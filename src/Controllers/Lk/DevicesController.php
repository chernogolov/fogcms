<?php

namespace Chernogolov\Fogcms\Controllers\Lk;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

use Chernogolov\Fogcms\Controllers\LkController;

use Chernogolov\Fogcms\Records;
use Chernogolov\Fogcms\Attr;
use Chernogolov\Fogcms\Jobs\LoadErcData;
use Chernogolov\Fogcms\MeteringDevicesValues;


class DevicesController extends LkController
{
    public $title;
    public $devices = [];
    public $add_values_tpl = 'fogcms::lk/pages/add_values';
    public $accepted_values_tpl = 'fogcms::lk/pages/meter_values';

    public function __construct()
    {
        parent::__construct();
    }

    public function getDevices(Request $request, $view = false)
    {
        $this->title = __('Devices');
        !$view ? $this->getDevicesData() : null;
        if(empty($this->current_account))
            return Redirect::route('lk-accounts');

        $post_data = $request->all();
        if ($post_data) {
            if (isset($post_data['update-devices'])) {
                $job = (new LoadErcData((object)$this->current_account));
                dispatch($job);
            }
        }

        if($request->ajax() || $view)
            return view('fogcms::lk/pages/devices', ['devices' => $this->devices]);
        else
        {
            $this->data['views'][] = view('fogcms::lk/pages/devices', ['devices' => $this->devices]);
            return $this->index();
        }
    }

    public function getDevicesData()
    {
        $this->getAccounts();
        $devices = [];

        if(!empty($this->current_account)) {

            $params = [
                'filters' => [
                    'account_number' => [['account_number', '=', $this->current_account['id']]],
                ],
                'orderBy' => [
                    'attr' => 'ServiceName','type' => 'ASC','field' => 'value'
                ]
             ];
            $devices = Records::getRecords($this->devices_reg_id, $params);
        }

        $this->devices = $devices;

    }

    public function getAcceptedValues(Request $request, $view = false)
    {
        $this->title = __('Accepted values');

        !$view ? $this->getDevicesData() : null;

        if(!empty($this->current_account))
        {
            $meteringDevicesValues = MeteringDevicesValues::where('account_number', $this->current_account['account_number'])->get();
            if($request->ajax() || $view)
                return view($this->accepted_values_tpl, ['devices' => $this->devices, 'values' => $meteringDevicesValues]);
            else
            {
                $this->data['views'][] = view($this->accepted_values_tpl, ['devices' => $this->devices, 'values' => $meteringDevicesValues]);
                return $this->index();
            }
        }

    }

    public function addDevicesValues(Request $request, $view = false)
    {
        $this->title = __('Add devices values');
        !$view ? $this->getDevicesData() : null;

        $post_data = $request->all();
        if ($post_data) {
            if(isset($post_data['values']))
            {
                $validate_arr = [];
                foreach($this->devices as $device)
                {
                    if($device->ServiceName === "Электроэнергия")
                    {
                        $validate_arr['values.'.$device->id.'.ValueDay'] = 'min:0';
                        $validate_arr['values.'.$device->id.'.ValueNight'] = 'min:0';
                    }
                    else
                        $validate_arr['values.'.$device->id.'.ValueMain'] = 'min:0';

                }
                $validator = Validator::make($request->all(), $validate_arr);
                if ($validator->fails())
                {
                    return redirect('/add-devices-values')
                        ->withErrors($validator)
                        ->withInput();
                }

                $attrs = Collect(Attr::getRegsAttrs($this->add_values_reg_id))->keyBy('name');
                foreach($this->devices as $device)
                {
                    $nulled = true;
                    $sendData = [];
                    $sendData['SendDate']['attr_id'] = $attrs['SendDate']->attr_id;
                    $sendData['SendDate']['value'] = date('d-m-Y', time());

                    $sendData['Device']['attr_id'] = $attrs['Device']->attr_id;
                    $sendData['Device']['value'] = $device->id;
                    foreach($post_data['values'][$device->id] as $key => $device_value)
                    {
                        $sendData[$key]['attr_id'] = $attrs[$key]->attr_id;
                        $sendData[$key]['value'] = $device_value;
                        $device_value > 0 ? $nulled = false : null;
                    }

                    if(!$nulled)
                        $rid = Records::addRecord($this->add_values_reg_id, $sendData);
                }
            }
        }

        if(!empty($this->current_account)) {

            if ($request->ajax())
                return view($this->add_values_tpl, ['devices' => $this->devices]);
            elseif ($view) {
                $view = view($this->add_values_tpl, ['devices' => $this->devices]);
                return $view;
            } else {
                $this->data['views'][] = view($this->add_values_tpl, ['devices' => $this->devices]);
                $this->data['views'][] = $this->getSendetValues($request, $this->devices);
                return $this->index();
            }
        }
    }

    public function getSendetValues(Request $request)
    {
        $this->title = __('Get sendet values');
        $values = [];

        $post_data = $request->all();
        if ($post_data) {
            if (isset($post_data['delete'])) {
                foreach($post_data['delete'] as $k => $item)
                {
                    Records::deleteRecord($k, null, true);
                }
            }
        }

        foreach ($this->devices as $dev) {
            $params = [
                'filters' => [
                    'Device' => [['Device','=',$dev->id]],
                ]
            ];
            $values[$dev->id] = Records::getRecords($this->add_values_reg_id, $params);
        }

        return view('fogcms::lk/pages/sendet_values', ['devices' => $this->devices, 'values' => $values]);
    }

    public function utilities(Request $request)
    {
        $this->getDevicesData();
        if(!empty($this->current_account))
        {
            $this->data['views'][] = $this->getDevices($request, true);
            $this->data['views'][] = $this->addDevicesValues($request, true);
            $this->data['views'][] = $this->getSendetValues($request, $this->devices);

            $this->data['views'][] = $this->getAcceptedValues($request, true);
        }
        $this->title = __('Utilites');

        return $this->index();
    }
}

