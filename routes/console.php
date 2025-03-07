<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Comando para consolidar los datos de la encuesta
// Artisan::command('survey:consolidate', function () {
//     $this->info('Consolidating survey data...');
//     // Artisan::call('survey:consolidate'); // Ejecutar el comando correctamente
// })->purpose('Consolidate survey data into summary table');

app(Schedule::class)->command('survey:consolidate')->cron('0 0 1 */2 *');

// Programar el comando para que se ejecute cada 2 meses
// Schedule::command('survey:consolidate')->cron('0 0 1 */2 *');
