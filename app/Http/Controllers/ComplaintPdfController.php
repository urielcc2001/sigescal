<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\OrgPosition;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ComplaintPdfController extends Controller
{
    public function show(Complaint $complaint)
    {
        $complaint->load('student');

        $subdirNombre = $this->nombreVigentePorSlug($complaint->subdirector_slug)
            ?? 'SUBDIRECCIÓN (S/F)';

        $fecha_resp = optional($complaint->respondida_at ?? $complaint->created_at)
            ->format('d/m/Y');

        $pdf = Pdf::loadView('pdf.queja-formato', [
                'complaint' => $complaint,
                'ahora'     => now()->timezone(config('app.timezone')),
                'subdirNombre' => $subdirNombre,
                'fecha_resp'   => $fecha_resp,
            ])
            ->setPaper('letter')
            ->setOption('isPhpEnabled', true);

        return $pdf->stream("queja_{$complaint->id}.pdf");
    }

    public function download(Complaint $complaint)
    {
        $complaint->load('student');

        $subdirNombre = $this->nombreVigentePorSlug($complaint->subdirector_slug)
            ?? 'SUBDIRECCIÓN (S/F)';

        $fecha_resp = optional($complaint->respondida_at ?? $complaint->created_at)
            ->format('d/m/Y');

        $pdf = Pdf::loadView('pdf.queja-formato', [
                'complaint' => $complaint,
                'ahora'     => now()->timezone(config('app.timezone')),
                'subdirNombre' => $subdirNombre,
                'fecha_resp'   => $fecha_resp,
            ])
            ->setPaper('letter')
            ->setOption('isPhpEnabled', true);

        return $pdf->download("queja_{$complaint->id}.pdf");
    }

    public function publicDownload(Complaint $complaint)
    {
        // Cargar relaciones necesarias
        $complaint->load('student');

        // Solo permitir si ya tiene respuesta y está respondida/cerrada
        if (!$complaint->respuesta || !in_array($complaint->estado, ['respondida', 'cerrada'])) {
            abort(403, 'El formato solo está disponible cuando la queja ha sido respondida.');
        }

        $subdirNombre = $this->nombreVigentePorSlug($complaint->subdirector_slug)
            ?? 'SUBDIRECCIÓN (S/F)';

        $fecha_resp = optional($complaint->respondida_at ?? $complaint->created_at)
            ->format('d/m/Y');

        $pdf = Pdf::loadView('pdf.queja-formato', [
                'complaint'          => $complaint,
                'ahora'              => now()->timezone(config('app.timezone')),
                'subdirNombre'       => $subdirNombre,
                'fecha_resp'         => $fecha_resp,
                'soloParteInferior'  => true,   
            ])
            ->setPaper('letter')
            ->setOption('isPhpEnabled', true);

        return $pdf->stream("queja_{$complaint->id}.pdf");
    }

    private function nombreVigentePorSlug(?string $slug): ?string
    {
        if (!$slug) return null;

        $pos = OrgPosition::with('vigente')->firstWhere('slug', $slug);

        // Primero el nombre de la persona, si existe; si no, el nombre del puesto
        return optional($pos?->vigente)->nombre ?? $pos?->nombre;
    }
}
