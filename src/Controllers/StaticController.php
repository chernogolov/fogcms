<?php

namespace Chernogolov\Fogcms\Controllers;

use App\Http\Controllers\Controller;

class StaticController extends Controller
{
    public function privacy_policy()
    {
        return view('privacy_policy');
    }
}