<?php

namespace Chernogolov\Fogcms;

use Illuminate\Database\Eloquent\Model;

class Filters extends Model
{
    //
    public static function validateFilters($data)
    {
        $filters = array();
        foreach($data as $type => $item)
        {
            $typefilters = array();
            foreach($item as $k => $f)
                if(count($f) == 3 && $f[2] != null)
                    $typefilters[] = $f;


            if(!empty($typefilters))
                $filters[$type] = $typefilters;
        }
        return $filters;
    }
}
