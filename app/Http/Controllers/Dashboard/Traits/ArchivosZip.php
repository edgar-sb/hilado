<?php

namespace App\Http\Controllers\Dashboard\Traits;


use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

trait ArchivosZip {
    public function createFacturaZip($estatus_log) {
        $hoy = Carbon::now();
        $zipFileName = 'Factura_'.$hoy.'.zip';
        $files_xml = $estatus_log->getMedia('factura-xml');
        $files_pdf = $estatus_log->getMedia('factura-pdf');
        return $this->storeZip($zipFileName, [$files_xml, $files_pdf]);
    }

    public function createAcuseZip($estatus_log)
    {
        $hoy = Carbon::now();
        $zipFileName = 'Acuse_'.$hoy .'.zip';
        $files_acuse = $estatus_log->getMedia('acuse');
        $files_carta = $estatus_log->getMedia('carta');
        return $this->storeZip($zipFileName, [$files_acuse, $files_carta]);
    }

    public function createComplementoZip($estatus_log) {
        $hoy = Carbon::now();
        $zipFileName = 'Complemento_'.$hoy.'.zip';
        $files_xml = $estatus_log->getMedia('complemento-xml');
        $files_pdf = $estatus_log->getMedia('complemento-pdf');
        return $this->storeZip($zipFileName, [$files_xml, $files_pdf]);
    }

    private function storeZip($zipFileName, $collections) {
        $zip = new ZipArchive();
        if ($zip->open(storage_path('app/public/temp/').$zipFileName, ZipArchive::CREATE) === TRUE) {
            foreach ($collections as $collection) {
                foreach ($collection as $key => $archivo) {
                    $zip->addFile($archivo->getPath(), $archivo->file_name);
                }
            }
            $zip->close();
        }
        if(Storage::disk('temp')->exists($zipFileName)) {
            return $zipFileName;
        }
        return null;
    }

}
