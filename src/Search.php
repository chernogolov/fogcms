<?php

namespace Chernogolov\Fogcms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Search extends Model
{
    //
    public static function searchByAttrs($text, $attrs)
    {
        $p = 0;
        $r = [];
        $limit = 20;
        foreach($attrs as $attr)
        {
            $p++;
            $q = DB::table('attrs_chars')
                ->where([['attr_id', '=', $attr->attr_id],['value', 'LIKE', str_replace(' ', '%', trim(strip_tags($text))).'%']])
                ->join('records_regs', 'attrs_chars.record_id', '=', 'records_regs.records_id')
                ->select(['attrs_chars.record_id as rid'])
                ->groupBy('attrs_chars.record_id')
                ->limit($limit)
                ->get()
                ->keyBy('rid');

            if(count($q)==0)
                $q = DB::table('attrs_chars')
                    ->where([['attr_id', '=', $attr->attr_id],['value', 'LIKE', '%'.str_replace(' ', '%', trim(strip_tags($text))).'%']])
                    ->join('records_regs', 'attrs_chars.record_id', '=', 'records_regs.records_id')
                    ->select(['attrs_chars.record_id as rid'])
                    ->groupBy('attrs_chars.record_id')
                    ->limit($limit)
                    ->get()
                    ->keyBy('rid');

            foreach ($q as $key => $item) {
                isset($r[$key]) ? $r[$key] = $r[$key] + $p : $r[$key] = $p;
            }
        }

        arsort($r);
        return $r;
    }
}

