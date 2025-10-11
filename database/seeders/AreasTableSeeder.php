<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Area;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            ['codigo' => 'CA', 'nombre' => 'Coordinación de Calidad'],
            ['codigo' => 'IR', 'nombre' => 'Ingreso'],
            ['codigo' => 'AC', 'nombre' => 'Académico'],
            ['codigo' => 'VI', 'nombre' => 'Vinculación'],
            ['codigo' => 'EG', 'nombre' => 'Egreso'],
            ['codigo' => 'AD', 'nombre' => 'Administración de los Recursos'],
            ['codigo' => 'PL', 'nombre' => 'Planeación'],
        ];

        foreach ($areas as $area) {
            Area::updateOrCreate(['codigo' => $area['codigo']], $area);
        }
    }
}
