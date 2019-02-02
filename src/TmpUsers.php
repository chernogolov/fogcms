<?php

namespace Chernogolov\Fogcms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TmpUsers extends Model
{
    //

    protected $table = 'tmp_users';

    public static function createTmpUser($data)
    {
        $insertData = [
            'record_id' => $data['record_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ];
        $insertData['created_at']= \Carbon\Carbon::now()->toDateTimeString();
        $insertData['updated_at']= \Carbon\Carbon::now()->toDateTimeString();
        DB::table('tmp_users')->insertGetId($insertData);
    }
}
