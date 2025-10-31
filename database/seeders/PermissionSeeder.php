<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            'access dashboard',

            'impersonate',

            'view users',
            'create users',
            'update users',
            'delete users',

            'view roles',
            'create roles',
            'update roles',
            'delete roles',

            'view permissions',
            'create permissions',
            'update permissions',
            'delete permissions',
            // Modulo de Solicitudes
            'solicitudes.create',   // crear solicitud
            'solicitudes.view',     // ver estado o detalle
            'solicitudes.review',   // revisar solicitudes
            'solicitudes.edit',     // editar (rechazadas/correcciones)
            'solicitudes.export',   // descargar PDF de formato

            // Modulo Lista Maestra
            'lista-maestra.view',   // ver modulo
            'lista-maestra.export', // exportar PDF
            'lista-maestra.edit',   // editar
            'lista-maestra.delete', // borrrar 

            // Modulo Organización / Personal 
            'org.personal.view',    // ver organigrama
            'org.personal.edit',    // editar asignaciones

            // Módulo Quejas y Sugerencias
            'quejas.review',      // revisar / responder quejas
        ];

        foreach ($permissions as $permission) {
            Permission::query()->updateOrCreate([
                'name' => $permission,
            ]);
        }

    }
}
