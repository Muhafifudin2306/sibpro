<?php

namespace App\Exports;

use App\Models\CategoryHasCredit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoryHasCreditExport implements FromCollection, WithTitle, WithHeadings
{
    use Exportable;

    protected $title;

    public function __construct($title)
    {
        $this->title = $title;
    }

    public function collection()
    {
        return CategoryHasCredit::with('credit','category')->get()->map(function ($item, $index) {
            return [
                'Number' => $index + 1,
                'category_name' => $item->category->category_name,
                'credit_name' => $item->credit->credit_name,
                'credit_price' => $item->credit->credit_price,
            ];
        });
    }

    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        return [
            'Nomor',
            'Nama Kategori',
            'Nama Atribut SPP',
            'Harga SPP'
        ];
    }
}
