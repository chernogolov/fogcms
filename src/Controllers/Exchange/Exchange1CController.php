<?php

namespace Chernogolov\Fogcms\Controllers\Exchange;

use App\Http\Controllers\Controller;
use Chernogolov\Fogcms\Controllers\ODataController;
use Chernogolov\Fogcms\Exchange1C;
use Chernogolov\Fogcms\Records;
use Illuminate\Http\Request;


class Exchange1CController extends ODataController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function newTicket(Request $request, $id)
    {
        $data = Records::getRecord($id);
        Exchange1C::netTicket($data);
    }

    public function pushRecord()
    {

    }
}
