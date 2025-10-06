<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Area;
use App\Models\ListaMaestra;
use Carbon\Carbon;

class ListaMaestraSeeder extends Seeder
{
    public function run(): void
    {
        // Asegúrate de haber corrido primero AreasTableSeeder
        $areasByCode = Area::pluck('id', 'codigo')->map(fn($id) => (int) $id)->toArray();

           $rows = [
                ['nombre' => 'Manual del Sistema de Gestión de la Calidad', 'codigo' => 'ITTUX-CA-MC-001', 'revision' => 30, 'fecha' => '14-marzo-2025'],
                ['nombre' => 'Formato para el Contexto de la Organización', 'codigo' => 'ITTUX-CA-MC-001-01', 'revision' => 2, 'fecha' => '14-julio-2021'],
                ['nombre' => 'Formato para la identificación, análisis y atención de las partes interesadas', 'codigo' => 'ITTUX-CA-MC-001-02', 'revision' => 3, 'fecha' => '14-julio-2021'],
                ['nombre' => 'Formato para el plan Rector de la Calidad', 'codigo' => 'ITTUX-CA-MC-001-03', 'revision' => 13, 'fecha' => '22-marzo-2022'],
                ['nombre' => 'Formato para la Ficha de descripción de proceso', 'codigo' => 'ITTUX-CA-MC-001-04', 'revision' => 1, 'fecha' => '16-julio-2021'],
                ['nombre' => 'Formato para el Plan de Calidad del Servicio Educativo', 'codigo' => 'ITTUX-CA-MC-001-05', 'revision' => 6, 'fecha' => '28-enero-2020'],
                ['nombre' => 'Procedimiento para el control de la información documentada', 'codigo' => 'ITTUX-CA-PG -001', 'revision' => 2, 'fecha' => '02-mayo -2018'],
                ['nombre' => 'Formato de Solicitud para la creación y actualización de la información documentada', 'codigo' => 'ITTUX-CA-PG-001-01', 'revision' => 0, 'fecha' => '02-mayo-2018'],
                ['nombre' => 'Formato para la Lista Maestra de Documentos Internos Controlados', 'codigo' => 'ITTUX-CA-PG-001-02', 'revision' => 0, 'fecha' => '23-marzo-2018'],
                ['nombre' => 'Formato para la Lista Maestra de Documentos de Origen Externo.', 'codigo' => 'ITTUX-CA-PG-001-03', 'revision' => 0, 'fecha' => '23-marzo-2018'],
                ['nombre' => 'Formato para la Lista Maestra para el Control de Registros de Calidad', 'codigo' => 'ITTUX-CA-PG-001-04', 'revision' => 0, 'fecha' => '23-marzo-2018'],
                ['nombre' => 'Formato para el Control de Instalación de Documentos Electrónicos.', 'codigo' => 'ITTUX-CA-PG-001-05', 'revision' => 2, 'fecha' => '23-marzo-2018'],
                ['nombre' => 'Instructivo de Trabajo para elaborar documentos', 'codigo' => 'ITTUX-CA-IT-01', 'revision' => 2, 'fecha' => '23-marzo-2018'],
                ['nombre' => 'Instructivo de Trabajo parala Gestión de Riesgos', 'codigo' => 'ITTUX-CA-IT-02', 'revision' => 3, 'fecha' => '23-agosto-2022'],
                ['nombre' => 'Formato de Matriz Institucional de Riesgos y Oportunidades', 'codigo' => 'ITTUX-CA-IT-002-01', 'revision' => 4, 'fecha' => '23-agosto-2022'],
                ['nombre' => 'Procedimiento para Auditoría Interna.', 'codigo' => 'ITTUX-CA-PG-003', 'revision' => 2, 'fecha' => '16-marzo-2018'],
                ['nombre' => 'Formato para el Programa de Auditoría', 'codigo' => 'ITTUX-CA-PG-003-01', 'revision' => 0, 'fecha' => '16-marzo-2018'],
                ['nombre' => 'Formato para Plan de Auditoría.', 'codigo' => 'ITTUX-CA-PG-003-02', 'revision' => 2, 'fecha' => '23-marzo-2018'],
                ['nombre' => 'Formato para Reunión de Apertura.', 'codigo' => 'ITTUX-CA-PG-003-03', 'revision' => 2, 'fecha' => '16-marzo-2018'],
                ['nombre' => 'Formato para Informe de Auditoría.', 'codigo' => 'ITTUX-CA-PG-003-04', 'revision' => 2, 'fecha' => '16-marzo-2018'],
                ['nombre' => 'Formato para Reunión de Cierre.', 'codigo' => 'ITTUX-CA-PG-003-05', 'revision' => 2, 'fecha' => '16-marzo-2018'],
                ['nombre' => 'Formato para la Evaluación de Auditores', 'codigo' => 'ITTUX-CA-PG-003-06', 'revision' => 2, 'fecha' => '16-marzo-2018'],
                ['nombre' => 'Criterios para Calificación de Auditores.', 'codigo' => 'ITTUX-CA-RC-004', 'revision' => 2, 'fecha' => '16-marzo-2018'],
                ['nombre' => 'Procedimiento para el Control de Salidas No Conforme.', 'codigo' => 'ITTUX-CA-PG-004', 'revision' => 2, 'fecha' => '23-marzo-2018'],
                ['nombre' => 'Formato para Identificación, Registro y Control de las Salidas No Conforme.', 'codigo' => 'ITTUX-CA-PG-004-01', 'revision' => 2, 'fecha' => '23-marzo-2018'],
                ['nombre' => 'Procedimiento para la Gestión de la Mejora', 'codigo' => 'ITTUX-CA-PG-005', 'revision' => 0, 'fecha' => '19-abril-2018'],
                ['nombre' => 'Formato para Requisición de Acciones Correctivas y/o Corrección', 'codigo' => 'ITTUX-CA-PG-005-01', 'revision' => 3, 'fecha' => '09-septiembre-2019'],
                ['nombre' => 'Formato para Plan de Oportunidades de Mejora', 'codigo' => 'ITTUX-CA-PG-005-02', 'revision' => 0, 'fecha' => '23-marzo-2018'],
                ['nombre' => 'Formato para Proyecto de Mejora', 'codigo' => 'ITTUX-CA-PG-005-03', 'revision' => 0, 'fecha' => '19-abril-2018'],
                ['nombre' => 'Procedimiento para la Atención de Quejas o Sugerencias.', 'codigo' => 'ITTUX-CA-PO-001', 'revision' => 2, 'fecha' => '21-febrero-2018'],
                ['nombre' => 'Formato para Quejas y /o Sugerencias.', 'codigo' => 'ITTUX-CA-PO-001-01', 'revision' => 2, 'fecha' => '21-febrero-2018'],
                ['nombre' => 'Procedimiento para la Evaluación de los Servicios al cliente', 'codigo' => 'ITTUX-CA-PO-002', 'revision' => 2, 'fecha' => '23-marzo-2018'],
                ['nombre' => 'Formato para programa anual de Encuestas de Servicios', 'codigo' => 'ITTUX-CA-PO-002-01', 'revision' => 2, 'fecha' => '23-marzo-2018'],
                ['nombre' => 'Formato para Encuesta de Servicio.', 'codigo' => 'ITTUX-CA-PO-002-02', 'revision' => 2, 'fecha' => '23-marzo-2018'],
                ['nombre' => 'Formato para el Informe de Resultados de la Encuesta de Servicio.', 'codigo' => 'ITTUX-CA-PO-002-03', 'revision' => 2, 'fecha' => '23-marzo-2018'],
                ['nombre' => 'Procedimiento para la Evaluación Docente por el estudiante', 'codigo' => 'ITTUX-CA-PO-005', 'revision' => 2, 'fecha' => '23-marzo-2018'],
                ['nombre' => 'Formato para el Concentrado de la Retroalimentación del cliente', 'codigo' => 'ITTUX-CA-PO-005-01', 'revision' => 2, 'fecha' => '23-marzo-2018'],
                ['nombre' => 'Instructivo de Trabajo para la Realización de la Revisión por la Dirección.', 'codigo' => 'ITTUX-CA-IT-03', 'revision' => 2, 'fecha' => '30-abril-2018'],
                ['nombre' => 'Formato electrónico para el Seguimiento de los Resultados de la Revisión por la Dirección.', 'codigo' => 'ITTUX-CA-FE-01', 'revision' => 2, 'fecha' => '30-abril-2018'],
                ['nombre' => 'Formato electrónico para el Contexto de la Organización', 'codigo' => 'ITTUX-CA-FE-02', 'revision' => 1, 'fecha' => '18-marzo-2022'],
                ['nombre' => 'Formato Electrónico para la Satisfacción del Cliente.', 'codigo' => 'ITTUX-CA-FE-03', 'revision' => 2, 'fecha' => '30-abril-2018'],
                ['nombre' => 'Formato Electrónico para el seguimientos y evaluación de las partes interesadas', 'codigo' => 'ITTUX-CA-FE-04', 'revision' => 1, 'fecha' => '28-junio-2018'],
                ['nombre' => 'Formato Electrónico para la Revisión de los objetivos de Calidad', 'codigo' => 'ITTUX-CA-FE-05', 'revision' => 2, 'fecha' => '30-abril-2018'],
                ['nombre' => 'Formato Electrónico para la Conformidad con el Aprendizaje y Salidas no Conformes', 'codigo' => 'ITTUX-CA-FE-06', 'revision' => 2, 'fecha' => '30-abril-2018'],
                ['nombre' => 'Formato electrónico para Acciones Correctiva.', 'codigo' => 'ITTUX-CA-FE-07', 'revision' => 2, 'fecha' => '30-abril-2018'],
                ['nombre' => 'Formato electrónico para el Seguimiento de Indicadores del Plan Rector', 'codigo' => 'ITTUX-CA-FE-08', 'revision' => 1, 'fecha' => '30-abril-2018'],
                ['nombre' => 'Formato electrónico para Auditoria de Calidad.', 'codigo' => 'ITTUX-CA-FE-09', 'revision' => 2, 'fecha' => '30-abril-2018'],
                ['nombre' => 'Formato Electrónico para el Seguimiento de Proveedores', 'codigo' => 'ITTUX-CA-FE-10', 'revision' => 0, 'fecha' => '30-abril-2018'],
                ['nombre' => 'Formato electrónico para la Provisión de los Recursos', 'codigo' => 'ITTUX-CA-FE-11', 'revision' => 0, 'fecha' => '30-abril-2018'],
                ['nombre' => 'Formato Electrónico para la Gestión de Riesgos y Oportunidades', 'codigo' => 'ITTUX-CA-FE-12', 'revision' => 1, 'fecha' => '18-marzo-2022'],
                ['nombre' => 'Formato Electrónico para la Mejora', 'codigo' => 'ITTUX-CA-FE-13', 'revision' => 0, 'fecha' => '30-abril-2018'],
                ['nombre' => 'Formato electrónico para Resultados de la Revisión por la Dirección', 'codigo' => 'ITTUX-CA-FE-14', 'revision' => 2, 'fecha' => '30-abril-2018'],
                ['nombre' => 'Formato Electrónico para captura de Resultados del Informe para Determinación y Gestión del Ambiente de Trabajo.', 'codigo' => 'ITTUX-CA-FE-14-01', 'revision' => 2, 'fecha' => '30-abril-2018'],
                ['nombre' => 'Instructivo para la Evaluación Departamental y Autoevaluación del Docente', 'codigo' => 'ITTUX-AD-IT-02', 'revision' => 1, 'fecha' => '27-noviembre-2020'],
                ['nombre' => 'Procedimiento para el Mantenimiento preventivo y/o correctivo de la Infraestructura y equipo.', 'codigo' => 'ITTUX-AD-PO-001', 'revision' => 4, 'fecha' => '16-enero-2023'],
                ['nombre' => 'Formato para la Lista de Verificación de infraestructura y equipo', 'codigo' => 'ITTUX-AD-PO-001-01', 'revision' => 3, 'fecha' => '2-mayo-2018'],
                ['nombre' => 'Formato para Solicitud de Mantenimiento Correctivo', 'codigo' => 'ITTUX-AD-PO-001-02', 'revision' => 3, 'fecha' => '2-mayo-2018'],
                ['nombre' => 'Formato para Programa de Mantenimiento Preventivo', 'codigo' => 'ITTUX-AD-PO-001-03', 'revision' => 2, 'fecha' => '2- mayo-2018'],
                ['nombre' => 'Formato para Orden de Trabajo de Mantenimiento', 'codigo' => 'ITTUX-AD-PO-001-04', 'revision' => 3, 'fecha' => '2-mayo-2018'],
                ['nombre' => 'Procedimiento para la Captación de Ingresos Propios.', 'codigo' => 'ITTUX-AD-PO-002', 'revision' => 3, 'fecha' => '9-abril-2018'],
                ['nombre' => 'Procedimiento para el Reclutamiento y Selección de Personal.', 'codigo' => 'ITTUX-AD-PO-003', 'revision' => 2, 'fecha' => '2-mayo-2018'],
                ['nombre' => 'Procedimiento para Determinar y Gestionar Ambiente de Trabajo.', 'codigo' => 'ITTUX-AD-PO-007', 'revision' => 2, 'fecha' => '2-mayo-2018'],
                ['nombre' => 'Formato de Encuesta para Determinar el Ambiente de Trabajo', 'codigo' => 'ITTUX-AD-PO-007-01', 'revision' => 2, 'fecha' => '2-mayo-2018'],
                ['nombre' => 'Instructivo de Trabajo para la realización de compras Directas', 'codigo' => 'ITTUX-AD-IT-01', 'revision' => 8, 'fecha' => '24-mayo-2024'],
                ['nombre' => 'Formato para la Evaluación y selección de proveedores', 'codigo' => 'ITTUX-AD-FO-01', 'revision' => 4, 'fecha' => '5-agosto-2022'],
                ['nombre' => 'Formato para la requisición de Bienes y Servicios', 'codigo' => 'ITTUX-AD-FO-02', 'revision' => 3, 'fecha' => '2-mayo-2018'],
                ['nombre' => 'Formato para la Orden de Compra del bien o servicio', 'codigo' => 'ITTUX-AD-FO-03', 'revision' => 3, 'fecha' => '2-mayo-2018'],
                ['nombre' => 'Formato para las Entrada y Salida simultanea de almacén', 'codigo' => 'ITTUX-AD-FO-04', 'revision' => 1, 'fecha' => '2-mayo-2018'],
                ['nombre' => 'Procedimiento para la Formación Docente y Actualización Profesional', 'codigo' => 'ITTUX-AD-PO-009', 'revision' => 8, 'fecha' => '12-junio-2023'],
                ['nombre' => 'Formato para el Diagnostico y Concentrado de Necesidades de Formación y Actualización Docente y Profesional.', 'codigo' => 'ITTUX-AD-PO-009-01', 'revision' => 4, 'fecha' => '12-junio-2023'],
                ['nombre' => 'Formato para Programa Institucional de Formación Docente y Actualización Profesional', 'codigo' => 'ITTUX-AD-PO-009-02', 'revision' => 4, 'fecha' => '12-junio-2023'],
                ['nombre' => 'Criterios para Seleccionar Instructor', 'codigo' => 'ITTUX-AD-PO-009-03', 'revision' => 0, 'fecha' => '12-junio-2023'],
                ['nombre' => 'Lista de asistencia', 'codigo' => 'ITTUX-AD-PO-009-04', 'revision' => 4, 'fecha' => '12-junio-2023'],
                ['nombre' => 'Cedula de inscripción', 'codigo' => 'ITTUX-AD-PO-009-05', 'revision' => 0, 'fecha' => '12-junio-2023'],
                ['nombre' => 'Encuesta de opinión', 'codigo' => 'ITTUX-AD-PO-009-06', 'revision' => 0, 'fecha' => '12-junio-2023'],
                ['nombre' => 'Formato de constancia', 'codigo' => 'ITTUX-AD-PO-009-07', 'revision' => 0, 'fecha' => '12-junio-2023'],
                ['nombre' => 'Encuesta de eficacia de capacitación docente', 'codigo' => 'ITTUX-AD-PO-009-08', 'revision' => 3, 'fecha' => '12-junio-2023'],
                ['nombre' => 'Procedimiento para la inscripción de estudiantes', 'codigo' => 'ITTUX-IR-PO-001', 'revision' => 4, 'fecha' => '19-febrero-2018'],
                ['nombre' => 'Registro electrónico de la solicitud de ficha para examen de selección.', 'codigo' => 'N/A', 'revision' => 1, 'fecha' => '19-febrero-2018'],
                ['nombre' => 'Formato de lista de aspirantes aceptados', 'codigo' => 'ITTUX-IR-PO-001-01', 'revision' => 2, 'fecha' => '19-febrero-2018'],
                ['nombre' => 'Formato de solicitud de inscripción.', 'codigo' => 'ITTUX-IR-PO-001-02', 'revision' => 2, 'fecha' => '19-febrero-2018'],
                ['nombre' => 'Formato de carta compromiso', 'codigo' => 'ITTUX-IR-PO-001-03', 'revision' => 3, 'fecha' => '15-marzo-2023'],
                ['nombre' => 'Formato de carga académica', 'codigo' => 'ITTUX-IR-PO-001-04', 'revision' => 2, 'fecha' => '19-febrero-2018'],
                ['nombre' => 'Formato de Autorización de consulta de expediente y entrega de documentos oficiales', 'codigo' => 'ITTUX-IR-PO-001-05', 'revision' => 0, 'fecha' => '19-febrero-2018'],
                ['nombre' => 'Formato de credencial.', 'codigo' => 'N/A', 'revision' => 1, 'fecha' => '18-septiembre-2009'],
                ['nombre' => 'Formato de Recibo de cobro', 'codigo' => 'N/A', 'revision' => 'N/A', 'fecha' => null],
                ['nombre' => 'Formato de libro de registro de números de control.', 'codigo' => 'N/A', 'revision' => 1, 'fecha' => '18-septiembre-2009'],
                ['nombre' => 'Procedimiento para la Reinscripción de Estudiantes.', 'codigo' => 'ITTUX-IR-PO-002', 'revision' => 3, 'fecha' => '10-abril-2018'],
                ['nombre' => 'Procedimiento para la Gestión del Curso.', 'codigo' => 'ITTUX-AC-PO-004', 'revision' => 4, 'fecha' => '7-agosto-2018'],
                ['nombre' => 'Formato para la Instrumentación Didáctica y Avance Programático', 'codigo' => 'ITTUX-AC-PO-004-01', 'revision' => 2, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato para el Reporte Final del Semestre.', 'codigo' => 'ITTUX-AC-PO-004-02', 'revision' => 2, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato para el reporte de proyectos individuales del docente / programa de trabajo académico en horas de apoyo a la docencia', 'codigo' => 'ITTUX-AC-PO-004-03', 'revision' => 2, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato para liberación de Actividades frente a grupo', 'codigo' => 'ITTUX-AC-PO-004-04', 'revision' => 3, 'fecha' => '7-agosto-2018'],
                ['nombre' => 'Formato de solicitud de corrección de calificaciones.', 'codigo' => 'ITTUX-AC-PO-004-05', 'revision' => 2, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato de horario de Trabajo.', 'codigo' => 'ITTUX-AC-PO-004-06', 'revision' => 3, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Procedimiento para la Evaluación Docente por el estudiante', 'codigo' => 'ITTUX-CA-PO-005-02', 'revision' => 2, 'fecha' => '23-marzo-2018'],
                ['nombre' => 'Procedimiento para la Operación y Acreditación de las Residencias Profesionales.', 'codigo' => 'ITTUX-AC-PO-007', 'revision' => 4, 'fecha' => '14-agosto-2019'],
                ['nombre' => 'Formato para Carta de Presentación del estudiante', 'codigo' => 'ITTUX-AC-PO-007-01', 'revision' => 3, 'fecha' => '14-agosto-2019'],
                ['nombre' => 'Formato para la Asignación de Asesor Interno de Residencias Profesionales.', 'codigo' => 'ITTUX-AC-PO-007-02', 'revision' => 2, 'fecha' => '2-mayo-2018'],
                ['nombre' => 'Formato de Registro de Asesoría', 'codigo' => 'ITTUX-AC-PO-007-03', 'revision' => 2, 'fecha' => '2-mayo-2018'],
                ['nombre' => 'Formato de Evaluación del Residente', 'codigo' => 'ITTUX-AC-PO-007-04', 'revision' => 4, 'fecha' => '14-agosto-2019'],
                ['nombre' => 'Formato para Asignación de Revisor de Residencias Profesionales.', 'codigo' => 'ITTUX-AC-PO-007-05', 'revision' => 2, 'fecha' => '2-mayo-2018'],
                ['nombre' => 'Formato para Carta de Agradecimiento', 'codigo' => 'ITTUX-AC-PO-007-06', 'revision' => 2, 'fecha' => '2-mayo-2018'],
                ['nombre' => 'Procedimiento para la Titulación', 'codigo' => 'ITTUX-EG-PO-001', 'revision' => 4, 'fecha' => '07-agosto-2018'],
                ['nombre' => 'Formato de Registro de proyecto', 'codigo' => 'ITTUX-EG-PO-001-01', 'revision' => 2, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato de Solicitud del Estudiante.', 'codigo' => 'ITTUX-EG-PO-001-02', 'revision' => 2, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato de Aviso de Inicio de Trámites de Titulación.', 'codigo' => 'ITTUX-EG-PO-001-03', 'revision' => 2, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato de Asignación de Asesor y Revisores.', 'codigo' => 'ITTUX-EG-PO-001-04', 'revision' => 2, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato de Liberación del Proyecto para la Titulación.', 'codigo' => 'ITTUX-EG-PO-001-05', 'revision' => 2, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato de Autorización de Presentación del Trabajo Profesional.', 'codigo' => 'ITTUX-EG-PO-001-06', 'revision' => 2, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato de Carta de No Inconveniencia para la Titulación.', 'codigo' => 'ITTUX-EG-PO-001-07', 'revision' => 3, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato de Notificación de Programación del Acto de Recepción Profesional.', 'codigo' => 'ITTUX-EG-PO-001-08', 'revision' => 2, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato de Comisión para Sinodales.', 'codigo' => 'ITTUX-EG-PO-001-09', 'revision' => 2, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Acta de Examen Profesional', 'codigo' => 'N/A', 'revision' => 'N/A', 'fecha' => '18-septiembre-2009'],
                ['nombre' => 'Constancia de Exención de Examen Profesional', 'codigo' => 'N/A', 'revision' => 'N/A', 'fecha' => '18-septiembre-2009'],
                ['nombre' => 'Procedimiento para el cumplimiento de actividades complementarias', 'codigo' => 'ITTUX-AC-PO-008', 'revision' => 0, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato de Tabla de Actividades Complementarias', 'codigo' => 'ITTUX-AC-PO-008-01', 'revision' => 0, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato de Solicitud de Actividad complementaria', 'codigo' => 'ITTUX-AC-PO-008-02', 'revision' => 0, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato de Evaluación al Desempeño de la Actividad complementaria', 'codigo' => 'ITTUX-AC-PO-008-03', 'revision' => 0, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato de Constancia de Cumplimiento de Actividades complementarias', 'codigo' => 'ITTUX-AC-PO-008-04', 'revision' => 0, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Formato de Constancia de Liberación de Actividades complementarias', 'codigo' => 'ITTUX-AC-PO-008-05', 'revision' => 0, 'fecha' => '26-abril-2018'],
                ['nombre' => 'Procedimiento para Visitas a Empresas.', 'codigo' => 'ITTUX-VI-PO-001', 'revision' => 2, 'fecha' => '02-mayo-2018'],
                ['nombre' => 'Formato para Solicitud de Visitas a Empresas.', 'codigo' => 'ITTUX-VI-PO-001-01', 'revision' => 3, 'fecha' => '25-mayo-2022'],
                ['nombre' => 'Formato para Oficio de Solicitud de Visitas a Empresas.', 'codigo' => 'ITTUX-VI-PO-001-02', 'revision' => 2, 'fecha' => '02-mayo-2018'],
                ['nombre' => 'Formato para Reporte de Visitas Realizadas a Empresas', 'codigo' => 'ITTUX-VI-PO-001-03', 'revision' => 2, 'fecha' => '02-mayo-2018'],
                ['nombre' => 'Formato para Carta de Presentación y Agradecimiento de Visitas a Empresas.', 'codigo' => 'ITTUX-VI-PO-001-04', 'revision' => 2, 'fecha' => '02-mayo-2018'],
                ['nombre' => 'Formato para Reporte de Resultados de Incidentes en Visita.', 'codigo' => 'ITTUX-VI-PO-001-05', 'revision' => 2, 'fecha' => '02-mayo-2018'],
                ['nombre' => 'Formato para la Evaluación del cumplimiento de objetivos de la visita a empresas.', 'codigo' => 'ITTUX-VI-PO-001-06', 'revision' => 3, 'fecha' => '25-mayo-2022'],
                ['nombre' => 'Formato para la Lista Autorizada de Estudiantes que asistirán a la Visita.', 'codigo' => 'ITTUX-VI-PO-001-07', 'revision' => 2, 'fecha' => '02-mayo-2018'],
                ['nombre' => 'Procedimiento para el Servicio Social.', 'codigo' => 'ITTUX-VI-PO-002', 'revision' => 4, 'fecha' => '02-mayo-2018'],
                ['nombre' => 'Formato para Solicitud de Servicio Social.', 'codigo' => 'ITTUX-VI-PO-002-01', 'revision' => 2, 'fecha' => '02-mayo-2018'],
                ['nombre' => 'Formato para Carta Compromiso del Servicio Social.', 'codigo' => 'ITTUX-VI-PO-002-02', 'revision' => 2, 'fecha' => '02-mayo-2018'],
                ['nombre' => 'Formato para Carta de Presentación de Servicio Social.', 'codigo' => 'ITTUX-VI-PO-002-03', 'revision' => 2, 'fecha' => '02-mayo-2018'],
                ['nombre' => 'Formato para Reporte Bimestral de Servicio Social.', 'codigo' => 'ITTUX-VI-PO-002-04', 'revision' => 2, 'fecha' => '02-mayo-2018'],
                ['nombre' => 'Formato para Constancia de Terminación de Servicio Social.', 'codigo' => 'ITTUX-VI-PO-002-05', 'revision' => 2, 'fecha' => '02-mayo-2018'],
                ['nombre' => 'Formato de Autoevaluación Cualitativa del Prestador de Servicio Social', 'codigo' => 'ITTUX-VI-PO-002-07', 'revision' => 1, 'fecha' => '15-noviembre-2019'],
                ['nombre' => 'Formato de Evaluación Cualitativa del Prestador de Servicio Social por el responsable de programa', 'codigo' => 'ITTUX-VI-PO-002-08', 'revision' => 1, 'fecha' => '15-noviembre-2019'],
                ['nombre' => 'Formato de Evaluación de las Actividades por el Prestador de Servicio. Social', 'codigo' => 'ITTUX-VI-PO-002-09', 'revision' => 1, 'fecha' => '15-noviembre-2019'],
                ['nombre' => 'Instructivo de trabajo para la captura del programa de trabajo anual y presupuesto anual', 'codigo' => 'ITTUX-PL-IT-001', 'revision' => 1, 'fecha' => '16-enero-2023'],
                ['nombre' => 'Formato para el Calendario Escolar.', 'codigo' => 'ITTUX-PL-IT-001-01', 'revision' => 0, 'fecha' => '28-abril-2018'],
                ['nombre' => 'Instructivo de trabajo para las Actividades Extraescolares', 'codigo' => 'ITTUX-VI-IT-001', 'revision' => 0, 'fecha' => '18-abril-2018'],
                ['nombre' => 'Formato para el Programa de actividades extraescolares', 'codigo' => 'ITTUX-VI-IT-001-01', 'revision' => 0, 'fecha' => '18-abril-2018'],
                ['nombre' => 'Formato para el calendario semestral de Actividades extraescolares', 'codigo' => 'ITTUX-VI-IT-001-02', 'revision' => 0, 'fecha' => '18-abril-2018'],
                ['nombre' => 'Formato para el registro de participantes de actividades extraescolares', 'codigo' => 'ITTUX-VI-IT-001-03', 'revision' => 2, 'fecha' => '18-abril-2018'],
                ['nombre' => 'Formato para el informe de Actividad extraescolares', 'codigo' => 'ITTUX-VI-IT-001-04', 'revision' => 2, 'fecha' => '18-abril-2018'],
            ];


        foreach ($rows as $r) {
            $codigo = $this->normalizeCode($r['codigo']);

            // 🟡 Evitar problemas con valores vacíos o repetidos "N/A"
            if (!$codigo || trim(strtoupper($codigo)) === 'N/A') {
                // Genera un código único para mantener el índice unique
                $codigo = 'NA-' . Str::slug(Str::limit($r['nombre'], 40), '-') . '-' . Str::random(3);
            }

            // Detectar área (CA, AD, IR, AC, VI, EG, PL)
            $areaCode = $this->extractAreaCode($codigo);
            $areaId = $areasByCode[$areaCode] ?? null;

            // Crear o actualizar el registro
            ListaMaestra::updateOrCreate(
                ['codigo' => $codigo],
                [
                    'nombre'             => $r['nombre'],
                    'revision'           => $this->toIntOrZero($r['revision']),
                    'fecha_autorizacion' => $this->parseFecha($r['fecha']),
                    'area_id'            => $areaId,
                ]
            );
        }


    }

    private function normalizeCode(?string $codigo): ?string
    {
        if (!$codigo) return null;
        // Limpia espacios múltiples o raros: e.g. "ITTUX-CA-PG -001" -> "ITTUX-CA-PG-001"
        $codigo = preg_replace('/\s+/', '', $codigo);
        // Homogeneiza mayúsculas
        return Str::upper($codigo);
    }

    private function extractAreaCode(?string $codigo): ?string
    {
        // Espera algo tipo "ITTUX-CA-PO-001" -> devuelve "CA"
        if (!$codigo) return null;
        $parts = explode('-', $codigo);
        // ITTUX-CA-...
        return $parts[1] ?? null;
    }

    private function toIntOrZero($value): int
    {
        return is_numeric($value) ? (int) $value : 0;
    }

    private function parseFecha(?string $textoFecha): ?string
    {
        if (!$textoFecha) return null;

        // Normaliza (quita dobles espacios, recorta)
        $t = trim(preg_replace('/\s+/', ' ', Str::lower($textoFecha)));

        // Casos N/A
        if ($t === 'n/a' || $t === 'n/a n/a' || $t === 'na') {
            return null;
        }

        // Mapa de meses (acepta con/sin acentos)
        $meses = [
            'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4, 'mayo' => 5, 'junio' => 6,
            'julio' => 7, 'agosto' => 8, 'septiembre' => 9, 'setiembre' => 9, 'octubre' => 10,
            'noviembre' => 11, 'diciembre' => 12,
        ];

        // Acepta formatos como: "02-mayo-2018", "2-mayo-2018", "14-marzo-2025", "23-agosto-2022"
        // También "30-abril-2018", "28-enero-2020"
        // Y con palabras compuestas tipo "16-enero-2023"
        $parts = explode('-', $t);
        if (count($parts) === 3) {
            [$dd, $mmTxt, $yyyy] = $parts;

            $dd = (int) preg_replace('/\D+/', '', $dd);
            $yyyy = (int) preg_replace('/\D+/', '', $yyyy);
            $mmTxt = Str::of($mmTxt)->replace(['á','é','í','ó','ú'], ['a','e','i','o','u'])->value();
            $mmTxt = trim($mmTxt);

            // Por si viene con palabras extras (e.g. "mayo " o "mayo ")
            $mmTxt = preg_replace('/[^a-z]/', '', $mmTxt);

            $mm = $meses[$mmTxt] ?? null;

            if ($dd && $mm && $yyyy) {
                try {
                    return Carbon::createFromDate($yyyy, $mm, $dd)->format('Y-m-d');
                } catch (\Throwable $e) {
                    return null;
                }
            }
        }

        // Si llega en otro formato inesperado, intenta parsear libre
        try {
            return Carbon::parse($t)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
