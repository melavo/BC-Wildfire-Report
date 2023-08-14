<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class ExportFires implements FromCollection, WithHeadings
{
    public $fireCollection;
    public $headings;

    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        return $this->fireCollection;
    }
    
    public function headings(): array
    {
        return $this->headings;
    }
}
