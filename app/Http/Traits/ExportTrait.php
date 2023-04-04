<?php

namespace App\Http\Traits;
use App;
use Maatwebsite\Excel\Facades\Excel;

trait ExportTrait {
    
    public static function storeExport($class, $filename, $filetype = 'csv', $disk = '')
    {
        if($filetype == 'csv'){
            $type = \Maatwebsite\Excel\Excel::CSV;
            $ext = '.csv';
        }else if($filetype ==' xls'){
            $type = \Maatwebsite\Excel\Excel::XLS;
            $ext = '.xls';
        }else if ($filetype == 'xlsx'){
            $type = \Maatwebsite\Excel\Excel::XLSX;
            $ext = '.xlsx';
        }

        return Excel::store(new $class, $filename.$ext, $disk, $type);
    }

    public static function downloadExport($class, $filename, $ext)
    {
        return Excel::download(new $class, $filename.$ext);
    }

    public static function generatePDF($data, $filename, $option = [])
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->setOption($option);
        $pdf->loadHTML($data);
        $pdf->setPaper('A4');
        return $pdf->download($filename.'.pdf');
    }
}