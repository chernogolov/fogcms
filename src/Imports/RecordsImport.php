<?php
namespace Chernogolov\Fogcms\Imports;

use Chernogolov\Fogcms\Records;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class RecordsImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public $reg_ids;

    public function __construct($reg_ids)
    {
        !is_array($reg_ids) ? $this->reg_ids = array($reg_ids) : $this->reg_ids = $reg_ids;
    }

    public function collection(Collection $rows)
    {
        Records::$import = true;
        Records::updateReg($this->reg_ids, $rows);
    }

    public function chunkSize(): int
    {
        return 100;
    }
}