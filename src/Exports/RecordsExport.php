<?php

namespace Chernogolov\Fogcms\Exports;

use Chernogolov\Fogcms\Records;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Chernogolov\Fogcms\User;

class RecordsExport implements FromCollection, WithHeadings
{
    public $rows;
    public $params;

    public function __construct($rows, $params = null)
    {
       $this->rows = $rows;
       $this->params = $params;
    }

    public function collection()
    {
        return $this->rows;
    }
    public function headings(): array
    {
        if($this->params)
            return $this->params;
        else
            return array_keys((array)$this->rows->first());
    }
}