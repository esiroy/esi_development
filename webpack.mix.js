const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |



mix.js([
        'resources/js/app.js'
    ], 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();
 */

/*
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    //Datatable Buttons
    .copy('node_modules/datatables.net-buttons/js/dataTables.buttons.min.js', 'public/js/dataTables.buttons.min.js', true)
    .version()
    .options({
        processCssUrls: false
    }).sourceMaps();
*/

mix.js(
        [
            "resources/js/app.js"
        ],
        "public/js/app.js"
    )
    .js(
        [
            "resources/js/croppie/croppie.js",
            "resources/js/datatables/jquery.dataTables.js",            
            "resources/js/app.js"
        ],
        "public/js/admin.js"
    )  
    .copy("resources/images/*.*", "public/images/")
    .copy("resources/sass/croppie/croppie.css", "public/css/croppie.css")
    .copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts')
    .sass("resources/sass/app.scss", "public/css/app.css")
    .sass("resources/sass/admin.scss", "public/css/admin.css")
    .version()
    .options({
        processCssUrls: false
    })
    .sourceMaps();