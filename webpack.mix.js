let mix = require('laravel-mix');
const CleanWebpackPlugin = require('clean-webpack-plugin');

// paths to clean
var pathsToClean = [
    'public/assets/app/js',
    'public/assets/app/css',
    'public/assets/admin/js',
    'public/assets/admin/css',
    'public/assets/auth/css',
];

// the clean options to use
var cleanOptions = {};

mix.webpackConfig({
    plugins: [
        new CleanWebpackPlugin(pathsToClean, cleanOptions)
    ]
});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

/*
 |--------------------------------------------------------------------------
 | Core
 |--------------------------------------------------------------------------
 |
 */
mix.scripts([
  'node_modules/jquery/dist/jquery.js',
  'node_modules/pace-progress/pace.js',
  'resources/assets/main.js'
], 'public/assets/app/js/app.js').version();

mix.styles([
  'node_modules/font-awesome/css/font-awesome.css',
  'node_modules/pace-progress/themes/blue/pace-theme-minimal.css',
], 'public/assets/app/css/app.css').version();

mix.copy([
 'node_modules/font-awesome/fonts/',
], 'public/assets/app/fonts');

/*
 |--------------------------------------------------------------------------
 | Auth
 |--------------------------------------------------------------------------
 |
 */
mix.sass('resources/assets/auth/scss/login.scss', 'public/assets/auth/css/login.css').version();
mix.sass('resources/assets/auth/scss/register.scss', 'public/assets/auth/css/register.css').version();
mix.sass('resources/assets/auth/scss/passwords.scss', 'public/assets/auth/css/passwords.css').version();

mix.styles([
    'node_modules/bootstrap/dist/css/bootstrap.css',
    'node_modules/gentelella/vendors/animate.css/animate.css',
    'node_modules/gentelella/build/css/custom.css',
], 'public/assets/auth/css/auth.css').version();

/*
 |--------------------------------------------------------------------------
 | Admin
 |--------------------------------------------------------------------------
 |
 */
mix.scripts([
    'node_modules/bootstrap/dist/js/bootstrap.js',
    'node_modules/gentelella/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js',
    'node_modules/icheck/icheck.js',
    'bower_components/switchery/dist/switchery.js',
    'node_modules/select2/dist/js/select2.full.js',
    'node_modules/pnotify/src/pnotify.js',
    'node_modules/pnotify/src/pnotify.buttons.js',
    'node_modules/pnotify/src/pnotify.nonblock.js',
    'node_modules/gentelella/build/js/custom.js',
], 'public/assets/admin/js/admin-core.js').version();

mix.styles([
    'node_modules/bootstrap/dist/css/bootstrap.css',
    'node_modules/gentelella/vendors/animate.css/animate.css',
    'node_modules/icheck/skins/flat/green.css',
    'bower_components/switchery/dist/switchery.css',
    'node_modules/select2/dist/css/select2.css',
    'node_modules/pnotify/src/pnotify.css',
    'node_modules/pnotify/src/pnotify.buttons.css',
    'node_modules/pnotify/src/pnotify.nonblock.css',
    'node_modules/gentelella/build/css/custom.css',
], 'public/assets/admin/css/admin-core.css').version();

mix.copy([
    'node_modules/gentelella/vendors/bootstrap/dist/fonts',
], 'public/assets/admin/fonts');

mix.copy([
    'node_modules/icheck/skins/flat/green.png',
    'node_modules/icheck/skins/flat/green@2x.png',
], 'public/assets/admin/css');

mix.sass('resources/assets/admin/scss/admin.scss', 'public/assets/admin/css/admin.css').version();
mix.sass('resources/assets/admin/scss/registration-form.scss', 'public/assets/admin/css/registration-form.css').version();

mix.scripts([
    'resources/assets/admin/js/field-creation.js'
], 'public/assets/admin/js/field-creation.js');
mix.scripts([
    'resources/assets/admin/js/banner-listing.js'
], 'public/assets/admin/js/banner-listing.js');
mix.scripts([
    'resources/assets/admin/js/stage-listing.js'
], 'public/assets/admin/js/stage-listing.js');
mix.scripts([
    'resources/assets/admin/js/game-ordering.js'
], 'public/assets/admin/js/game-ordering.js');

mix.copy([
    'bower_components/bootstrap-fileinput/img/loading-sm.gif',
    'bower_components/bootstrap-fileinput/img/loading.gif',
], 'public/assets/admin/img');
mix.styles([
    'bower_components/bootstrap-fileinput/css/fileinput.css'
], 'public/assets/admin/css/banner-form.css');
mix.scripts([
    'bower_components/bootstrap-fileinput/js/fileinput.js'
], 'public/assets/admin/js/banner-form.js');

mix.scripts([
    'resources/assets/admin/js/image-upload.js'
], 'public/assets/admin/js/image-upload.js');
