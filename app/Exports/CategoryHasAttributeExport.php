<?php

namespace App\Exports;

use App\Models\CategoryHasAttribute;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoryHasAttributeExport implements FromCollection, WithTitle, WithHeadings
{
    use Exportable;

    protected $title;

    public function __construct($title)
    {
        $this->title = $title;
    }

    public function collection()
    {
        return CategoryHasAttribute::with('attribute','category')->get()->map(function ($item, $index) {
            return [
                'Number' => $index + 1,
                'category_name' => $item->category->category_name,
                'attribute_name' => $item->attribute->attribute_name,
                'attribute_price' => $item->attribute->attribute_price,
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
            'Nama Atribut Daftar Ulang',
            'Harga Daftar Ulang'
        ];
    }
}
