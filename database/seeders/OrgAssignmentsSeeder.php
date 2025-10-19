<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\OrgPosition;
use App\Models\OrgAssignment;

class OrgAssignmentsSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::now()->toDateString();

        $assign = function (string $positionSlug, ?string $nombre) use ($today) {
            $pos = OrgPosition::firstWhere('slug', $positionSlug);
            if (!$pos) return;

            optional($pos->vigente)->update(['fin' => $today]);

            OrgAssignment::create([
                'org_position_id' => $pos->id,
                'user_id'   => null,
                'nombre'    => mb_strtoupper($nombre, 'UTF-8'),
                'correo'    => null,
                'telefono'  => null,
                'inicio'    => $nombre ? $today : null,
                'fin'       => null,
            ]);
        };

        // Dirección y Subdirecciones
        $assign('director',            'SANTIAGO ENRIQUE TORRES LOYO');
        $assign('subdir-vinculacion',  'FELIPE DE JESÚS NIÑO DE LA CRUZ');
        $assign('subdir-servicios',    'DAVID TOMÁS ILLANA');
        $assign('subdir-academica',    'JULIÁN KURI MAR');

        // Calidad
        $assign('coord-calidad',    'REBECA GLORIA TEJEDA');
        $assign('ctrl-documentos',  'JOSÉ ALBERTO VILLALOBOS SERRANO');

        // Jefaturas (20 total)
        $assign('jdep-planeacion-pp',         'Ana Maricarmen carrera Gómez');
        $assign('jdep-comunicacion',          'Karla rebeca barrera Anzaldo');
        $assign('jdep-gestion-vinculacion',   'Gloria Tejeda rebeca');
        $assign('jdep-servicios-escolares',   'Jose Alberto villalobos serrano');
        $assign('jdep-centro-informacion',    'Ana maría luna Vargas');
        $assign('jdep-actividades-extra',     'Milagros Rangel cruz');
        $assign('jdep-recursos-financieros',  'Lorena Zamora velazquez');
        $assign('jdep-recursos-humanos',      'Adriana Montalvo calles');
        $assign('jdep-mantenimiento-equipo',  'Rogelio rincón reyes');
        $assign('jdep-recursos-materiales',   'Rosa Eugenia diaz menendez');
        $assign('jdep-centro-computo',        'Julio Aguilar Carmona');
        $assign('jdep-ciencias-tierra',       'Candida alvarez Palafox');
        $assign('jdep-sistemas',              'Tomas torres Ramírez');
        $assign('jdep-electrica-electronica', 'Antonio jose hernandez Aguilar');
        $assign('jdep-metal-mecanica',        'Antonio jose hernandez Aguilar'); 
        $assign('jdep-economico-admin',       'Antonio de jesus roldan sabido');
        $assign('jdep-ciencias-basicas',      'Guillermo gomez pulido');
        $assign('jdep-quimica-bioquimica',    'Jesus rodriguez miranda');
        $assign('jdep-desarrollo-academico',  'Milagros del Carmen varela santos');
        $assign('jdep-division-est-prof',     'Martha monica hernandez cruz');
    }
}
