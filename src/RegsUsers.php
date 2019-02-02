<?php

namespace Chernogolov\Fogcms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RegsUsers extends Model
{
    /**
     * @var string
     */
    protected $table = 'regs_users';
    protected $fillable = ['reg_id', 'user_id', 'view', 'edit', 'delete', 'created_at', 'updated_at'];

    public static function getSendUsers($ids)
    {
        if(!is_array($ids))
            $ids = array($ids);

        $us = [];
        $users =  DB::table('users')
            ->join('regs_users', 'regs_users.user_id', '=', 'users.id')
            ->whereIn('regs_users.reg_id', $ids)
            ->where('regs_users.send', '=', 1)
            ->select('users.id')
            ->get();

        foreach ($users as $user) {
            $us[] = $user->id;
        }

        return $us;

    }

}
