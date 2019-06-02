<?php

namespace Chernogolov\Fogcms\Controllers\Exchange;

use App\Http\Controllers\Controller;
use Chernogolov\Fogcms\Controllers\ODataController;
use Artisaninweb\SoapWrapper\SoapWrapper;
//use App\Soap\Request\GetConversionAmount;
//use App\Soap\Response\GetConversionAmountResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use SoapClient;
use Illuminate\Support\Carbon;
use Chernogolov\Fogcms\ExchangeErc;


class ExchangeErcController extends Controller
{
    /**
     * @var SoapWrapper
     */
//    protected $soapWrapper;
//    protected $login;
//    protected $password;
//    public $client;

    /**
     * SoapController constructor.
     *
     * @param SoapWrapper $soapWrapper
     */
//    public function __construct(SoapWrapper $soapWrapper)
//    {
//        $this->soapWrapper = $soapWrapper;
//        $this->client = new SoapClient("https://api.erc-ekb.ru/Services/AccountService.svc?wsdl", array(
//            'trace'=> true,
//            'exceptions' => true,
//            'encoding' => 'UTF-8',
//            'compression' => true,
//        ));
//    }

    /**
     * Authentificate
     *
     * return bool
     * */

//    public function auth(Request $request)
//    {
//        if($request->session()->has('eAuthExpiresIn'))
//        {
//            $eAuthExpiresIn = $request->session()->get('eAuthExpiresIn');
//            if($eAuthExpiresIn < Carbon::now())
//            {
//                $result = $this->client->RefreshAuth(['refreshToken' => $request->session()->get('eAuthRefreshToken')]);
//                if(isset($result->RefreshAuth->AccessToken))
//                {
//                    $request->session()->put('eAuthExpiresIn', $result->RefreshAuth->ExpiresIn);
//                    $request->session()->put('eAuthRefreshToken', $result->RefreshAuth->RefreshToken);
//                    $request->session()->put('eAuthAccessToken', $result->RefreshAuth->AccessToken);
//                }
//                return $result->RefreshAuthResult->AccessToken;
//            }
//            else
//                return $request->session()->get('eAuthAccessToken');
//        }
//        else
//        {
//            $result = $this->client->Auth(['email' => Config::get('fogcms.erc_email'), 'password' => Config::get('fogcms.erc_password')]);
//            if(isset($result->AuthResult->AccessToken))
//            {
//                $request->session()->put('eAuthExpiresIn', $result->AuthResult->ExpiresIn);
//                $request->session()->put('eAuthRefreshToken', $result->AuthResult->RefreshToken);
//                $request->session()->put('eAuthAccessToken', $result->AuthResult->AccessToken);
//            }
//            return $result->AuthResult->AccessToken;
//        }
//    }

    /**
     * Create a new record from OData remote service
     *
     * return bool
     * */

//    public function getRecord(Request $request)
//    {
//        $token = $this->auth($request);
//        dd($token);
//    }

    public function getDevices()
    {
        return ExchangeErc::getInvoice((object)['account_number' =>  1900126500]);
//        $token = $this->auth();
//        $result = $this->client->GetMeteringDevices([
//            'lsNumber' => $lsNumber,
//            'startDate' => \Carbon::now()->startOfMonth(),
//            'endDate' => \Carbon::now(),
//            'accessToken' => $token
//        ]);
//
//        dd($result);
        //get devices data
    }

    /**
     * Create a new record in OData remote service
     *
     * return bool
     * */

    public function pushRecord()
    {

    }

}
