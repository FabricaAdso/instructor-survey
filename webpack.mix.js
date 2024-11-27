const mix = require('laravel-mix');

mix.js('resources/js/test.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .webpackConfig({
    resolve: {
        alias: 
{
            apexcharts: path.resolve(__dirname, 'node_modules', 'apexcharts')
        }
    }
});