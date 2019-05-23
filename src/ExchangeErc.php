<?php

namespace Chernogolov\Fogcms;

use Artisaninweb\SoapWrapper\SoapWrapper;
//use App\Soap\Request\GetConversionAmount;
//use App\Soap\Response\GetConversionAmountResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use Chernogolov\Fogcms\Attr;


use SoapClient;
use Chernogolov\Fogcms\Records;


class ExchangeErc extends Model
{
    /**
     * Authentificate
     *
     * return Access Token string
     * */

    public static function auth($forceNewToken = false)
    {
        $client = new SoapClient("https://api.erc-ekb.ru/Services/AccountService.svc?wsdl", array(
            'trace'=> true,
            'exceptions' => true,
            'encoding' => 'UTF-8',
            'compression' => true,
        ));

        if(Session::has('eAuthExpiresIn') && !$forceNewToken)
        {
            $eAuthExpiresIn = Session::get('eAuthExpiresIn');
            if($eAuthExpiresIn < Carbon::now())
            {
                $result = $client->RefreshAuth(['refreshToken' => Session::get('eAuthRefreshToken')]);
                if(isset($result->RefreshAuth->AccessToken))
                {
                    Session::put('eAuthExpiresIn', $result->RefreshAuth->ExpiresIn);
                    Session::put('eAuthRefreshToken', $result->RefreshAuth->RefreshToken);
                    Session::put('eAuthAccessToken', $result->RefreshAuth->AccessToken);
                }
                return $result->RefreshAuthResult->AccessToken;
            }
            else
                return Session::get('eAuthAccessToken');
        }
        else
        {
            $result = $client->Auth(['email' => Config::get('fogcms.erc_email'), 'password' => Config::get('fogcms.erc_password')]);
            if(isset($result->AuthResult->AccessToken))
            {
                Session::put('eAuthExpiresIn', $result->AuthResult->ExpiresIn);
                Session::put('eAuthRefreshToken', $result->AuthResult->RefreshToken);
                Session::put('eAuthAccessToken', $result->AuthResult->AccessToken);
            }
            return $result->AuthResult->AccessToken;
        }
    }

    public static function getDevices($account)
    {
        $token = Self::auth();

        $client = new SoapClient("https://api.erc-ekb.ru/Services/MeteringDeviceService.svc?wsdl", array(
            'trace'=> true,
            'exceptions' => true,
            'encoding' => 'UTF-8',
            'compression' => true,
        ));

        try {
            $result = $client->GetMeteringDevices([
                'lsNumber' => $account->account_number,
                'startDate' => Carbon::now()->startOfMonth()->toIso8601String(),
                'endDate' => Carbon::now()->toIso8601String(),
                'accessToken' => $token
            ]);
        } catch (\Exception $e) {

            file_get_contents('https://api.telegram.org/bot608599411:AAFPZIybZ-O9-t4y_rlRxmtQV4i-sV8aF6c/sendMessage?chat_id=293756888&text=gkh2 SOAP AUTH Warning ' . $e);

            $token = Self::auth(true);
            $result = $client->GetMeteringDevices([
                'lsNumber' => $account->account_number,
                'startDate' => Carbon::now()->startOfMonth()->toIso8601String(),
                'endDate' => Carbon::now()->toIso8601String(),
                'accessToken' => $token
            ]);
        }

        $res = [];

        $params = [
            'filters' => [
                'account_number' => [['account_number', '=', $account->id]],
            ]
        ];
        $devices = Records::getRecords(Config::get('fogcms.devices_reg_id'), $params)->keyBy('MeteringDeviceNumber');
        if(isset($result->GetMeteringDevicesResult->MeteringDevice))
        {
            if(isset($result->GetMeteringDevicesResult->MeteringDevice->CloseDate))
            {
                $item = $result->GetMeteringDevicesResult->MeteringDevice;
                if($item->CloseDate > Carbon::now()->toIso8601String())
                {
                    $item->account_number = $account->account_number;
                    if(count($devices)>0)
                    {
                        if($devices->has($item->MeteringDeviceNumber))
                        {
                            $item->id = $devices->get($item->MeteringDeviceNumber)->id;
                            $devices->forget($item->MeteringDeviceNumber);
                        }
                    }

                    $res[] = $item;
                }
            }
            else
            {
                foreach($result->GetMeteringDevicesResult->MeteringDevice as $item)
                {
                    if($item->CloseDate > Carbon::now()->toIso8601String())
                    {
                        $item->account_number = $account->account_number;
                        if(count($devices)>0)
                        {
                            if($devices->has($item->MeteringDeviceNumber))
                            {
                                $item->id = $devices->get($item->MeteringDeviceNumber)->id;
                                $devices->forget($item->MeteringDeviceNumber);
                            }
                        }

                        $res[] = $item;
                    }
                }
            }

            foreach ($devices as $delete) {
                Records::deleteRecord($delete->id,null,true);
            }

            Records::$import = true;
            Records::updateReg(Config::get('fogcms.devices_reg_id'), $res);
            //get devices data
        }
    }

