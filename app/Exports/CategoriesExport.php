<?php

namespace App\Exports;

use App\Models\CategoryHasAttribute;
use App\Models\CategoryHasCredit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CategoriesExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];

        // Sheet 1: Data dari CategoryHasAttribute
        $sheets[] = new CategoryHasAttributeExport('Paket Atribut Daftar Ulang');

        // Sheet 2: Data dari CategoryHasCredit
        $sheets[] = new CategoryHasCreditExport('Paket Atribut SPP');

        return $sheets;
    }
}
