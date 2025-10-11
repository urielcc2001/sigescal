<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SolicitudPdfController extends Controller
{
    public function download(Request $request, Solicitud $solicitud)
    {
        // Si quieres restringir: solo el dueño puede descargar
        if (auth()->check()) {
            abort_unless($solicitud->user_id === auth()->id(), 403);
        }

        // Carga todas las relaciones necesarias, incluidas las imágenes
        $solicitud->load([
            'usuario:id,name',
            'area:id,nombre',
            'documento:id,codigo,nombre,revision,fecha_autorizacion,area_id',
            'imagenesDice',
            'imagenesDebeDecir',
        ]);

        // DomPDF prefiere rutas absolutas en disco (no urls). 
        // Asumimos que los archivos están en disk 'public' -> storage/app/public
        $diceImgs = $solicitud->imagenesDice->map(function ($a) {
            return [
                'abs_path' => public_path('storage/'.$a->path), // ej: /var/www/.../public/storage/solicitudes/ID/cambio_dice/...
                'name'     => $a->original_name,
                'width'    => $a->width,
                'height'   => $a->height,
            ];
        })->all();

        $debeImgs = $solicitud->imagenesDebeDecir->map(function ($a) {
            return [
                'abs_path' => public_path('storage/'.$a->path),
                'name'     => $a->original_name,
                'width'    => $a->width,
                'height'   => $a->height,
            ];
        })->all();

        $pdf = Pdf::loadView('pdf.solicitud-formato', [
            'solicitud' => $solicitud,
            'diceImgs'  => $diceImgs,
            'debeImgs'  => $debeImgs,
        ])->setPaper('letter', 'portrait');

        $filename = 'Solicitud_'.$solicitud->folio.'.pdf';
        return $pdf->stream($filename); // o ->download($filename)
    }
}
