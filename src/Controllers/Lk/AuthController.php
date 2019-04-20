<?php

namespace Chernogolov\Fogcms\Controllers\Lk;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public  $title;

    public function reset_pass()
    {
        $this->title = __('Reset password');
        $data['views'] = array();
        return view('fogcms::lk/restore', $data);
    }
}