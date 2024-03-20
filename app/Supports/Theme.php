<?php

namespace App\Supports;

use App\Events\ThemeDisabled;
use App\Events\ThemeDisabling;
use App\Events\ThemeEnabled;
use App\Events\ThemeEnabling;
use App\Traits\ComposerTrait;
use Illuminate\Container\Container;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class Theme
{
    use ComposerTrait;

    /**
     * The theme path.
     *
     * @var string
     */
    protected $path;

    /**
     * The Parent theme.
     *
     * @var string
     */
    protected $parent;

    /**
     * The theme status (enabled or not).
     *
     * @var string
     */
    protected $status = false;

    /**
     * The constructor.
     *
     * @param Container $app
     * @param $name
     * @param $path
     */
    public function __construct($path)
    {
        $this->setPath($path);

        if ($this->isActive()) {
            // Add theme.THEME_NAME namespace to be able to force views from specific theme
            View::prependNamespace('theme.' . $this->getSnakeName(), $this->getPath('resources/views'));
            View::replaceNamespace('theme', $this->getPath('resources/views'));
        }
    }

    /**
     * Check if theme is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->get('extra.theme.active', false);
    }

    /**
     * Activate theme.
     *
     * @return bool
     */
    public function activate()
    {
        return $this->set('extra.theme.active', true);
    }

    /**
     * Deactivate theme.
     *
     * @return bool
     */
    public function deactivate()
    {
        return $this->set('extra.theme.active', false);
    }

    /**
     * Get path.
     */
    public function getPath(string $path = null): string
    {
        return $this->cleanPath(Str::finish($this->path, DIRECTORY_SEPARATOR) . $path);
    }

    /**
     * Get assets path.
     */
    public function getAssetsPath(string $path = null): string
    {
        return Config::get('themes-manager.symlink_path', 'themes') . DIRECTORY_SEPARATOR . $this->getLowerVendor() . DIRECTORY_SEPARATOR . $this->getLowerName() . DIRECTORY_SEPARATOR . $this->cleanPath($path);
    }

    /**
     * Get theme views paths.
     *
     * @param string $path
     */
    public function getViewPaths($path = ''): array
    {
        // Build Paths array.
        // All paths are relative to Config::get('themes-manager.directory')
        $paths = [];
        $theme = $this;

        do {
            $viewsPath = $theme->getPath('views' . ($path ? "/{$path}" : ''));

            if (!in_array($viewsPath, $paths)) {
                $paths[] = $viewsPath;
            }
        } while ($theme = $theme->getParent());

        return array_reverse($paths);
    }

    /**
     * Get theme translations paths.
     *
     * @param string $path
     */
    public function getTransaltionPaths($path = ''): array
    {
        // Build Paths array.
        // All paths are relative to Config::get('themes-manager.directory')
        $paths = [];
        $theme = $this;

        do {
            $translationsPath = $theme->getPath('lang' . ($path ? "/{$path}" : ''));

            if (File::exists($translationsPath) && !in_array($translationsPath, $paths)) {
                $paths[] = $translationsPath;
            }
        } while ($theme = $theme->getParent());

        return array_reverse($paths);
    }

    /**
     * Set path.
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $this->cleanPath($path);

        return $this;
    }

    /**
     * Check if has parent Theme.
     */
    public function hasParent(): bool
    {
        return !is_null($this->parent);
    }

    /**
     * Set parent Theme.
     */
    public function setParent(Theme $theme)
    {
        $this->parent = $theme;
    }

    /**
     * Get parent Theme.
     *
     * @return null|Theme
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set theme's status.
     *
     * @param $status
     *
     * @return bool
     */
    public function setStatus(bool $status): Theme
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Check is current status is same as the one given.
     *
     * @param $status
     */
    public function isStatus(bool $status = false): bool
    {
        return $this->status === $status;
    }

    /**
     * Determine whether the current theme activated.
     */
    public function enabled(): bool
    {
        return $this->isStatus(true);
    }

    /**
     *  Determine whether the current theme not disabled.
     */
    public function disabled(): bool
    {
        return !$this->enabled();
    }

    /**
     * Disable the current theme.
     */
    public function disable(bool $withEvent = true): Theme
    {
        // Check if current is active and currently enabled
        if ($this->isActive() && $this->enabled()) {
            if ($withEvent) {
                event(new ThemeDisabling($this->getName()));
            }

            $this->setStatus(false);

            if ($withEvent) {
                event(new ThemeDisabled($this->getName()));
            }
        }

        return $this;
    }

    /**
     * Enable the current theme.
     *
     * @param null|mixed $defaultViewPaths
     * @param null|mixed $defaultMailViewPaths
     */
    public function enable(bool $withEvent = true, $defaultViewPaths = null, $defaultMailViewPaths = null): Theme
    {
        // Check if current is active and currently disabled
        if ($this->isActive() && $this->disabled()) {
            if ($withEvent) {
                event(new ThemeEnabling($this->getName()));
            }

            $this->setStatus(true);
            $this->registerViews($defaultViewPaths, $defaultMailViewPaths);
            $this->registerTranlastions();
            $this->registerConfigs();

            if ($withEvent) {
                event(new ThemeEnabled($this->getName()));
            }
        }

        return $this;
    }

    /**
     * Get theme asset url.
     *
     * @param string $url
     * @param bool   $absolutePath
     */
    public function url($url, $absolutePath = true, bool $version = true): ?string
    {
        $url = ltrim($url, DIRECTORY_SEPARATOR);

        // return external URLs unmodified
        if (preg_match('/^((http(s?):)?\/\/)/i', $url)) {
            return $url;
        }

        // Is theme folder located on the web (ie AWS)? Dont lookup parent themes...
        if (preg_match('/^((http(s?):)?\/\/)/i', $this->getAssetsPath())) {
            return $this->getAssetsPath($url);
        }

        // Check for valid {xxx} keys and replace them with the Theme's configuration value (in composer.json)
        preg_match_all('/\{(.*?)\}/', $url, $matches);
        foreach ($matches[1] as $param) {
            if (($value = $this->get("extra.theme.{$param}")) !== null) {
                $url = str_replace('{' . $param . '}', $value, $url);
            }
        }

        // Lookup asset in current's theme assets path
        $fullUrl = rtrim((empty($this->getAssetsPath()) ? '' : DIRECTORY_SEPARATOR) . $this->getAssetsPath($url), DIRECTORY_SEPARATOR);
        if (File::exists(public_path($fullUrl))) {
            $fullUrl = ltrim(str_replace('\\', '/', $fullUrl), '/');
            $versionTag = hash_file('md5', public_path($fullUrl));

            return ($absolutePath ? asset('') . $fullUrl : $fullUrl) . ($version ? '?v=' . $versionTag : '');
        }

        // If not found then lookup in parent's theme assets path
        if ($parentTheme = $this->getParent()) {
            return $parentTheme->url($url, $absolutePath, $version);
        }   // No parent theme? Lookup in the public folder.
        if (File::exists(public_path($url))) {
            $url = ltrim(str_replace('\\', '/', $url), '/');
            $versionTag = hash_file('md5', public_path($url));

            return ($absolutePath ? asset('') . $url : $url) . ($version ? '?v=' . $versionTag : '');
        }

        Log::warning("Asset [{$url}] not found for Theme [{$this->getName()}]");

        return ltrim(str_replace('\\', '/', $url));
    }

    /**
     * List theme's available layouts.
     *
     * @return Collection
     */
    public function listLayouts()
    {
        $layouts = collect();

        $layoutDirs = $this->getViewPaths('layouts');
        foreach ($layoutDirs as $layoutDir) {
            foreach (glob($layoutDir . '/{**/*,*}.php', GLOB_BRACE) as $layout) {
                $layouts->put($layout, basename($layout, '.blade.php'));
            }
        }

        return $layouts;
    }

    /**
     * Clean Path by replacing all / by DIRECTORY_SEPARATOR.
     *
     * @param string $path
     *
     * @return string
     */
    protected function cleanPath($path = '')
    {
        if ($path) {
            $path = str_replace('/', DIRECTORY_SEPARATOR, $path);

            if (!is_file($path)) {
                Str::finish($path, DIRECTORY_SEPARATOR);
            }
        }

        return $path;
    }

    /**
     * Create public assets directory path.
     */
    protected function createPublicAssetsStructure()
    {
        // Create target symlink parent directory if required
        $publicPath = public_path(Config::get('themes-manager.symlink_path', 'themes'));

        if (!File::exists($publicPath)) {
            app(Filesystem::class)->makeDirectory($publicPath, 0755);
        }

        $publicAssetsPath = dirname(public_path($this->getAssetsPath()));

        // Create vendor directory if not exists yet
        if (!File::exists($publicAssetsPath)) {
            app(Filesystem::class)->makeDirectory($publicAssetsPath, 0755);
        }
    }

    /**
     * Register theme's translations.
     */
    protected function registerTranlastions()
    {
        // Register Translation paths
        $paths = $this->getTransaltionPaths();
        $translator = app()->make(Translator::class);

        $paths = array_map(function ($path) use ($translator) {
            $path = rtrim($path, DIRECTORY_SEPARATOR);

            $translator->addNamespace('theme', $path);

            return $path;
        }, $paths);
    }

    protected function registerConfigs()
    {
        $config_path = $this->getPath('config/options.php');

        if(File::exists($config_path)) {
            $options = File::requireOnce($config_path);
            
            if(is_array($options)) {
                foreach($options as $key => $value) {
                    Config::set('theme.'.$key, $value);
                }
            }
        }
    }

    /**
     * Register theme's views in ViewFinder.
     *
     * @param mixed $defaultViewPaths
     * @param mixed $defaultMailViewPaths
     */
    protected function registerViews($defaultViewPaths, $defaultMailViewPaths)
    {
        // Create symlink for public resources if not existing yet
        $assetsPath = $this->getPath('public');
        $publicAssetsPath = public_path($this->getAssetsPath());

        $this->createPublicAssetsStructure();

        if (!File::exists($publicAssetsPath) && File::exists($assetsPath)) {
            if (Config::get('themes-manager.symlink_relative', false)) {
                app(Filesystem::class)->relativeLink($assetsPath, rtrim($publicAssetsPath, DIRECTORY_SEPARATOR));
            } else {
                app(Filesystem::class)->link($assetsPath, rtrim($publicAssetsPath, DIRECTORY_SEPARATOR));
            }
        }

        // Register theme views path
        $paths = $this->getViewPaths();
        $paths = array_map(function ($path) {
            $path = rtrim($path, DIRECTORY_SEPARATOR);
            View::getFinder()->prependLocation("{$path}");

            return $path;
        }, $paths);

        // Update config view.paths to work with errors views
        if (is_array($defaultViewPaths)) {
            Config::set('view.paths', array_merge($paths, $defaultViewPaths));
        } else {
            Config::set('view.paths', array_merge($paths, [$defaultViewPaths]));
        }

        // Register all vendor views
        $vendorViewsPath = $this->getPath('resources/views/vendor');
        if (File::exists($vendorViewsPath)) {
            $directories = scandir($vendorViewsPath);
            foreach ($directories as $namespace) {
                if ('.' != $namespace && '..' != $namespace) {
                    if (!empty(Config::get('view.paths')) && is_array(Config::get('view.paths'))) {
                        foreach (Config::get('view.paths') as $viewPath) {
                            if (is_dir($appPath = $viewPath . '/vendor/' . $namespace)) {
                                View::prependNamespace($namespace, $appPath);
                            }
                        }
                    }
                }
            }
        }

        // Update config mail.markdown.paths to work with mail views
        $mailViewsPath = $this->getPath('resources/views/vendor/mail');
        if (File::exists($vendorViewsPath) && is_dir($mailViewsPath)) {
            if (is_array($defaultMailViewPaths)) {
                Config::set('mail.markdown.paths', array_merge([
                    $mailViewsPath,
                ], $defaultMailViewPaths));
            } else {
                Config::set('mail.markdown.paths', [$mailViewsPath]);
            }

            app()->make(Markdown::class)->loadComponentsFrom(Config::get('mail.markdown.paths'));
        }
    }
}
