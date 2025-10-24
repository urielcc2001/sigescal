<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $students = [
            [
                'numcontrol' => '23350001',
                'nombre'     => 'KATIA ACEVEDO VERA',
                'semestre'   => 3,
                'carrera_code' => 'IBQOK', // Ing. Bioquímica
                'grupo'      => 'A',
                'turno'      => 'MATUTINO',
                'aula'       => 'B-203',
                'telefono'   => '2871234567',
                'email'      => 'L23350001@tuxtepec.tecnm.mx',
                'password'   => Hash::make('23350001'),
                'must_change_password' => true,
                'last_login_at' => null,
                'status'     => 'active',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'numcontrol' => '23350002',
                'nombre'     => 'ZURYSHADAI AGUILAR GARCIA',
                'semestre'   => 5,
                'carrera_code' => 'ICOK', // Ing. Civil
                'grupo'      => 'B',
                'turno'      => 'VESPERTINO',
                'aula'       => 'C-101',
                'telefono'   => '2877654321',
                'email'      => 'L23350002@tuxtepec.tecnm.mx',
                'password'   => Hash::make('23350002'),
                'must_change_password' => true,
                'last_login_at' => null,
                'status'     => 'active',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'numcontrol' => '23350003',
                'nombre'     => 'FELIX FERNANDO GALVAN CASTILLO',
                'semestre'   => 1,
                'carrera_code' => 'IEOK', // Ing. Electrónica
                'grupo'      => 'A',
                'turno'      => 'MATUTINO',
                'aula'       => 'E-105',
                'telefono'   => '2871112222',
                'email'      => 'L23350003@tuxtepec.tecnm.mx',
                'password'   => Hash::make('23350003'),
                'must_change_password' => true,
                'last_login_at' => null,
                'status'     => 'active',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'numcontrol' => '23350004',
                'nombre'     => 'KEILA SANTIAGO FRANCISCO',
                'semestre'   => 3,
                'carrera_code' => 'IEMOK', // Ing. Electromecánica
                'grupo'      => 'C',
                'turno'      => 'VESPERTINO',
                'aula'       => 'M-201',
                'telefono'   => '2873334444',
                'email'      => 'L23350004@tuxtepec.tecnm.mx',
                'password'   => Hash::make('23350004'),
                'must_change_password' => true,
                'last_login_at' => null,
                'status'     => 'active',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'numcontrol' => '23350005',
                'nombre'     => 'GABRIELA GUZMAN PRATS',
                'semestre'   => 2,
                'carrera_code' => 'IIOK', // (Ing. Informática / Industrial según catálogo)
                'grupo'      => 'A',
                'turno'      => 'MATUTINO',
                'aula'       => 'D-202',
                'telefono'   => '2875556666',
                'email'      => 'L23350005@tuxtepec.tecnm.mx',
                'password'   => Hash::make('23350005'),
                'must_change_password' => true,
                'last_login_at' => null,
                'status'     => 'active',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'numcontrol' => '23350006',
                'nombre'     => 'LUZ EUGENIA AGUILAR BRAVO',
                'semestre'   => 3,
                'carrera_code' => 'IGEOK', // Ing. Gestión Empresarial
                'grupo'      => 'B1',
                'turno'      => 'MATUTINO',
                'aula'       => 'G-103',
                'telefono'   => '2877778888',
                'email'      => 'L23350006@tuxtepec.tecnm.mx',
                'password'   => Hash::make('23350006'),
                'must_change_password' => true,
                'last_login_at' => null,
                'status'     => 'active',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'numcontrol' => '23350007',
                'nombre'     => 'DANNA PAOLA SALGADO LICONA',
                'semestre'   => 2,
                'carrera_code' => 'ESPOK', // (Sistemas Computacionales?)
                'grupo'      => 'A',
                'turno'      => 'VESPERTINO',
                'aula'       => 'SC-01',
                'telefono'   => '2879990000',
                'email'      => 'L23350007@tuxtepec.tecnm.mx',
                'password'   => Hash::make('23350007'),
                'must_change_password' => true,
                'last_login_at' => null,
                'status'     => 'active',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'numcontrol' => '23350008',
                'nombre'     => 'NADIA ACEVEDO PERDOMO',
                'semestre'   => 1,
                'carrera_code' => 'CPOK', // Contador Público
                'grupo'      => 'A',
                'turno'      => 'MATUTINO',
                'aula'       => 'CP-02',
                'telefono'   => '2872221111',
                'email'      => 'L23350008@tuxtepec.tecnm.mx',
                'password'   => Hash::make('23350008'),
                'must_change_password' => true,
                'last_login_at' => null,
                'status'     => 'active',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'numcontrol' => '23350009',
                'nombre'     => 'GAELA AGUILAR CRUZ',
                'semestre'   => 5,
                'carrera_code' => 'IDAOK', // Desarrollo de Aplicaciones
                'grupo'      => 'C2',
                'turno'      => 'MATUTINO',
                'aula'       => 'DA-03',
                'telefono'   => '2874443333',
                'email'      => 'L23350009@tuxtepec.tecnm.mx',
                'password'   => Hash::make('23350009'),
                'must_change_password' => true,
                'last_login_at' => null,
                'status'     => 'active',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'numcontrol' => '23350010',
                'nombre'     => 'ALAN EDUARDO MORALES ESPINOSA',
                'semestre'   => 4,
                'carrera_code' => 'ICOK', // Ing. Civil
                'grupo'      => 'B2',
                'turno'      => 'VESPERTINO',
                'aula'       => 'C-203',
                'telefono'   => '2876665555',
                'email'      => 'L23350010@tuxtepec.tecnm.mx',
                'password'   => Hash::make('23350010'),
                'must_change_password' => true,
                'last_login_at' => null,
                'status'     => 'active',
                'created_at' => $now, 'updated_at' => $now,
            ],
        ];

        DB::table('students')->insert($students);
    }
}
