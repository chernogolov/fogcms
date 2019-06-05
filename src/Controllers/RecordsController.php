<?php

namespace Chernogolov\Fogcms\Controllers;

use Chernogolov\Fogcms\Imports\RecordsImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

use Chernogolov\Fogcms\Controllers\PanelController;
use Chernogolov\Fogcms\Notifications\AddRecord;
use Chernogolov\Fogcms\Notifications\EditRecord;
use Chernogolov\Fogcms\Notifications\AccountApproved;

use Chernogolov\Fogcms\Reg;
use Chernogolov\Fogcms\RegsUsers;
use Chernogolov\Fogcms\Attr;
use Chernogolov\Fogcms\Filters;
use Chernogolov\Fogcms\Records;
use Chernogolov\Fogcms\RecordsRegs;
use Chernogolov\Fogcms\User;
use Chernogolov\Fogcms\Options;
use Chernogolov\Fogcms\TmpUsers;
use Chernogolov\Fogcms\ExcelF;

class RecordsController extends PanelController
{
    public $title = '';
    public $node = array();
    public $nodes = array();
    public $options = array();
    public $access = array();
    public $ptemplate = 'fogcms::records';
    public $rtemplate = 'fogcms::records/regs';
    private $debug = true;
    /**
     * Show the records.
     *
     * @return \Illuminate\Http\Response
     */

    public function getNodes()
    {
        $root = Reg::where('name', '=', 'root')->first();
        $node = $root;
        $this->access = RegsUsers::where([['user_id', '=', Auth::user()->id],['view', '=', 1]])->get()->keyBy('reg_id');
        $nodes = $root->descendantsAndSelf()->withoutNode($node)->whereIn('id', $this->access->keys())->get()->keyBy('id');
        $this->nodes = collect();
        foreach($nodes as $node)
        {
            $node->access = $this->access[$node->id];
            $this->nodes->push($node);
            if(!isset($nodes[$node->parent_id]))
            {
                $guest_node = $root->where('id', $node->parent_id)->first();
                $this->access[$guest_node->id] = false;
                $this->nodes->push($guest_node);
            }
        }
        $this->nodes = $this->nodes->keyBy('id');
        if(count($this->nodes)===0)
            abort(404);
    }

    public function can($type = 'view', $id = false)
    {
        if(!empty($this->node) && $this->node->access && intval($this->node->access->$type) === 1)
            return true;
        else
            return false;
    }

    public function index($view = null)
    {
        $this->getNodes();
        $this->title .= 'Все записи';
        if(empty($this->node))
            foreach($this->nodes as $node)
                if(isset($node->access) && $node->access != false)
                {
                    $this->node = $node;
                    break;
                }

        $views['regs'][] = view($this->rtemplate, array('nodes' => $this->nodes, 'node' => $this->node));
        $views['records'][] = $view;

        if($this->debug && $view)
            return $view;

        return view($this->ptemplate, array('views' => $views, 'title' => $this->title));
    }