    public static function getMeterValues($account)
    {
        $token = Self::auth();
        $client = new SoapClient("https://api.erc-ekb.ru/Services/MeteringDeviceService.svc?wsdl", array(
            'trace'=> true,
            'exceptions' => true,
            'encoding' => 'UTF-8',
            'compression' => true,
        ));

        $meteringDevicesValues = MeteringDevicesValues::where('account_number', $account->account_number)->first();
        if(!$meteringDevicesValues)
            $period = Carbon::now()->startOfYear()->toIso8601String();
        else
            $period = Carbon::now()->startOfMonth()->toIso8601String();

        try {
            $result = $client->GetMeteringDevicesValues([
                'lsNumber' => $account->account_number,
                'startDate' => $period,
                'endDate' => Carbon::now()->toIso8601String(),
                'accessToken' => $token
            ]);

        } catch (\Exception $e) {


            file_get_contents('https://api.telegram.org/bot608599411:AAFPZIybZ-O9-t4y_rlRxmtQV4i-sV8aF6c/sendMessage?chat_id=293756888&text=gkh2 SOAP ERROR ' . $account->account_number);
            $period = Carbon::now()->startOfMonth()->subMonth()->toIso8601String();

            $result = $client->GetMeteringDevicesValues([
                'lsNumber' => $account->account_number,
                'startDate' => $period,
                'endDate' => Carbon::now()->toIso8601String(),
                'accessToken' => $token
            ]);

        }

        if(isset($result->GetMeteringDevicesValuesResult->MeteringDeviceValue))
        {
            foreach($result->GetMeteringDevicesValuesResult->MeteringDeviceValue as $item)
            {
                $meteringDevicesValues = MeteringDevicesValues::where([['ValueDate', $item->ValueDate],['MeteringDeviceNumber', $item->MeteringDeviceNumber],['ScaleName', $item->ScaleName]])->first();
                if(!$meteringDevicesValues)
                {
                    $meteringDevicesValues = new MeteringDevicesValues();
                    $meteringDevicesValues->account_number = $account->account_number;
                    foreach($item as $key => $value)
                        $meteringDevicesValues->$key = $value;

                    $meteringDevicesValues->save();
                }
            }
        }
    }

    public static function getCharges($account)
    {
        $token = Self::auth();
        $client = new SoapClient("https://api.erc-ekb.ru/Services/ChargeService.svc?wsdl", array(
            'trace'=> true,
            'exceptions' => true,
            'encoding' => 'UTF-8',
            'compression' => true,
        ));

//        $meteringDevicesValues = MeteringDevicesValues::where('account_number', $account->account_number)->first();
//        if(!$meteringDevicesValues)
            $period = Carbon::now()->startOfYear()->toIso8601String();
//        else
//            $period = Carbon::now()->startOfMonth()->toIso8601String();

        try {
            $result = $client->GetCharges([
                'lsNumber' => $account->account_number,
                'startDate' => $period,
                'endDate' => Carbon::now()->toIso8601String(),
                'accessToken' => $token
            ]);

        } catch (\Exception $e) {


            file_get_contents('https://api.telegram.org/bot608599411:AAFPZIybZ-O9-t4y_rlRxmtQV4i-sV8aF6c/sendMessage?chat_id=293756888&text=gkh2 SOAP ERROR ' . $account->account_number);
            $period = Carbon::now()->startOfMonth()->subMonth()->toIso8601String();

            $result = $client->GetCharges([
                'lsNumber' => $account->account_number,
                'startDate' => $period,
                'endDate' => Carbon::now()->toIso8601String(),
                'accessToken' => $token
            ]);
        }
        dd($result);
    }

    public static function getInvoice($account, $month = null)
    {
        !$month ? $month = date('m') - 1 : null;
        $subMont = date('m') - $month;
        $subMont <= 0 ? $subMont += 12 : null;

        $beginDate = Carbon::now()->subMonths($subMont)->startOfMonth()->toIso8601String();

        $token = Self::auth();
        $client = new SoapClient("https://api.erc-ekb.ru/Services/LsService.svc?wsdl", array(
            'trace'=> true,
            'exceptions' => true,
            'encoding' => 'UTF-8',
            'compression' => true,
        ));

        $result = $client->DownloadQuittance([
            'lsNumber' => $account->account_number,
            'beginDate' => $beginDate,
            'accessToken' => $token
        ]);

        if(isset($result->DownloadQuittanceResult->Content))
        {
            $filename = $account->account_number . '.pdf';
            Storage::put('/public/invoice/' . $month . '/' . $filename,  $result->DownloadQuittanceResult->Content);
            //return Storage::download('/public/invoice/' . $month . '/' . $filename);
        }
    }
}
