<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::query()->updateOrCreate(['name' => 'Super Admin']);
        $permissions = Permission::all()->pluck('name')->toArray();
        $role->givePermissionTo($permissions);

        // Jefe de departamento
        $jefe = Role::query()->updateOrCreate(['name' => 'Jefe de departamento']);
        $jefe->syncPermissions([
            'solicitudes.create',
            'solicitudes.view',
            'solicitudes.edit',
            'solicitudes.export',
            'lista-maestra.view',
            'org.personal.view',
            'lista-maestra.download',
            'lista-maestra.files.download',
        ]);

        // coordinación de calidad
        $coord = Role::query()->updateOrCreate(['name' => 'coordinación de calidad']);
        $coord->syncPermissions([
            'lista-maestra.view',
            'org.personal.view',
            'lista-maestra.download',
            'quejas.review',
        ]);
    }
}
