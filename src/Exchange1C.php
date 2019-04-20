<?php

namespace Chernogolov\Fogcms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Chernogolov\Fogcms\Records;


class Exchange1C extends Model
{
    /**
     *
     * */

    public static function auth()
    {

    }

    public static function netTicket($data = [])
    {
        dd($data);
    }
}
