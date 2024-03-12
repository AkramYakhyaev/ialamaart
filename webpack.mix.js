const { mix } = require('laravel-mix');

mix.disableNotifications();

mix.less('resources/assets/less/style.less', 'public/css');

mix.less('resources/assets/less/dashboard.less', 'public/css');

mix.scripts([
    'resources/assets/js/functions.js',
    'resources/assets/js/app.js'
], 'public/js/app.js');

mix.scripts([
    'resources/assets/js/functions.js',
    'resources/assets/js/dashboard.js'
], 'public/js/dashboard.js');