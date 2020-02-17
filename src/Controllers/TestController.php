<?php

namespace Chernogolov\Fogcms\Controllers;

use App\Http\Controllers\Controller;
use Image;
use Chernogolov\Fogcms\ExchangeErc;
use Chernogolov\Fogcms\Records;
use Chernogolov\Fogcms\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;


class TestController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params = [];
        $params['added_user'] = Auth::user()->id;
        $params['offset'] = 0;
        $account = Records::getRecords(Config::get('fogcms.accounts_reg_id'), $params)->sortBy('address')->first();
        ExchangeErc::auth();
        ExchangeErc::getInvoice($account, 9);
//        ExchangeErc::getDevices($account);
//        ExchangeErc::getMeterValues($account);




//        $mimes = ['image/jpeg' => 'jpeg', 'image/png' => 'png', 'image/gif' => 'gif'];
//        $image ='https://ug-energo.com/assets/img/logo.png';
//        $info = pathinfo($image);
//        $img = Image::make($image);
//        $ext = $mimes[$img->mime];
//        $filename  = time() . rand(0,10) . str_slug($info['filename']) . '.' . $ext;
    }
}
