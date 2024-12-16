<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModalidadesSeleccionSeeder extends Seeder
{
    public function run()
    {
        $modalidades = [
            ['codigo' => 'CCE-01', 'descripcion' => 'Solicitud de información a los Proveedores'],
            ['codigo' => 'CCE-02', 'descripcion' => 'Licitación pública'],
            ['codigo' => 'CCE-17', 'descripcion' => 'Licitación pública (Obra pública)'],
            ['codigo' => 'CCE-03', 'descripcion' => 'Concurso de méritos con precalificación (descontinuado)'],
            ['codigo' => 'CCE-04', 'descripcion' => 'Concurso de méritos abierto (descontinuado)'],
            ['codigo' => 'CCE-20-Concurso_Meritos_Sin_Lista_Corta_1Sobre', 'descripcion' => 'Concurso de méritos abierto'],
            ['codigo' => 'CCE-05', 'descripcion' => 'Contratación directa (con ofertas)'],
            ['codigo' => 'CCE-06', 'descripcion' => 'Selección abreviada menor cuantía'],
            ['codigo' => 'CCE-18-Seleccion_Abreviada_Menor_Cuantia_Sin_Manifestacion_Interes', 'descripcion' => 'Selección Abreviada de Menor Cuantia sin Manifestacion de Interés'],
            ['codigo' => 'CCE-19-Concurso_Meritos_Con_Lista_Corta_1Sobre', 'descripcion' => 'Concurso de méritos con precalificación'],
            ['codigo' => 'CCE-07', 'descripcion' => 'Selección abreviada subasta inversa'],
            ['codigo' => 'CCE-10', 'descripcion' => 'Mínima cuantía'],
            ['codigo' => 'CCE-11||01', 'descripcion' => 'Contratación régimen especial - Selección de comisionista'],
            ['codigo' => 'CCE-11||02', 'descripcion' => 'Contratación régimen especial - Enajenación de bienes para intermediarios idóneos'],
            ['codigo' => 'CCE-11||03', 'descripcion' => 'Contratación régimen especial - Régimen especial'],
            ['codigo' => 'CCE-11||04', 'descripcion' => 'Contratación régimen especial - Banco multilateral y organismos multilaterales'],
            ['codigo' => 'CCE-15||01', 'descripcion' => 'Contratación régimen especial (con ofertas) - Selección de comisionista'],
            ['codigo' => 'CCE-15||02', 'descripcion' => 'Contratación régimen especial (con ofertas) - Enajenación de bienes para intermediarios idóneos'],
            ['codigo' => 'CCE-15||03', 'descripcion' => 'Contratación régimen especial (con ofertas) - Régimen especial'],
            ['codigo' => 'CCE-15||04', 'descripcion' => 'Contratación régimen especial (con ofertas) - Banco multilateral y organismos multilaterales'],
            ['codigo' => 'CCE-16', 'descripcion' => 'Contratación directa.'],
            ['codigo' => 'CCE-99', 'descripcion' => 'Selección abreviada - acuerdo marco'],
        ];

        DB::table('Sgc_modalidades_seleccion')->insert($modalidades);

    }
}
