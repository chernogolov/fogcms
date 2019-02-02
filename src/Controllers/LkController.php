<?php

namespace Chernogolov\Fogcms\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

use App\Http\Controllers\Controller;
use Baum\Providers\BaumServiceProvider;
use App\Notifications\AddRecord;

use App\Attr;
use App\TmpUsers;
use App\Options;
use App\User;
use App\RegsUsers;
use App\Records;
use App\Comments;

use Chernogolov\Fogcms\Reg;

class LkController extends Controller
{
    //
    public $title = '';
    public $nodes;

    public function getNodes()
    {
        $root = Reg::where('name', '=', 'root')->first();
        $node = $root;
        $this->nodes = $root->descendantsAndSelf()->withoutNode($node)->where('is_public', '=', 1)->get();
    }

    public function index(Request $request)
    {
        $this->getNodes();
        $data['nodes'] = $this->nodes;
        $data['views'] = array();

        Auth::check() ? $this->title = __('Userarea') : $this->title = __('Home');
        $data['title'] = $this->title;

        $data['views'][] = view('fogcms::lk/index');

        if (Auth::check())
            $userData['user'] = User::where('id', '=', Auth::user()->id)->first();

        return view('fogcms::lk', $data);
    }

    public function create(Request $request, $id, $from_rid = null)
    {
        $validate_attr = [];
        $node = Reg::where('id', '=', $id)->first();
        $this->getNodes();
        $data['nodes'] = $this->nodes;
        $additional = [];
        $sender = '';
        $subject = 'Новая заявка ';

        $this->title = 'Создать';
        $data['title'] = $this->title;

        if($from_rid)
        {
            $this->title = 'Создать на основе';
            $record = Records::getRecord($from_rid);
        }

        if (!$node || $node->is_public != 1)
            abort('404');

        $attrs = Attr::getRegsAttrs($id, $from_rid);
        $view = view('/panel/lk/new');

        foreach ($attrs as $attr)
            if ($attr->is_public && $attr->modificator != 'deadline')
                if ($attr->is_required)
                    $validate_attr['attr.' . $attr->name . '.value'] = 'required';

        $post_data = $request->all();
        if ($post_data) {
            if (!Auth::check())
                $validate_attr['g-recaptcha-response'] = 'required|captcha';

            if (!Auth::check() || isset($post_data['user']['tmp'])) {
                $validate_attr['user.name'] = 'required|min:2';
                $validate_attr['user.phone'] = 'required|min:7|max:15';
            }

            if (isset($post_data['user']['tmp'])) {
                Session::put('create_tmp_user', true);
                $additional['user_id'] = Auth::user()->id;
            } else
                Session::forget('create_tmp_user');

            $this->validate($request, $validate_attr);
            $regs = Records::getDestinationNodes($attrs, $post_data['attr']);

            if($from_rid)
            {
                foreach($post_data['attr'] as $k => $item)
                {
                    unset($post_data['attr'][$k]['id']);
                    unset($post_data['attr'][$k]['save']);
                }

                //if has tmp user - create new tmp user
                $tmp_user = TmpUsers::where('record_id', '=', $from_rid)->first();
                if(!isset($tmp_user->id))
                    $additional['user_id'] = $record['user_id'];
            }

            $rid = Records::addRecord($regs, $post_data['attr'], $additional);

            if(count($regs)<3)
                file_get_contents('https://api.telegram.org/bot608599411:AAFPZIybZ-O9-t4y_rlRxmtQV4i-sV8aF6c/sendMessage?chat_id=293756888&text=gkh2 ID: '.$rid.' ' . implode(',', $regs));

            if ($rid) {
                $subject .= '#' . $rid;
                if (!Auth::check() || isset($post_data['user']['tmp'])) {
                    $post_data['user']['record_id'] = $rid;
                    TmpUsers::createTmpUser($post_data['user']);
                }

                if(isset($tmp_user) && isset($tmp_user->id))
                {
                    $tmp_user->record_id = $rid;
                    TmpUsers::createTmpUser($tmp_user);
                }

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
            return redirect(route('lk_success', ['id' => $id, 'rid' => $rid]));
        }

        foreach ($attrs as $attr) {
            if ($attr->is_public) {
                if($from_rid)
                    $attr->mode = 'new';
                $attr->modificator ? $template = $attr->type . '.' . $attr->modificator : $template = $attr->type;
                $view .= view('panel/records/attrs/' . $template, array('attr' => $attr));
            }
        }

        if(Auth::check())
        {
            $data['user'] = Auth::user();
            if($from_rid)
            {
                $data['user'] =  TmpUsers::where('record_id', '=', $from_rid)->first();
                if(!$data['user'])
                    $data['user'] = User::where('id', '=', $record['user_id'])->first();
            }

        }
        else
            $data['user'] = [];

//        if(Session::get('create_tmp_user') == true)
        if(Gate::allows('view-regs') && !$from_rid)
            $data['user']['tmp'] = true;

        $view .= view('/panel/lk/user', $data);
        $view .= view('/panel/lk/endform');

        $data['views'][] = $view;
        return view('/panel/lk', $data);
    }

    public function success($id, $rid)
    {
        $node = Reg::where('id', '=', $id)->first();
        $data['title'] = 'Обращение успешно добавлено';

        $this->getNodes();
        $data['nodes'] = $this->nodes;

        $data['views'][] = view('/panel/lk/appeal/success', ['id' => $rid]);
        return view('/panel/lk', $data);
    }

    public function find(Request $request, $id)
    {
        $this->getNodes();
        $data['nodes'] = $this->nodes;
        $data['node'] = Reg::where('id', '=', $id)->first();


        $data['views'] = array();
        $this->title = 'Поиск по номеру';
        $post_data = $request->all();

        isset($post_data['find_id']) ? $find_id = $post_data['find_id'] : $find_id = null;
        $data['views'][] = view('/panel/lk/check', ['find_id' => $find_id]);

        if(isset($post_data['find_id']))
        {
            if(Auth::check())
                $record = Records::where([['id', '=', $find_id],['user_id', '=', Auth::user()->id]])->first();
            else
                $record = Records::where([['id', '=', $find_id],['user_id', '=', 0]])->first();

            if(!empty($record))
                $data['views'][] = view('/panel/lk/appeals', ['records' =>array($record), 'node' => $data['node']]);
            else
                $data['views'][] = '<div class="alert alert-warning">Ничего не найдено</div>';
        }

        $data['title'] = $this->title;
        return view('/panel/lk', $data);
    }

    public function appeals(Request $request, $id)
    {
        $node = Reg::where('id', '=', $id)->first();
        $this->getNodes();
        $data['nodes'] = $this->nodes;

        $data['views'] = array();
        $this->title = 'Мои обращения';

        $attrs = Attr::getFields($node, ['entry']);
        if(Auth::check())
        {
            $records = Records::getRecords(null, array('user_id' => Auth::user()->id, 'type' => 'tickets', 'fields' => $attrs['fields']));
            $data['views'][] = view('/panel/lk/appeals', ['records' => $records, 'node' => $node]);
        }

        $data['title'] = $this->title;
        return view('/panel/lk', $data);
    }

    public function close_appeal($id, $rid)
    {
        $record = Records::where('id', '=', $rid)->first();
        if(Auth::check() && Auth::user()->id == $record->user_id)
        {
            Records::changeRecordStatus($record->id, 4);
            return redirect(route('lk_list', ['id' => $id]));
        }

        return redirect(route('lk_list', ['id' => $id]));
    }

    public function view_appeal(Request $request, $id, $rid)
    {
        $node = Reg::where('id', '=', $id)->first();
        $this->getNodes();
        $data['nodes'] = $this->nodes;

        $this->title = 'Просмотр';
        $record = Records::where('id', '=', $rid)->first();

        if(Auth::check() && Auth::user()->id == $record->user_id)
        {
            $post_data = $request->all();
            if ($post_data)
            {
                if(isset($post_data['comment']))
                {
                    $this->validate($request, ['comment.text' => 'required|max:3000|min:2']);
                    Comments::saveComment($post_data['comment'], $record->id);
                    return redirect(route('view_appeal', ['id' => $id, 'rid' => $rid]));
                }

                if(isset($post_data['delete']))
                {
                    foreach($post_data['delete'] as $k => $v)
                        Comments::where([['id', '=', $k],['user_id', '=', Auth::user()->id]])->delete();
                }
            }

            $data['views'][] = view('/panel/lk/appeal/view', ['data' => $record]);

            $attrs = Attr::getRecordAttrs($rid);
            foreach($attrs as $attr)
            {
                if($attr->is_public)
                {
                    $template = $attr->type;
                    $attr->modificator ? $template .= '.'.$attr->modificator : null;
                    $data['views'][] = view('panel/records/attrs/view/'.$template, array('attr' => $attr, 'vls' => array()));
                }
            }

            $comments = Comments::getComments($rid);
            $data['views'][] = view('panel/lk/comments', ['record' => $record, 'data' => $comments, 'user_id' => Auth::user()->id, 'rid' => $rid]);
        }

        $data['title'] = $this->title;

        return view('/panel/lk', $data);
    }
}
