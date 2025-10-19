<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrgDepartment;

class OrgDepartmentsSeeder extends Seeder
{
    public function run(): void
    {
        $deps = [
            // ↑↑ NUEVOS “contenedores” para que no queden vacíos ↑↑
            ['slug'=>'direccion',                         'nombre'=>'Dirección'],
            ['slug'=>'subdireccion-academica',           'nombre'=>'Subdirección Académica'],
            ['slug'=>'subdireccion-servicios',           'nombre'=>'Subdirección de Servicios Administrativos'],
            ['slug'=>'subdireccion-planeacion-vinc',     'nombre'=>'Subdirección de Planeación y Vinculación'],
            ['slug'=>'calidad-sgc',                      'nombre'=>'Calidad (SGC)'],

            // Departamentos “operativos” del organigrama
            ['slug'=>'planeacion-programacion-presupuesto', 'nombre'=>'Planeación, Programación y Presupuesto'],
            ['slug'=>'comunicacion-difusion',               'nombre'=>'Comunicación y Difusión'],
            ['slug'=>'gestion-tecnologica-vinculacion',     'nombre'=>'Gestión Tecnológica y Vinculación'],
            ['slug'=>'servicios-escolares',                 'nombre'=>'Servicios Escolares'],
            ['slug'=>'centro-informacion',                  'nombre'=>'Centro de Información'],
            ['slug'=>'actividades-extraescolares',          'nombre'=>'Actividades Extraescolares'],
            ['slug'=>'recursos-financieros',                'nombre'=>'Recursos Financieros'],
            ['slug'=>'recursos-humanos',                    'nombre'=>'Recursos Humanos'],
            ['slug'=>'mantenimiento-equipo',                'nombre'=>'Mantenimiento de Equipo'],
            ['slug'=>'recursos-materiales-servicios',       'nombre'=>'Recursos Materiales y Servicios'],
            ['slug'=>'servicios-generales',                 'nombre'=>'Servicios Generales'],
            ['slug'=>'centro-computo',                      'nombre'=>'Centro de Cómputo'],
            ['slug'=>'ciencias-de-la-tierra',               'nombre'=>'Ciencias de la Tierra'],
            ['slug'=>'sistemas-computacion',                'nombre'=>'Sistemas y Computación'],
            ['slug'=>'electrica-electronica',               'nombre'=>'Eléctrica y Electrónica'],
            ['slug'=>'metal-mecanica',                      'nombre'=>'Metal Mecánica'],
            ['slug'=>'ciencias-economico-administrativas',  'nombre'=>'Ciencias Económico-Administrativas'],
            ['slug'=>'ciencias-basicas',                    'nombre'=>'Ciencias Básicas'],
            ['slug'=>'quimica-bioquimica',                  'nombre'=>'Química y Bioquímica'],
            ['slug'=>'desarrollo-academico',                'nombre'=>'Desarrollo Académico'],
            ['slug'=>'division-estudios-profesionales',     'nombre'=>'División de Estudios Profesionales'],
        ];

        foreach ($deps as $d) {
            OrgDepartment::firstOrCreate(
                ['slug' => $d['slug']],
                ['nombre' => $d['nombre'], 'area_id' => null]
            );
        }
    }
}