    public function records(Request  $request, $id)
    {
        $data = [];
        $this->getNodes();

        if(isset($this->nodes[$id]) && $this->nodes[$id]->access != false)
            $this->node = $this->nodes[$id];
        else
            return(view('fogcms::records/error', ['text' => __('Access denied')]));

        $params = Attr::getFields($this->node);

        $template = $this->node['type'];
        if($this->node->is_summary == 1)
        {
            $template = 'total';
            $data['nodes'] = $this->nodes;
        }

        $post_data = $request->all();

        if ($request->session()->has('flds'.$id))
            $params['fields'] = $request->session()->get('flds'.$id);

        if ($request->session()->has('ordby'.$id))
            $params['orderBy'] = $request->session()->get('ordby'.$id);

        //if session has filters, use it
        if ($request->session()->has('f'.$id))
            $params['filters'] = $request->session()->get('f'.$id);

        if(isset($post_data['clear_all']))
        {
            $request->session()->forget('f'.$id);
            $request->session()->forget('flds'.$id);
            $request->session()->forget('ordby'.$id);
            return redirect(route('reg_records', ['id' => $id]));
        }

        if(isset($post_data['clear_filters']))
        {
            $request->session()->forget('f'.$id);
            return redirect(route('reg_records', ['id' => $id]));
        }

        if(isset($post_data['clear_fields']))
        {
            $request->session()->forget('flds'.$id);
            return redirect(route('reg_records', ['id' => $id]));
        }

        if(isset($post_data['clear_order']))
        {
            $request->session()->forget('ordby'.$id);
            return redirect(route('reg_records', ['id' => $id]));
        }

        if ($post_data)
        {
            if(isset($post_data['template']))
                $template = $post_data['template'];

            if(isset($post_data['delete']) && $this->can('delete'))
                foreach($post_data['check'] as $rid => $v)
                    Records::deleteRecord($rid);

            if(isset($post_data['copy']) && $this->can('delete'))
                foreach($post_data['check'] as $rid => $v)
                {
                    $success = Records::copyRecord($rid, $post_data['destination']);
                    if(isset($post_data['notificate']))
                    {
                        foreach($success as $s)
                        {
                            $sender = __('New message');
                            $users = User::whereIn('id', RegsUsers::getSendUsers($s))->get();
                            foreach ($users as $user) {
                                try {
                                    Notification::send($user, new AddRecord($id, $rid, $sender));
                                } catch (\Exception $e) {
                                    file_get_contents('https://api.telegram.org/bot608599411:AAFPZIybZ-O9-t4y_rlRxmtQV4i-sV8aF6c/sendMessage?chat_id=293756888&text=gkh2 send ' . $user->email . ' notification error ' . $e);
                                }
                            }
                        }
                    }
                }

            if(isset($post_data['filters']))
            {
                $params['filters'] = Filters::validateFilters($post_data['filters']);
                if(!isset($post_data['nosavefilters']))
                    !empty($params['filters']) ? $request->session()->put('f'.$id, $params['filters']) :  $request->session()->forget('f'.$id);
            }

            if(isset($post_data['fields']))
            {
                $params['fields'] = array_intersect_key($params['default_fields'], $post_data['fields']);
                !empty($params['fields']) ? $request->session()->put('flds'.$id, $params['fields']) : $request->session()->forget('flds'.$id);
            }


            if(isset($post_data['import_file']))
            {
                Excel::import(new RecordsImport($id), request()->file('import_file'));
//                Excel::importData($id, $request->file('import_file'));
                return redirect(route('reg_records', ['id' => $id]));
            }

            if(isset($post_data['orderBy']))
            {
                $params['orderBy'] = $post_data['orderBy'];
                !empty($params['orderBy']) ? $request->session()->put('ordby'.$id, $params['orderBy']) : $request->session()->forget('ordby'.$id);
            }
        }

        //check hidden fields for filters and ordering settings
        if(isset($params['orderBy']['attr']))
        {
            if(!in_array($params['orderBy']['attr'], array_keys($params['fields'])))
                return redirect(route('reg_records', ['id' => $id]));
        }

        //export journal data
        if(isset($post_data['export']))
        {
            $params['limit'] = 9999999;
            $data['records'] = Records::getRecords($id, $params);
            return ExcelF::exportData($data['records'], $this->node);
        }

        //clear journal
        if(isset($post_data['clear']))
            $data['records'] = Records::clearReg($post_data['clear']);

        $data['records'] = Records::getRecords($id, $params);

        $this->title .= $this->node['name'];
        $data['reg_id'] = $id;
        $data['node'] = $this->node;
        $data['nodes'] = $this->nodes;
        $data['params'] = $params;
        $this->can('delete') ? $data['delete'] = true : null;
        $this->can('edit') ? $data['edit'] = true : null;

//        \Debugbar::startMeasure('render','123');

        if($request->ajax())
            return view('fogcms::records/'.$template, $data);
        else
            return $this->index(view('fogcms::records/'.$template, $data));

//        \Debugbar::stopMeasure('render');
    }

