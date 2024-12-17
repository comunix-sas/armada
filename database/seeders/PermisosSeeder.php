<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisosSeeder extends Seeder
{
    public function run()
    {
        Permission::create(['name' => 'ver adquisiciones', 'guard_name' => 'web']);
        Permission::create(['name' => 'crear adquisiciones', 'guard_name' => 'web']);
        Permission::create(['name' => 'editar adquisiciones', 'guard_name' => 'web']);
        Permission::create(['name' => 'eliminar adquisiciones', 'guard_name' => 'web']);
        Permission::create(['name' => 'aprobar adquisiciones', 'guard_name' => 'web']);
        Permission::create(['name' => 'gestionar usuarios', 'guard_name' => 'web']);
        Permission::create(['name' => 'generar reportes', 'guard_name' => 'web']);
        Permission::create(['name' => 'ver reportes', 'guard_name' => 'web']);
        Permission::create(['name' => 'administrar contratos', 'guard_name' => 'web']);
        Permission::create(['name' => 'inspeccionar adquisiciones', 'guard_name' => 'web']);
        Permission::create(['name' => 'validar adquisiciones', 'guard_name' => 'web']);
        Permission::create(['name' => 'asignar adquisiciones', 'guard_name' => 'web']);
        Permission::create(['name' => 'ver reportes financieros', 'guard_name' => 'web']);
        Permission::create(['name' => 'generar reportes de gasto', 'guard_name' => 'web']);
        Permission::create(['name' => 'aprobar pagos', 'guard_name' => 'web']);
        Permission::create(['name' => 'generar reportes de pago', 'guard_name' => 'web']);
        Permission::create(['name' => 'rastrear pagos', 'guard_name' => 'web']);

        // Roles y asignación de permisos con guard_name 'web'
        $administrador = Role::create(['name' => 'Administrador', 'guard_name' => 'web']);
        $administrador->givePermissionTo([
            'ver adquisiciones',
            'crear adquisiciones',
            'editar adquisiciones',
            'eliminar adquisiciones',
            'aprobar adquisiciones',
            'gestionar usuarios',
            'generar reportes'
        ]);

        $gerenteProyecto = Role::create(['name' => 'Gerente de Proyecto', 'guard_name' => 'web']);
        $gerenteProyecto->givePermissionTo([
            'ver adquisiciones',
            'crear adquisiciones',
            'ver reportes',
            'editar adquisiciones'
        ]);

        $ejecutivoContractual = Role::create(['name' => 'Ejecutivo Contractual', 'guard_name' => 'web']);
        $ejecutivoContractual->givePermissionTo([
            'ver adquisiciones',
            'editar adquisiciones',
            'aprobar adquisiciones',
            'administrar contratos'
        ]);

        $supervisorContractual = Role::create(['name' => 'Supervisor Contractual', 'guard_name' => 'web']);
        $supervisorContractual->givePermissionTo([
            'ver adquisiciones',
            'inspeccionar adquisiciones',
            'generar reportes',
            'validar adquisiciones'
        ]);

        $jefeAdquisiciones = Role::create(['name' => 'Jefe de Adquisiciones', 'guard_name' => 'web']);
        $jefeAdquisiciones->givePermissionTo([
            'ver adquisiciones',
            'aprobar adquisiciones',
            'generar reportes',
            'asignar adquisiciones',
            'editar adquisiciones'
        ]);

        $ordenadorGasto = Role::create(['name' => 'Ordenador del Gasto', 'guard_name' => 'web']);
        $ordenadorGasto->givePermissionTo([
            'ver adquisiciones',
            'aprobar adquisiciones',
            'ver reportes financieros',
            'generar reportes de gasto'
        ]);

        $controlPagos = Role::create(['name' => 'Control de Pagos', 'guard_name' => 'web']);
        $controlPagos->givePermissionTo([
            'ver adquisiciones',
            'aprobar pagos',
            'ver reportes financieros',
            'generar reportes de pago',
            'rastrear pagos'
        ]);
    }
}
