<?php

namespace App\Supports;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Psr\SimpleCache\InvalidArgumentException;
use RuntimeException;

class Menu
{
    /**
     * Get all registered links from package
     * @var array
     */
    protected $links = [];

    /**
     * Add link
     *
     * @param array $options
     *
     * @return $this
     */
    public function add(array $options): self
    {
        $defaultOptions = [
            'id' => '',
            'priority' => 99,
            'parent_id' => null,
            'name' => '',
            'icon' => null,
            'url' => '',
            'children' => [],
            'permissions' => [],
            'active' => false,
            'enable' => true
        ];

        if(isset($options['enable']) && !boolval($options['enable'])) {
            return $this;
        }

        $options = array_merge($defaultOptions, $options);
        $id = $options['id'];

        if (isset($options['children'])) {
            foreach($options['children'] as $idx => $sub) {
                $options['children'][$idx] = array_merge($defaultOptions, $sub);
            }
        }

        if (!$id && !app()->runningInConsole() && app()->isLocal()) {
            $calledClass = isset(debug_backtrace()[1]) ?
                debug_backtrace()[1]['class'] . '@' . debug_backtrace()[1]['function']
                :
                null;
            throw new RuntimeException('Menu id not specified: ' . $calledClass);
        }

        if (isset($this->links[$id]) && $this->links[$id]['name'] && !app()->runningInConsole() && app()->isLocal()) {
            $calledClass = isset(debug_backtrace()[1]) ?
                debug_backtrace()[1]['class'] . '@' . debug_backtrace()[1]['function']
                :
                null;
            throw new RuntimeException('Menu id already exists: ' . $id . ' on class ' . $calledClass);
        }

        if (isset($this->links[$id])) {

            $options['children'] = array_merge($options['children'], $this->links[$id]['children']);
            $options['permissions'] = array_merge($options['permissions'], $this->links[$id]['permissions']);

            $this->links[$id] = array_replace($this->links[$id], $options);

            return $this;
        }

        if ($options['parent_id']) {
            if (!isset($this->links[$options['parent_id']])) {
                $this->links[$options['parent_id']] = ['id' => $options['parent_id']] + $defaultOptions;
            }

            $this->links[$options['parent_id']]['children'][] = $options;

            $permissions = array_merge($this->links[$options['parent_id']]['permissions'], $options['permissions']);
            $this->links[$options['parent_id']]['permissions'] = $permissions;
        } else {
            $this->links[$id] = $options;
        }

        return $this;
    }

    /**
     * @param array|string $id
     * @param null $parentId
     *
     * @return $this
     */
    public function remove($id, $parentId = null): self
    {
        if ($parentId && !isset($this->links[$parentId])) {
            return $this;
        }

        $id = is_array($id) ? $id : func_get_args();
        foreach ($id as $item) {
            if (!$parentId) {
                Arr::forget($this->links, $item);
            } else {
                foreach ($this->links[$parentId]['children'] as $key => $child) {
                    if ($child['id'] === $item) {
                        Arr::forget($this->links[$parentId]['children'], $key);
                        break;
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @param string $id
     * @param null|string $parentId
     *
     * @return bool
     */
    public function has($id, $parentId = null): bool
    {
        if ($parentId) {
            if (!isset($this->links[$parentId])) {
                return false;
            }
            $id = $parentId . '.children.' . $id;
        }
        return Arr::has($this->links, $id . '.name');
    }


    /**
     * Rearrange links
     * @return Collection
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function all(): Collection
    {
        $currentUrl = URL::current();

        $prefix = request()->route()->getPrefix();
        if (!$prefix || $prefix === config('admin.admin_prefix', 'admin')) {
            $uri = explode('/', request()->route()->uri());
            $prefix = end($uri);
        }

        $routePrefix = '/' . $prefix;

        $links = $this->links;

        if (request()->isSecure()) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }

        $protocol .= config('admin.admin_prefix', 'admin');

        foreach ($links as $key => &$link) {
            if ($link['permissions'] && !Auth::user()->hasAnyPermission($link['permissions']) && !Auth::user()->hasRole(['Super Admin'], 'admins')) {
                Arr::forget($links, $key);
                continue;
            }

            $link['active'] = $currentUrl == $link['url']
                || (Str::contains($link['url'], $routePrefix) && !in_array($routePrefix, ['//', '/' . config('admin.admin_prefix', 'admin')]) && !Str::startsWith($link['url'], $protocol))
                || (isset($link['module-menu']) && (Str::contains($routePrefix, @$link['module-menu']) && !in_array($routePrefix, ['//', '/' . config('admin.admin_prefix', 'admin')]) && !Str::startsWith($link['url'], $protocol)));

            if (!count($link['children'])) {
                continue;
            }

            $link['children'] = collect($link['children'])->sortBy('priority')->toArray();

            foreach ($link['children'] as $subKey => $subMenu) {
                if ($subMenu['permissions'] && !Auth::user()->hasAnyPermission($subMenu['permissions']) && !Auth::user()->hasRole(['Super Admin'], 'admins')) {
                    Arr::forget($link['children'], $subKey);
                    continue;
                }

                if ($currentUrl == $subMenu['url'] || Str::contains($currentUrl, $subMenu['url'])) {
                    $link['children'][$subKey]['active'] = true;
                    $link['active'] = true;
                }
            }
        }

        return collect($links)->filter(fn($x) => $x['enable'] === true)->sortBy('priority');
    }
}
