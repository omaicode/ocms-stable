const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

const resourcePath = 'resources/assets/admin/partial-editor';
const distPath = 'resources/assets/admin/vendors/partial-editor';
mix.setPublicPath(distPath).mergeManifest();

mix.js(resourcePath + '/js/partial-editor.js', '/js/partial-editor.js')
.sass( resourcePath + '/sass/partial-editor.scss', '/css/partial-editor.css').vue();

if (mix.inProduction()) {
    mix.version();
}
