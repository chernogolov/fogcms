<?php

namespace Chernogolov\Fogcms\Controllers;

use App\Http\Controllers\Controller;
use Image;

class TestController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mimes = ['image/jpeg' => 'jpeg', 'image/png' => 'png', 'image/gif' => 'gif'];
        $image ='https://ug-energo.com/assets/img/logo.png';
        $info = pathinfo($image);
        $img = Image::make($image);
        $ext = $mimes[$img->mime];
        $filename  = time() . rand(0,10) . str_slug($info['filename']) . '.' . $ext;
    }
}
