<?php

namespace Chernogolov\Fogcms\Controllers;

use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public $title;
    public function index()
    {
        $views = [];
        $this->title = __('Settings');
        return view('fogcms::settings', ['title' =>$this->title, 'views' => $views]);
    }
}
