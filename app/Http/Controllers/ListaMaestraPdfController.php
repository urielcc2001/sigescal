<?php

namespace App\Http\Controllers;

use App\Models\ListaMaestra;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

use niklasravnsborg\LaravelPdf\Facades\Pdf as MPDF;

class ListaMaestraPdfController extends Controller
{
    public function download(Request $request)
    {
        $areaId = $request->integer('areaId');
        $search = (string) $request->get('search', '');
        $likeOp = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';
        $needle = '%'.$search.'%';

        $docs = ListaMaestra::select('id','codigo','nombre','revision','fecha_autorizacion','area_id')
            ->when($areaId, fn($q) => $q->where('area_id', $areaId))
            ->when($search !== '', fn($q) => $q->where(fn($qq) =>
                $qq->where('codigo', $likeOp, $needle)->orWhere('nombre', $likeOp, $needle)
            ))
            ->orderBy('id','asc')
            ->get();

        $dateParam = (string) $request->get('date', '');
        if ($dateParam !== '') {
            $fecha = Carbon::createFromFormat('Y-m-d', $dateParam);
        } else {
            $max = $docs->max('fecha_autorizacion') ?? now()->toDateString();
            $fecha = Carbon::parse($max);
        }
        $fechaActualizacion = mb_strtoupper($fecha->isoFormat('D [DE] MMMM [DE] YYYY'), 'UTF-8');

        $data = [
            'docs' => $docs,
            'fechaActualizacion' => $fechaActualizacion,
            'formTitle' => 'Formato para la Lista Maestra de Documentos Internos Controlados',
            'formCode'  => 'ITTUX-CA-PG-001-02',
            'formRev'   => '0',
            'norma'     => 'ISO 9001:2015',
            'requisito' => '7.5.3',
        ];

        $pdf = MPDF::loadView('pdf.lista-maestra', $data, [], [
            'format' => 'Letter',
            'orientation' => 'P',
            'tempDir' => storage_path('app/mpdf-temp'), // opcional para Sail

            // Aquí configuramos la instancia mPDF directamente
            'instanceConfigurator' => function (\Mpdf\Mpdf $mpdf) {
                // Denegar TODO: no imprimir, no copiar, no editar, etc.
                // Si quisieras permitir algo, p.ej. 'copy', pásalo en el array.
                $mpdf->SetProtection([], '', 'ITTUX-SGC-OWNER'); // owner password

                $mpdf->SetTitle('Lista Maestra');
                $mpdf->SetAuthor('ITTux SGC');
            },
        ]);

        return $pdf->download('Lista_Maestra.pdf'); // o ->stream('Lista_Maestra.pdf')
    }
}