    public function view(Request  $request, $id, $rid)
    {
        $this->options = Options::getOptions('view', true);
        $this->node = Reg::where('id', '=', $id)->first();
        $this->title .= __('Overview');

        $request_data = $request->all();
        if(isset($request_data['change-status']))
            Records::changeRecordStatus($rid, intval($request_data['change-status']));

        $record = Records::where('id', '=', $rid)->first();

        if(!$record)
            return abort('404');

        $record['reg_id'] = $id;
        $record_nodes = RecordsRegs::where('records_id', '=', $rid)->get();

        $view = view('fogcms::records/view/'.$this->node['type'], array(
            'nodes' => $this->nodes,
            'record_nodes' => $record_nodes,
            'data' => $record,
            'vls' => array(),
            'access' => $this->node->access,
            'back' => route('reg_records', ['id' => $id])
        ));

        $attrs = Attr::getRegsAttrs($id, $rid);

        foreach($attrs as $attr)
        {
            $template = $attr->type;
            $attr->modificator ? $template .= '.'.$attr->modificator : null;
            $view .= view('fogcms::records/attrs/view/'.$template, array('attr' => $attr, 'vls' => array(), 'access' => $this->node->access));
        }

        $additional['comments'] = CommentsController::getComments($rid);
        $additional['reg_id'] = $id;
        $additional['creator'] = [];
        $additional['records'] = [];

        if($this->node->type == 'tickets')
        {
            $additional['history'] = Records::getRecordStatuses($rid);

            $tmp_user =  TmpUsers::where('record_id', '=', $record->id)->first();
            if(!$tmp_user)
            {
                $user = User::where('id', '=', $record->user_id)->first();
                $additional['creator']  = $user;
                $additional['records'] = Records::getRecords($id, ['user_id' => $record->user_id, 'exclude' => $rid]);
            }
            else
            {
                $additional['creator'] = $tmp_user;
                $additional['creator'] ? $additional['creator']['name'] .= ' (' . __('temporary') . ' ) ' : null;
            }
        }

        $view .= view('fogcms::records/additional', $additional);

        if($request->ajax())
            return $view;
        else
            return $this->index($view);
    }

    public function create(Request  $request, $id)
    {
        $this->options = Options::getOptions('view', true);
        $this->node = Reg::where('id', '=', $id)->first();
        $validate_attr = [];
        $sender = '';

        isset($post_data['post_data']['back']) ? $back = $post_data['post_data']['back'] : $back = route('reg_records', ['id' => $id]);

        $view = view('fogcms::records/edit/'.$this->node['type'], array(
            'nodes' => $this->nodes,
            'record_nodes' => array($this->node),
            'vls' => array(),
            'access' => $this->node->access,
            'node' => $this->node,
            'back' => $back
        ));
        $attrs = Attr::getRegsAttrs($id);
        foreach($attrs as $attr)
        {
            if($attr->is_required)
                $validate_attr['attr.'.$attr->name.'.value'] = 'required';

            $attr->modificator ? $template = $attr->type . '.' . $attr->modificator : $template = $attr->type;
            $view .= view('fogcms::records/attrs/'.$template, array('attr' => $attr, 'access' => $this->node->access));
        }


        $post_data = $request->all();
        if ($post_data)
        {
            $this->validate($request, $validate_attr);

            $regs = Records::getDestinationNodes($attrs, $post_data['attr']);
            if(!empty($regs))
                $destination = $regs;
            else
                $destination = $id;

            $rid = Records::addRecord($destination, $post_data['attr']);

            if($rid)
            {
                if (isset($post_data['attr']['address']['value'])) {
                    $address = Records::getRecord($post_data['attr']['address']['value']);
                    isset($address['fulladdress']) ? $sender .= ' ' . $address['fulladdress'] : null;
                }

                if (isset($post_data['attr']['territory']['value'])) {
                    $address = Records::getRecord($post_data['attr']['territory']['value']);
                    isset($address['type']) ? $sender .= ' ' . $address['type'] : null;
                }

                $users = User::whereIn('id', RegsUsers::getSendUsers($regs))->get();
                foreach ($users as $user) {
                    try {
                        Notification::send($user, new AddRecord($id, $rid, $sender));
                    } catch (\Exception $e) {
                        file_get_contents('https://api.telegram.org/bot608599411:AAFPZIybZ-O9-t4y_rlRxmtQV4i-sV8aF6c/sendMessage?chat_id=293756888&text=gkh2 send ' . $user->email . ' notification error ' . $e);
                    }
                }
            }

            //go to list function
            if(isset($post_data['to_list']))
            {
                Session::put('to_list', 'checked');
                return redirect()->route('reg_records', ['id' => $id]);
            }
            else
                Session::forget('to_list');
        }

        $this->node->regs_id = $this->node->id;
        $this->title .= __('New record');


        $view .= view('fogcms::records/endform', ['to_list' => Session::get('to_list'), 'back' => route('reg_records', ['id' => $id])]);

        if($request->ajax())
            return $view;
        else
            return $this->index($view);
    }

