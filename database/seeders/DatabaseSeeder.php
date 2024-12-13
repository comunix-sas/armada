<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    $this->call([
      MesesSeeder::class,
      CodigoSeeder::class,
      UbicacionesSeeder::class,
      ModalidadesSeleccionSeeder::class,
      PermisosSeeder::class,
      UsuarioSeeder::class,
    ]);
  }
}
