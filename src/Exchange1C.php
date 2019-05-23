<?php

namespace Chernogolov\Fogcms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use Chernogolov\Fogcms\Records;
use Chernogolov\Fogcms\Comments;

use App\User;


class Exchange1C extends Model
{
    /**
     *
     * */
    private static $url;
    private static $postfix;

    public static function netTicket($data = [])
    {
        if(!empty($data) && empty($data['Ref_Key']))
        {
            Self::$url = 'http://' . env('TICKETS_IP', '') . '/' . env('TICKETS_BASE', '') . '/odata/standard.odata/';
            Self::$postfix = '?$format=json';
            //добавляем новую заявку в 1С
            $user = User::find($data['user_id']);

            $headers = array("Accept: application/json", "Accept-Charset: UTF-8", "User-Agent: Fiddler", "Content-Type: application/json");
            $ticket = [
                'Date' => $data['created_at'],
                'Организация_Key' => self::getCatalogValue('Организации', env('TICKETS_ORG', ''), Config::get('fogcms.organisation_reg_id'), 'title'),
                'СодержаниеЗаявки' => $data['description'],
                'ОбъектЖКХ_Key' => self::getCatalogValue('ОбъектыЖКХ', $data['account_number.address.fulladdress'], Config::get('fogcms.addresses_reg_id'), 'fulladdress'),
                'Помещение' => $data['account_number.room'],
                'Телефон' => $user->phone,
                'КатегорияЗаявки_Key' => self::getCatalogValue('КатегорииЗаявокНаВыполнениеРабот', $data['type']),
                'Заявитель' => $user->name,
                'ФормаОбращения_Key' => '3771c916-7882-11e9-80ce-049226d8a105',
                'Этаж' => $data['floor'],
            ];
            $ticket = json_encode($ticket);

            $url = Self::$url . 'Document_ЗаявкаНаВыполнениеРабот';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $ticket);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);

            $result = curl_exec($ch);
            curl_close($ch);

            $info = json_decode($result);
            if(isset($info->Ref_Key) && isset($info->Number) && strlen($info->Number)>2)
            {
                $data['Ref_Key'] = $info->Ref_Key;
                $data['Number'] = $info->Number;
                $data['Source'] = 'Личный кабинет';
                Records::$import = true;
                Records::updateReg(Config::get('fogcms.tickets_reg_id'), [$data]);

//                Self::uploadFiles($data['id'], $info->Ref_Key);

                return $info->Number;
            }
            else
                file_get_contents('https://api.telegram.org/bot608599411:AAFPZIybZ-O9-t4y_rlRxmtQV4i-sV8aF6c/sendMessage?chat_id=293756888&text=1C Export error. Ticket №' . $data['id'] . ' ' . $result);
        }