    public function edit(Request  $request, $id, $rid)
    {
        $validate_attr = [];
        $this->options = Options::getOptions('view', true);
        $this->node = Reg::where('id', '=', $id)->first();
        $this->title .= __('Edit');
        isset($post_data['post_data']['back']) ? $back = $post_data['post_data']['back'] : $back = route('reg_records', ['id' => $id]);
        $attrs = Attr::getRegsAttrs($id);

        $post_data = $request->all();
        if($post_data)
        {
            foreach($attrs as $attr)
            {
                if($attr->is_required && !isset($post_data['attr'][$attr->name]['save']))
                    $validate_attr['attr.'.$attr->name.'.value'] = 'required';
            }


            $request->validate($validate_attr);
            Records::saveRecord($id, $post_data['record'], $post_data['attr']);

            //go to list function
            if(isset($post_data['to_list']))
            {
                Session::put('to_list', 'checked');
                return redirect()->route('reg_records', ['id' => $id]);
            }
            else
                Session::forget('to_list');
        }

        $record = Records::where('id', '=', $rid)->first();

        if(!$record)
            return abort('404');

        $record_nodes = RecordsRegs::where('records_id', '=', $rid)->get();
        $view = view('fogcms::records/edit/'.$this->node['type'], array(
            'nodes' => $this->nodes,
            'record_nodes' => $record_nodes,
            'data' => $record,
            'vls' => array(),
            'access' => $this->node->access,
            'node' => $this->node,
            'back' => $back,
        ));

        $attrs = Attr::getRegsAttrs($id, $rid);
        foreach($attrs as $attr)
        {
            $attr->modificator ? $template = $attr->type . '.' . $attr->modificator : $template = $attr->type;
            $view .= view('fogcms::records/attrs/'.$template, array('attr' => $attr, 'access' => $this->node->access));
        }

        $view .= view('fogcms::records/endform', ['to_list' => Session::get('to_list'), 'back' => $back]);
        if($request->ajax())
            return $view;
        else
            return $this->index($view);
    }

    public function trash(Request  $request)
    {
        $post_data = $request->all();
        isset($post_data['trash']) && $post_data['trash'] == 'clear' ? Records::clearTrash() : null;

        $records = Records::getTrash();

        $this->title .= __('Trash');
        return view('fogcms::records/trash', array('records' => $records));
    }

