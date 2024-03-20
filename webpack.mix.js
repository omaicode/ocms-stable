const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

const resourcePath = 'resources/assets/admin';
const distPath = 'public/assets/admin';

mix.options({
    fileLoaderDirs: {
        fonts: 'assets/admin/fonts'
    }
})
mix.setPublicPath(distPath).mergeManifest();

mix.copyDirectory(resourcePath + '/img', distPath + '/img')
.copyDirectory(resourcePath + '/vendors', distPath + '/vendors')
.copyDirectory(resourcePath + '/webfonts', distPath + '/fonts')
.js(resourcePath + '/js/app.js', 'js/app.js')
.js(resourcePath + '/js/theme.js', 'js/theme.js')
.sass( resourcePath + '/scss/app.scss', 'css/app.css');

if (mix.inProduction()) {
    mix.version();
}
