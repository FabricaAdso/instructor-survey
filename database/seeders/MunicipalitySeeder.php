<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MunicipalitySeeder extends Seeder
{
    public function run()
    {
        $municipalities = [
            ['name' => 'Almaguer'],
            ['name' => 'Argelia'],
            ['name' => 'Balboa'],
            ['name' => 'Bolívar'],
            ['name' => 'Buenos Aires'],
            ['name' => 'Cajibío'],
            ['name' => 'Caldono'],
            ['name' => 'Caloto'],
            ['name' => 'Corinto'],
            ['name' => 'El Tambo'],
            ['name' => 'Florencia'],
            ['name' => 'Guapí'],
            ['name' => 'Inzá'],
            ['name' => 'Jambaló'],
            ['name' => 'La Sierra'],
            ['name' => 'La Vega'],
            ['name' => 'López de Micay'],
            ['name' => 'Mercaderes'],
            ['name' => 'Moraña'],
            ['name' => 'Morales'],
            ['name' => 'Padilla'],
            ['name' => 'Páez'],
            ['name' => 'Piamonte'],
            ['name' => 'Popayán'],
            ['name' => 'Puerto Tejada'],
            ['name' => 'Puracé'],
            ['name' => 'Rosas'],
            ['name' => 'San Sebastián'],
            ['name' => 'Santander de Quilichao'],
            ['name' => 'Santa Rosa'],
            ['name' => 'Silvia'],
            ['name' => 'Sotará'],
            ['name' => 'Suárez'],
            ['name' => 'Timbío'],
            ['name' => 'Timbiquí'],
            ['name' => 'Toribío'],
            ['name' => 'Tres de Enero'],
            ['name' => 'Villa Rica'],
        ];

        DB::table('municipalities')->insert($municipalities);
    }
}