    public function change_status(Request  $request, $rid, $sid = null)
    {
        $sid = 1;
        $post_data = $request->all();

        if(isset($post_data['sid']))
            $sid = $post_data['sid'];

        if($sid)
        {
            $rsid = Records::changeRecordStatus($rid, $sid);

            if($rsid)
            {

                $record = Records::getRecord($rid);
                $regs = Records::getRecordRegs($rid)->get();
                $users = [];
                foreach ($regs as $reg) {
                    $user_sends = RegsUsers::where([['user_id', '=', $record['user_id']], ['reg_id', '=', $reg->id]])->get();
                    $channels = [];
                    foreach ($user_sends as $send)
                        if(intval($send->email) == 1)
                            $channels[] = 'mail';

                    $channels = array_unique($channels);
                    $user = User::where('id', '=', $record['user_id'])->first();
                }

                try {
                    Notification::send($user, new EditRecord((object)$record, '', $channels));
                } catch (\Exception $e) {
                    file_get_contents('https://api.telegram.org/bot608599411:AAFPZIybZ-O9-t4y_rlRxmtQV4i-sV8aF6c/sendMessage?chat_id=293756888&text=gkh2 send ' . $user->email . ' notification error ' . $e);
                }


                return $rsid;
            }

            if(!isset($post_data['sid']))
                return redirect()->route('view_record', ['rid' => $rid]);
        }
    }

    public function document($id, $rid)
    {
        $record = Records::getRecord($rid);
        $node = Reg::where('id', '=', $id)->first();
        if(isset($node->print_template) && $node->print_template != '')
        {
            $document  = new \PhpOffice\PhpWord\TemplateProcessor(url("/") . $node->print_template);
            foreach($record as $name => $value)
                $document->setValue($name, $value);

            header("Content-Disposition: attachment; filename='result.docx'");
            $document->saveAs('php://output');
        }
    }

    public function ajaxReg($id)
    {
        $this->getNodes();
        $this->node = Reg::where('id', '=', $id)->first();
        $this->node->access = $this->access[$id];

        $data = array();
        $params = array();
        $params['default_fields'] = Config::get('fogcms.default_fields');

        foreach(explode(',', $this->node->default_fields) as $field)
            $field != '' ? $params['fields'][$field] = $params['default_fields'][$field] : null;

        $attrs = Attr::getRegsAttrs($id);
        foreach($attrs as $attr)
        {
            $template = $attr->type;
            $attr->modificator ? $template .= '.'.$attr->modificator : null;

            $params['default_fields'][$attr->name] = [
                'type' => $attr->type,
                'title' => $attr->title,
                'filter_template' => 'fogcms::records/attrs/filter/'.$template,
                'params' => ['attr' => $attr],
            ];

            //default field view
            if($attr->is_filter == 1)
                $params['fields'][$attr->name] = $params['default_fields'][$attr->name];

            View::exists('fogcms::records/attrs/data/'.$template) ? $params['default_fields'][$attr->name]['data_template'] = 'fogcms::records/attrs/data/'.$template : null;
        }

        $template = $this->node['type'];
        if($this->node->is_summary == 1)
        {
            $template = 'total';
            $this->access = RegsUsers::where([['user_id', '=', Auth::user()->id],['view', '=', 1]])->get()->keyBy('reg_id');
            $nodes = $this->node->descendantsAndSelf()->withoutNode($this->node)->get();
            $data['nodes'] = collect();
            foreach($nodes as $node)
            {
                $node->access = $this->access[$node->id];
                $data['nodes']->push($node);
            }
        }

        //check hidden fields for filters and ordering settings
        if(isset($params['orderBy']['attr']))
        {
            if(!in_array($params['orderBy']['attr'], array_keys($params['fields'])))
                return redirect(route('reg_records', ['id' => $id]));
        }

        $data['records'] = Records::getRecords($id, $params);
        $this->title .= $this->node['name'];
        $data['reg_id'] = $id;
        $data['node'] = $this->node;
        $data['params'] = $params;

        return view('fogcms::records/'.$template, $data);
    }

    public function onoff(Request  $request, $rid, $sid)
    {
        return Records::OnOff($rid, $sid);
    }

    public function rate(Request  $request, $rid)
    {
        $post_data = $request->all();

        if(isset($post_data['rating']))
            $rating = $post_data['rating'];

        if(isset($rating))
        {
            return Records::Rate($rid, $rating);
        }
    }
}
