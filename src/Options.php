<?php

namespace Chernogolov\Fogcms;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Options extends Model
{

    //
    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'options';

    public static function getOptions($group = null, $assoc = false)
    {
        $user_id = Auth::user()->id;

        $where[] = ['options.user_id', '=', $user_id];

        if($group)
            $where[] = ['options.group', '=', $group];

        $options = DB::table('options')
            ->where($where)->get();

        if($assoc)
        {
            $n = array();
            foreach($options as $option)
                $n[$option->name] = $option->value;
            $options = $n;
        }

        return $options;
    }

    public static function saveOptions($group, $data)
    {
        $user_id = Auth::user()->id;

        foreach($data as $k => $v)
        {
            $option = DB::table('options')
                ->where([
                    ['options.group', '=', $group],
                    ['options.user_id', '=', $user_id],
                    ['options.name', '=', $k]]
                )
                ->get();
            if(count($option)==0)
            {
                $res = DB::table('options')->insert(
                    ['group' => $group,
                        'user_id' => $user_id,
                        'name' => $k,
                        'value' => $v]
                );
            }
            else
            {
                $res = DB::table('options')
                    ->where([
                            ['options.group', '=', $group],
                            ['options.user_id', '=', $user_id],
                            ['options.name', '=', $k]]
                    )
                    ->update(
                        ['value' => $v]
                    );
            }

        }

    }
}
