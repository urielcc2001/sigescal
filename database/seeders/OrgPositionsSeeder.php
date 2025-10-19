<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrgDepartment;
use App\Models\OrgPosition;

class OrgPositionsSeeder extends Seeder
{
    public function run(): void
    {
        $deptId = fn (?string $slug) => $slug ? optional(OrgDepartment::firstWhere('slug', $slug))->id : null;

        $puestos = [
            // Dirección y Subdirecciones — AHORA con departamento asignado
            ['slug'=>'director',           'titulo'=>'Director',                                   'nivel'=>'director',    'dept'=>'direccion',                     'orden'=>1],
            ['slug'=>'subdir-academica',   'titulo'=>'Subdirector(a) Académica',                   'nivel'=>'subdirector', 'dept'=>'subdireccion-academica',        'orden'=>10],
            ['slug'=>'subdir-servicios',   'titulo'=>'Subdirector(a) de Servicios Administrativos', 'nivel'=>'subdirector', 'dept'=>'subdireccion-servicios',        'orden'=>11],
            ['slug'=>'subdir-vinculacion', 'titulo'=>'Subdirector(a) de Planeación y Vinculación', 'nivel'=>'subdirector', 'dept'=>'subdireccion-planeacion-vinc',  'orden'=>12],

            // Calidad — agrupados bajo “Calidad (SGC)”
            ['slug'=>'coord-calidad',      'titulo'=>'Coordinación de Calidad',                    'nivel'=>'coordinacion','dept'=>'calidad-sgc',                    'orden'=>20],
            ['slug'=>'ctrl-documentos',    'titulo'=>'Controlador(a) de Documentos del SGC',       'nivel'=>'control',     'dept'=>'calidad-sgc',                    'orden'=>21],

            // Jefaturas (20 en total) — sin cambios
            ['slug'=>'jdep-planeacion-pp',         'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'planeacion-programacion-presupuesto', 'orden'=>300],
            ['slug'=>'jdep-comunicacion',          'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'comunicacion-difusion',               'orden'=>301],
            ['slug'=>'jdep-gestion-vinculacion',   'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'gestion-tecnologica-vinculacion',     'orden'=>302],
            ['slug'=>'jdep-servicios-escolares',   'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'servicios-escolares',                 'orden'=>303],
            ['slug'=>'jdep-centro-informacion',    'titulo'=>'Jefe(a) de Centro', 'nivel'=>'jefe_depto', 'dept'=>'centro-informacion',                  'orden'=>304],
            ['slug'=>'jdep-actividades-extra',     'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'actividades-extraescolares',          'orden'=>305],
            ['slug'=>'jdep-recursos-financieros',  'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'recursos-financieros',                'orden'=>306],
            ['slug'=>'jdep-recursos-humanos',      'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'recursos-humanos',                    'orden'=>307],
            ['slug'=>'jdep-mantenimiento-equipo',  'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'mantenimiento-equipo',                'orden'=>308],
            ['slug'=>'jdep-recursos-materiales',   'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'recursos-materiales-servicios',       'orden'=>309],
            ['slug'=>'jdep-centro-computo',        'titulo'=>'Jefe(a) de Centro', 'nivel'=>'jefe_depto', 'dept'=>'centro-computo',                      'orden'=>310],
            ['slug'=>'jdep-ciencias-tierra',       'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'ciencias-de-la-tierra',               'orden'=>311],
            ['slug'=>'jdep-sistemas',              'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'sistemas-computacion',                'orden'=>312],
            ['slug'=>'jdep-electrica-electronica', 'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'electrica-electronica',               'orden'=>313],
            ['slug'=>'jdep-metal-mecanica',        'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'metal-mecanica',                      'orden'=>314],
            ['slug'=>'jdep-economico-admin',       'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'ciencias-economico-administrativas',  'orden'=>315],
            ['slug'=>'jdep-ciencias-basicas',      'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'ciencias-basicas',                    'orden'=>316],
            ['slug'=>'jdep-quimica-bioquimica',    'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'quimica-bioquimica',                  'orden'=>317],
            ['slug'=>'jdep-desarrollo-academico',  'titulo'=>'Jefe(a) de Depto.', 'nivel'=>'jefe_depto', 'dept'=>'desarrollo-academico',                'orden'=>318],
            ['slug'=>'jdep-division-est-prof',     'titulo'=>'Jefe(a) de División','nivel'=>'jefe_depto', 'dept'=>'division-estudios-profesionales',     'orden'=>319],
        ];

        foreach ($puestos as $p) {
            OrgPosition::firstOrCreate(
                ['slug' => $p['slug']],
                [
                    'titulo'            => $p['titulo'],
                    'nivel'             => $p['nivel'],
                    'area_id'           => null,
                    'org_department_id' => $deptId($p['dept']),
                    'orden'             => $p['orden'],
                ]
            );
        }
    }
}
