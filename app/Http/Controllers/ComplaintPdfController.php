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

        // Obtener nombre y cargo del subdirector
        [$subdirNombre, $subdirCargo] = $this->subdirInfoPorSlug($complaint->subdirector_slug);

        $fecha_resp = optional($complaint->respondida_at ?? $complaint->created_at)
            ->format('d/m/Y');

        $pdf = Pdf::loadView('pdf.queja-formato', [
                'complaint' => $complaint,
                'ahora'     => now()->timezone(config('app.timezone')),
                'subdirNombre' => $subdirNombre,
                'subdirCargo'  => $subdirCargo,
                'fecha_resp'   => $fecha_resp,
            ])
            ->setPaper('letter')
            ->setOption('isPhpEnabled', true);

        return $pdf->stream("queja_{$complaint->id}.pdf");
    }

    public function download(Complaint $complaint)
    {
        $complaint->load('student');

        [$subdirNombre, $subdirCargo] = $this->subdirInfoPorSlug($complaint->subdirector_slug);

        $fecha_resp = optional($complaint->respondida_at ?? $complaint->created_at)
            ->format('d/m/Y');

        $pdf = Pdf::loadView('pdf.queja-formato', [
                'complaint' => $complaint,
                'ahora'     => now()->timezone(config('app.timezone')),
                'subdirNombre' => $subdirNombre,
                'subdirCargo'  => $subdirCargo,
                'fecha_resp'   => $fecha_resp,
            ])
            ->setPaper('letter')
            ->setOption('isPhpEnabled', true);

        return $pdf->download("queja_{$complaint->id}.pdf");
    }

    public function publicDownload(Complaint $complaint)
    {
        $complaint->load('student');

        if (!$complaint->respuesta || !in_array($complaint->estado, ['respondida', 'cerrada'])) {
            abort(403, 'El formato solo está disponible cuando la queja ha sido respondida.');
        }

        [$subdirNombre, $subdirCargo] = $this->subdirInfoPorSlug($complaint->subdirector_slug);

        $fecha_resp = optional($complaint->respondida_at ?? $complaint->created_at)
            ->format('d/m/Y');

        $pdf = Pdf::loadView('pdf.queja-formato', [
                'complaint'          => $complaint,
                'ahora'              => now()->timezone(config('app.timezone')),
                'subdirNombre'       => $subdirNombre,
                'subdirCargo'        => $subdirCargo,
                'fecha_resp'         => $fecha_resp,
                'soloParteInferior'  => true,
            ])
            ->setPaper('letter')
            ->setOption('isPhpEnabled', true);

        return $pdf->stream("queja_{$complaint->id}.pdf");
    }

    //  Nuevo helper: nombre de persona + nombre del puesto
    private function subdirInfoPorSlug(?string $slug): array
    {
        if (!$slug) {
            return ['SUBDIRECCIÓN (S/F)', 'Subdirector del Área correspondiente'];
        }

        $pos = OrgPosition::with('vigente')->firstWhere('slug', $slug);

        // Nombre de la persona vigente (si hay), si no, texto genérico
        $nombrePersona = optional($pos?->vigente)->nombre ?? 'SUBDIRECCIÓN (S/F)';

        //  Aquí usamos el campo correcto del modelo: titulo
        $cargo = $pos?->titulo ?? 'Subdirector del Área correspondiente';

        return [$nombrePersona, $cargo];
    }

}
