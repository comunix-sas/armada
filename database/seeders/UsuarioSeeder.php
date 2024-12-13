<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Asegúrate de que el modelo User esté correctamente importado
use Spatie\Permission\Models\Role;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        // Crear el usuario
        $usuario = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456789'), // Asegúrate de encriptar la contraseña
        ]);

        // Asignar el rol de Administrador
        $usuario->assignRole('Administrador');
    }
} 
