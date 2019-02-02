<?php

namespace Chernogolov\Fogcms\Controllers;

use App\Http\Controllers\Controller;
use App;

class TestController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'Test';
    }
}
