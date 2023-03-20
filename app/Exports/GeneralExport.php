<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class GeneralExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection($collection)
    {
        return $collection;
    }
}
