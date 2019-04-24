<?php

namespace Chernogolov\Fogcms;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Rap2hpoutre\FastExcel\FastExcel;
use Chernogolov\Fogcms;
use Chernogolov\Fogcms\Records;

class ExcelF extends Model
{
    //
    public static function exportData($data, $node = null)
    {
        $newdata = array();
        $filename = 'file.xlsx';


        foreach($data as $item)
            $newdata[] = (array) $item;

        if(file_exists($filename))
            unlink($filename);

        if(count($newdata) == 0)
        {
            $fields = Attr::getFields($node);
            $newdata[] = array();
            foreach($fields['default_fields'] as $k => $v)
                $newdata[0][$k] = "";

        }
        $newdata = Collect($newdata);
        (new FastExcel($newdata))->export($filename);
        
        return $filename;
    }

    public static function importData($reg_ids, $file)
    {
        !is_array($reg_ids) ? $reg_ids = array($reg_ids) : null;
        $collection = (new FastExcel)->import($file);
        Records::$import = true;
        Records::updateReg($reg_ids, $collection);
    }
}
