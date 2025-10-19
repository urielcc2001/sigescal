<?php

namespace App\Http\Controllers;

use App\Models\ListaMaestra;
use App\Models\Area;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

        // Si viene date desde el modal, úsela, si no, usa el max existente
        $dateParam = (string) $request->get('date', '');
        if ($dateParam !== '') {
            $fecha = Carbon::createFromFormat('Y-m-d', $dateParam);
        } else {
            $max = $docs->max('fecha_autorizacion') ?? now()->toDateString();
            $fecha = Carbon::parse($max);
        }

        $fechaActualizacion = mb_strtoupper($fecha->isoFormat('D [DE] MMMM [DE] YYYY'), 'UTF-8');

        $pdf = Pdf::loadView('pdf.lista-maestra', [
                'docs' => $docs,
                'fechaActualizacion' => $fechaActualizacion,
                // metadatos del formato
                'formTitle' => 'Formato para la Lista Maestra de Documentos Internos Controlados',
                'formCode'  => 'ITTUX-CA-PG-001-02',
                'formRev'   => '0',
                'norma'     => 'ISO 9001:2015',
                'requisito' => '7.5.3',
            ])
            ->setOption('isPhpEnabled', true)
            ->setPaper('letter', 'portrait');

        $filename = 'Lista_Maestra.pdf';
        return $pdf->stream($filename); // o ->download($filename)
    }
}
