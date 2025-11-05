<?php

namespace App\Http\Controllers;

use App\Models\LmFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipStream\ZipStream;

class MasterZipController extends Controller
{
    // ZIP por carpeta (?path= "1.COORDINACION DE CALIDAD/AUDITORIA INTERNA")
    public function zip(Request $request)
    {
        $slugPath = $request->query('path');
        abort_unless($slugPath, 404);

        $folder = LmFolder::where('slug_path', $slugPath)
            ->with(['files','children'])
            ->firstOrFail();

        $zipName = $this->safeZipName($folder->slug_path); // garantiza .zip

        return response()->streamDownload(function () use ($folder) {
            // Importante: no enviar headers desde ZipStream (sendHttpHeaders=false)
            $zip = new ZipStream(outputName: null, sendHttpHeaders: false);
            $this->addFolderToZip($zip, $folder, '');
            $zip->finish();
        }, $zipName, [
            'Content-Type' => 'application/zip',
            // 'Cache-Control' => 'no-store', // opcional
        ]);
    }

    // ZIP con TODO el repositorio de la Lista Maestra
    public function zipAll()
    {
        $zipName = 'ListaMaestra_Completa.zip';

        return response()->streamDownload(function () {
            $zip = new ZipStream(outputName: null, sendHttpHeaders: false);

            LmFolder::whereNull('parent_id')
                ->with(['files','children'])
                ->chunk(50, function ($roots) use ($zip) {
                    foreach ($roots as $root) {
                        $this->addFolderToZip($zip, $root, '');
                    }
                });

            $zip->finish();
        }, $zipName, [
            'Content-Type' => 'application/zip',
        ]);
    }

    private function addFolderToZip(ZipStream $zip, LmFolder $folder, string $prefix): void
    {
        $pathPrefix = $prefix === '' ? $folder->name : $prefix.'/'.$folder->name;

        foreach ($folder->files as $f) {
            $stream = Storage::disk('local')->readStream($f->disk_path);
            if ($stream) {
                $zip->addFileFromStream($pathPrefix.'/'.$f->filename, $stream);
                fclose($stream);
            }
        }

        foreach ($folder->children as $child) {
            $child->loadMissing(['files','children']);
            $this->addFolderToZip($zip, $child, $pathPrefix);
        }
    }

    private function safeZipName(string $base): string
    {
        // Sanitiza y garantiza extensión .zip
        $name = preg_replace('/[^\w\-\.]+/u', '_', $base);
        $name = trim($name, '_');
        if ($name === '') {
            $name = 'ListaMaestra';
        }
        return Str::finish($name, '.zip');
    }
}
