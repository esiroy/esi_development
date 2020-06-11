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
            "resources/js/datatables/jquery.dataTables.js",
            "resources/js/datatables/dataTables.bootstrap4.js",
            "resources/js/datatables/dataTables.buttons.js",
            "resources/js/datatables/buttons.bootstrap4.js",
            "resources/js/datatables/buttons.html5.js",
            "resources/js/datatables/dataTables.select.js",
            "resources/js/datatables/buttons.print.js",
            "resources/js/datatables/buttons.colVis.js",
            "resources/js/pdfmake/pdfmake.js",
            "resources/js/jszip/jszip.js",
            "resources/js/select2/select2.full.js",
            "resources/js/datatables/dataTables.fixedColumns.js",
            "resources/js/app.js"
        ],
        "public/js/app.js"
    )
    .copy(
        "node_modules/pdfmake/build/vfs_fonts.js",
        "public/js/vfs_fonts.js",
        true
    )
    .sass(
        "resources/sass/app.scss", "public/css/app.css")
    .version()
    .options({
        processCssUrls: false
    })
    .sourceMaps();