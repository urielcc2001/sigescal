<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\OrgPosition;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class SolicitudPdfController extends Controller
{
    public function download(Request $request, Solicitud $solicitud)
    {
        // Si quieres restringir: solo el dueño puede descargar 
        if (auth()->check()) {
            $user = auth()->user();

            abort_unless(
                $solicitud->user_id === $user->id || $user->hasRole('Super Admin'),
                403
            );
        }

        // Carga relaciones necesarias
        $solicitud->load([
            'usuario:id,name',
            'area:id,codigo,nombre',
            'documento:id,codigo,nombre,revision,fecha_autorizacion,area_id',
            'imagenesDice',
            'imagenesDebeDecir',
        ]);

        $ctrlNombre   = $this->nombreVigentePorSlug('ctrl-documentos') ?? 'CONTROLADOR DE DOCUMENTOS (S/F)';
        $coordNombre  = $this->nombreVigentePorSlug('coord-calidad')   ?? 'COORDINACIÓN DE CALIDAD (S/F)';

        // Subdirector según área (usa la relación ya cargada)
        $areaCodigo   = strtoupper(trim(optional($solicitud->area)->codigo ?? ''));
        $respSlug     = $solicitud->responsable_slug ?: $this->subdirectorSlugPorCodigo($areaCodigo);
        $subdirNombre = $this->nombreVigentePorSlug($respSlug) ?? 'SUBDIRECCIÓN (S/F)';

        // Imágenes: a rutas absolutas y existentes
        $diceImgs = $solicitud->imagenesDice->map(function ($a) {
            $abs = public_path('storage/'.$a->path);
            return file_exists($abs) ? [
                'abs_path' => $abs,
                'name'     => $a->original_name,
                'width'    => $a->width,
                'height'   => $a->height,
            ] : null;
        })->filter()->values()->all();

        $debeImgs = $solicitud->imagenesDebeDecir->map(function ($a) {
            $abs = public_path('storage/'.$a->path);
            return file_exists($abs) ? [
                'abs_path' => $abs,
                'name'     => $a->original_name,
                'width'    => $a->width,
                'height'   => $a->height,
            ] : null;
        })->filter()->values()->all();

        // Render PDF
        $pdf = Pdf::loadView('pdf.solicitud-formato', [
                'solicitud'     => $solicitud,
                'diceImgs'      => $diceImgs,
                'debeImgs'      => $debeImgs,
                'ctrlNombre'    => $ctrlNombre,
                'coordNombre'   => $coordNombre,
                'subdirNombre'  => $subdirNombre,
            ])
            ->setPaper('letter', 'portrait');

        $filename = 'Solicitud_'.$solicitud->folio.'.pdf';
        return $pdf->stream($filename); // o ->download($filename)
    }

    private function subdirectorSlugPorCodigo(?string $codigo): ?string
    {
        if (! $codigo) return null;

        $map = [
            'AC' => 'subdir-academica',
            'VI' => 'subdir-vinculacion',
            'PL' => 'subdir-vinculacion',
            'AD' => 'subdir-servicios',
            'IR' => 'subdir-vinculacion',
            'EG' => 'subdir-vinculacion',
            'CA' => 'subdir-vinculacion',
        ];

        return $map[$codigo] ?? null;
    }

    /**
     * Obtiene el nombre del titular vigente para un puesto (por slug).
     */
    private function nombreVigentePorSlug(?string $slug): ?string
    {
        if (!$slug) return null;
        $pos = OrgPosition::with('vigente')->firstWhere('slug', $slug);
        return optional($pos?->vigente)->nombre; // ya lo sembraste en MAYÚSCULAS
    }
}
