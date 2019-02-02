<?php

namespace Chernogolov\Fogcms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Chernogolov\Fogcms\Attr;

class Lists extends Model
{
    //
    protected $table = 'lists';

    public static function getLists()
    {
        //get all selects attributes
        $lists = Attr::getAttrsByType('select');
        foreach($lists as $k => $list)
        {
            $lists[$k]->data = DB::table('lists')->where('attr_id', '=', $list->id)->get();
        }

        return $lists;
    }

    public static function getList($id)
    {
        return DB::table('lists')->where('attr_id', '=', $id)->orderBy('ordering')->orderBy('value')->get();
    }

    public static function addValues($id, $data)
    {
        $values = explode(PHP_EOL, trim($data));
        foreach($values as $value)
            $result_id = DB::table('lists')->insertGetId(array('attr_id' => $id, 'value' => trim($value)));
    }

    public static function addValue($id, $value)
    {
        $val = DB::table('lists')->where([['attr_id', '=', $id],['value', '=', trim($value)]])->first();
        if($val)
            return $val->id;
        else
            return DB::table('lists')->insertGetId(array('attr_id' => $id, 'value' => trim($value)));
    }

    public static function saveValues($data)
    {
        foreach($data as $key => $values)
            DB::table('lists')->where('id', $key)->update($values);
    }

    public static function clearList($ids)
    {
        foreach($ids as $k => $v)
            DB::table('lists')->where('attr_id', '=', $k)->delete();
    }

    public static function deleteListItems($ids)
    {
        foreach($ids as $k => $v)
            DB::table('lists')->where('id', '=', $k)->delete();
    }
}
