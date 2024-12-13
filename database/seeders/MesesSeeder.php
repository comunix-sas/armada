<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MesesSeeder extends Seeder {
    public function run() {
        $meses = [
            ['mes' => 1, 'nombre' => 'Enero'],
            ['mes' => 2, 'nombre' => 'Febrero'],
            ['mes' => 3, 'nombre' => 'Marzo'],
            ['mes' => 4, 'nombre' => 'Abril'],
            ['mes' => 5, 'nombre' => 'Mayo'],
            ['mes' => 6, 'nombre' => 'Junio'],
            ['mes' => 7, 'nombre' => 'Julio'],
            ['mes' => 8, 'nombre' => 'Agosto'],
            ['mes' => 9, 'nombre' => 'Septiembre'],
            ['mes' => 10, 'nombre' => 'Octubre'],
            ['mes' => 11, 'nombre' => 'Noviembre'],
            ['mes' => 12, 'nombre' => 'Diciembre'],
        ];

        DB::table('Sgc_meses')->insert($meses);
    }
}
