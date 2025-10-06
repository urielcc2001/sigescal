<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SolicitudPdfController extends Controller
{
    public function download(Request $request, Solicitud $solicitud)
    {
        if (auth()->check()) {
            abort_unless($solicitud->user_id === auth()->id(), 403);
        }

        $solicitud->load([
            'usuario:id,name',
            'area:id,nombre',
            'documento:id,codigo,nombre,revision,fecha_autorizacion,area_id',
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.solicitud-formato', [
            'solicitud' => $solicitud,
        ])->setPaper('letter', 'portrait');

        $filename = 'Solicitud_'.$solicitud->folio.'.pdf';
        // return $pdf->download($filename); // para descargar
        return $pdf->stream($filename);       // para ver en navegador
    }
}