//        Self::uploadFiles($data['id'], $data['Ref_Key']);

        return false;
    }

    public static function getCatalogValue($catalog_name, $value, $reg_id = null, $field = null)
    {
        if($reg_id && $field)
        {
            $attrs = Attr::getRegsAttrs($reg_id);
            //сначала ищем в нашей базе
            $params = [
                'filters' => [
                    $field => [[$field, '=', $value]],
                ]
            ];

            $records = Records::getRecords($reg_id, $params)->keyBy('title');

            //если есть возвращаем
            if($records->has($value))
                if($records[$value]->Ref_Key && strlen($records[$value]->Ref_Key)>3)
                    return $records[$value]->Ref_Key;
        }


        //если нет - ищем в 1С
//        $filter='&$filter=Description eq \'' . $value . '\'';
        $url = Self::$url . 'Catalog_' . $catalog_name . Self::$postfix;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);

        curl_close($ch);

        $result = json_decode($result);
        $data = Collect($result->value)->keyBy('Description');
        if($data->has($value))
        {
            //записываем в БД
//            if($records->has($value))
//            {
//                $r = $records[$value];
//                $record = Records::getRecord($records[$value]->id);
//                $record['Ref_Key'] = $data[$value]->Ref_Key;
//                Records::saveRecord($reg_id, $record, $attrs);
//            }
            //возвращаем
            return $data[$value]->Ref_Key;
        }
        else
        {
            file_get_contents('https://api.telegram.org/bot608599411:AAFPZIybZ-O9-t4y_rlRxmtQV4i-sV8aF6c/sendMessage?chat_id=293756888&text=Значение ' . $value . ' не найдено в справочнике Catalog_' . $catalog_name);
            return false;
        }

    }

    public static function uploadFiles($record_id, $Ref_Key)
    {
        //
        dd(base64_decode("PFN0cmluZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMS9YTUxTY2hlbWEi\r\nIHhtbG5zOnhzaT0iaHR0cDovL3d3dy53My5vcmcvMjAwMS9YTUxTY2hlbWEtaW5z\r\ndGFuY2UiLz4="));
        $files = Attr::getRecordFiles($record_id);

        Self::$url = 'http://' . env('TICKETS_IP', '') . '/' . env('TICKETS_BASE', '') . '/odata/standard.odata/';
        Self::$postfix = '?$format=json';
        $url = Self::$url . 'Catalog_ЗаявкаНаВыполнениеРаботПрисоединенныеФайлы';
        $headers = array("Accept: application/json", "Accept-Charset: UTF-8", "User-Agent: Fiddler", "Content-Type: application/json");
        foreach ($files as $file) {
            $filedata = Storage::get('/public/' . $file->value);
            $data = [
                "ВладелецФайла_Key" => $Ref_Key,
                "ДатаСоздания" => date('Y-m-d\TH:i:sP'),
                "ДатаМодификацииУниверсальная" => date('Y-m-d\TH:i:sP'),
                "Описание" => pathinfo($file->value, PATHINFO_FILENAME),
                "Расширение" => pathinfo($file->value, PATHINFO_EXTENSION),
                "ТипХраненияФайла" => "ВИнформационнойБазе",
//                "ТекстХранилище_Type" => mime_content_type(public_path() . '/storage/' . $file->value),
                "ТекстХранилище_Type" => "application/xml+xdto",
                "ТекстХранилище_Base64Data" => base64_encode($filedata),
            ];
            $data = json_encode($data, JSON_UNESCAPED_SLASHES);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $result = curl_exec($ch);
            curl_close($ch);
            dd($result);

            $info = json_decode($result);
            if(isset($info->Ref_Key) && strlen($info->Ref_Key)>2)
                return $info->Ref_Key;
            else
                return false;

        }
    }

    public static function updateNewTickets()
    {
        $rid = Config::get('fogcms.tickets_reg_id');
        $params = [
            'filters' => [
                'status' => [['status', '=', '1']],
            ]
        ];

        $tickets = Records::getRecords($rid, $params);
        foreach ($tickets as $ticket) {
            if(isset($ticket->Number) && strlen($ticket->Number) > 3)
                Self::updateTicket($ticket->Ref_Key, $ticket);
        }

        $params = [
            'filters' => [
                'status' => [['status', '=', '2']],
            ]
        ];

        $tickets = Records::getRecords($rid, $params);
        foreach ($tickets as $ticket) {
            if(isset($ticket->Number) && strlen($ticket->Number) > 3)
                Self::updateTicket($ticket->Ref_Key, $ticket);
        }
    }

    public static function updateTicket($Ref_Key, $data)
    {
        Self::$url = 'http://' . env('TICKETS_IP', '') . '/' . env('TICKETS_BASE', '') . '/odata/standard.odata/';
        Self::$postfix = '(guid\''.$Ref_Key.'\')?$format=json';
        $url = Self::$url . 'Document_ЗаявкаНаВыполнениеРабот' . Self::$postfix;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);

        $info = json_decode($result);
        if(isset($info->РезультатВыполненияРабот) && strlen($info->РезультатВыполненияРабот) > 1)
        {
            $user_id = Config::get('fogcms.ads_user');
            !$user_id ? $user_id = 0 : null;

            $comment = Comments::getComments($data->id)->first();
            if(empty($comment))
            {
                Comments::saveComment(['text' => $info->РезультатВыполненияРабот, 'user_id' => $user_id], $data->id);
            }
            else
                Comments::updateComment(['text' => $info->РезультатВыполненияРабот], $comment->id);
        }
    }
}
