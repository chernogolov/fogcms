<?php

namespace Chernogolov\Fogcms;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Rap2hpoutre\FastExcel\FastExcel;
use Chernogolov\Fogcms;\Records;

class Excel extends Model
{
    //
    public static function exportData($data, $node = null)
    {
        $newdata = array();
        $filename = 'file.xlsx';

        foreach($data as $item)
            $newdata[] = (array) $item;

        unlink($filename);
        $newdata = Collect($newdata);
        (new FastExcel($newdata))->export($filename);
        
        return $filename;
    }

    public static function importData($reg_ids, $file)
    {
        !is_array($reg_ids) ? $reg_ids = array($reg_ids) : null;
        $collection = (new FastExcel)->import($file);

        Records::updateReg($reg_ids, $collection);
    }
}
