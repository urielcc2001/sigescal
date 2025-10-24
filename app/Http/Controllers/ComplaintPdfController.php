<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ComplaintPdfController extends Controller
{
    public function show(Complaint $complaint)
    {
        $pdf = Pdf::loadView('pdf.queja-formato', [
                'complaint' => $complaint,
                'ahora'     => now()->timezone(config('app.timezone')),
            ])
            ->setPaper('letter')
            ->setOption('isPhpEnabled', true);  

        return $pdf->stream("queja_{$complaint->id}.pdf");
    }

    public function download(Complaint $complaint)
    {
        $pdf = Pdf::loadView('pdf.queja-formato', [
                'complaint' => $complaint,
                'ahora'     => now()->timezone(config('app.timezone')),
            ])
            ->setPaper('letter')
            ->setOption('isPhpEnabled', true); 

        return $pdf->download("queja_{$complaint->id}.pdf");
    }
}
