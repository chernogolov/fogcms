<?php

namespace Chernogolov\Fogcms\Controllers\Lk;

use Chernogolov\Fogcms\Jobs\NewTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

use Chernogolov\Fogcms\Controllers\LkController;
use Chernogolov\Fogcms\Notifications\AddRecord;

use Chernogolov\Fogcms\Records;
use Chernogolov\Fogcms\Attr;
use Chernogolov\Fogcms\Reg;
use Chernogolov\Fogcms\User;
use Chernogolov\Fogcms\RegsUsers;
use Chernogolov\Fogcms\Comments;


class SupportController extends LkController
{
    public $title;

    public function __construct()
    {
        parent::__construct();
    }

    public function supportCenter(Request $request)
    {
        $this->getAccounts();

        $this->data['views'][] = $this->getTickets($request, true);
        $this->data['views'][] = $this->documentsList($request, true);
        $this->data['views'][] = $this->qA($request, true);

        $this->title = __('Support center');

        return $this->index();
    }

    public function newTicket(Request $request, $from_rid = null, $view = false)
    {
        !$view ? $this->getAccounts() : null;
        $validate_attr = [];
        $additional = [];
        $sender = '';
        $subject = __('New ticket ');

        $this->title = __('Create ');

        if($from_rid)
        {
            $this->title = __('Reapplication');
            $record = Records::getRecord($from_rid);
        }

        $attrs = Attr::getRegsAttrs($this->tickets_reg_id, $from_rid);
        $v = view('fogcms::lk/pages/new');

        foreach ($attrs as $attr)
        {
            if ($attr->is_public && $attr->modificator != 'deadline')
                if ($attr->is_required)
                    $validate_attr['attr.' . $attr->name . '.value'] = 'required';

            $accounts = implode(',', $this->accounts->pluck('id')->toArray());
            if($attr->name == 'account_number')
                $validate_attr['attr.' . $attr->name . '.value'] .= '|min:3|in:'.$accounts;
        }
        $post_data = $request->all();
        if ($post_data) {
            $this->validate($request, $validate_attr);
            $regs = Records::getDestinationNodes($attrs, $post_data['attr']);

            if ($from_rid) {
                foreach ($post_data['attr'] as $k => $item) {
                    unset($post_data['attr'][$k]['id']);
                    unset($post_data['attr'][$k]['save']);
                }
            }

            try {
                $rid = Records::addRecord($this->tickets_reg_id, $post_data['attr'], $additional);
            } catch (ValidateException $errors) {
                file_get_contents('https://api.telegram.org/bot608599411:AAFPZIybZ-O9-t4y_rlRxmtQV4i-sV8aF6c/sendMessage?chat_id=293756888&text=UG-ENERGO ID: ' . $rid . ' ' . implode(',', $this->tickets_reg_id));
            }

            if (!isset($rid))
                file_get_contents('https://api.telegram.org/bot608599411:AAFPZIybZ-O9-t4y_rlRxmtQV4i-sV8aF6c/sendMessage?chat_id=293756888&text=UG-ENERGO ID: ' . $rid . ' ' . implode(',', $this->tickets_reg_id));

            if ($rid) {
                $subject .= '#' . $rid;

                if (isset($post_data['attr']['address']['value'])) {
                    $address = Records::getRecord($post_data['attr']['address']['value']);
                    isset($address['fulladdress']) ? $sender .= ' ' . $address['fulladdress'] : null;
                }

                if (isset($post_data['attr']['territory']['value'])) {
                    $address = Records::getRecord($post_data['attr']['territory']['value']);
                    isset($address['type']) ? $sender .= ' ' . $address['type'] : null;
                }


                $users = User::whereIn('id', RegsUsers::getSendUsers($this->tickets_reg_id))->get();
                foreach ($users as $user) {
                    try {
                        Notification::send($user, new AddRecord($this->tickets_reg_id, $rid, $sender));
                    } catch (\Exception $e) {
                        file_get_contents('https://api.telegram.org/bot608599411:AAFPZIybZ-O9-t4y_rlRxmtQV4i-sV8aF6c/sendMessage?chat_id=293756888&text=gkh2 send ' . $user->email . ' notification error ' . $e);
                    }
                }

                $job = (new NewTicket(Records::getRecord($rid)));
                dispatch($job);
            }

            return redirect(route('tickets'));
        }

        foreach ($attrs as $attr) {
            if ($attr->is_public) {
                if($from_rid)
                    $attr->mode = 'new';
                $attr->modificator ? $template = $attr->type . '.' . $attr->modificator : $template = $attr->type;
                $v .= view('fogcms::lk/attrs/' . $template, array('attr' => $attr, 'accounts' => $this->accounts, 'current_account' => $this->current_account));
            }
        }

        $this->data['user'] = Auth::user();
        if($from_rid)
        {
            $this->data['user'] = User::where('id', '=', $record['user_id'])->first();
        }

        $v .= view('fogcms::lk/pages/user', $this->data);
        $v .= view('fogcms::lk/pages/endform');

        if($request->ajax() || $view)
            return $v;
        else
        {
            $this->data['views'][] = $v;
            return $this->index();
        }
    }

    public function getTickets(Request $request, $view = false)
    {
        $this->title = __('Tickets');

        $node = Reg::where('id', '=', $this->tickets_reg_id)->first();

        $attrs = Attr::getFields($node, ['entry']);
        $records = Records::getRecords(null, array('user_id' => Auth::user()->id, 'type' => 'tickets', 'fields' => $attrs['fields']));
        $this->data['title'] = $this->title;

        if($request->ajax() || $view)
            return view('fogcms::lk/pages/tickets', ['records' => $records, 'node' => $node]);
        else
        {
            $this->data['views'][] = view('fogcms::lk/pages/tickets', ['records' => $records, 'node' => $node]);
            return $this->index();
        }
    }

    public function closeTicket($id)
    {
        $record = Records::where('id', '=', $id)->first();
        if(Auth::check() && Auth::user()->id == $record->user_id)
        {
            Records::changeRecordStatus($record->id, 4);
            return redirect(route('tickets'));
        }

        return redirect(route('tickets'));
    }

    public function viewTicket(Request $request, $id)
    {
        $this->title = __('View');
        $record = (object)Records::getRecord($id);
        $post_data = $request->all();
        if(isset($post_data['delete']))
        {
            foreach($post_data['delete'] as $k => $v)
                Comments::deleteComment($k);
        }



//        $post_data = $request->all();
//        if ($post_data)
//        {
//            if(isset($post_data['comment']))
//            {
//                $this->validate($request, ['comment.text' => 'required|max:3000|min:2']);
//                Comments::saveComment($post_data['comment'], $record->id);
//                return redirect(route('view_appeal', ['id' => $id, 'rid' => $id]));
//            }
//
//            if(isset($post_data['delete']))
//            {
//                foreach($post_data['delete'] as $k => $v)
//                    Comments::where([['id', '=', $k],['user_id', '=', Auth::user()->id]])->delete();
//            }
//        }

        $this->data['views'][] = view('fogcms::lk/pages/view_ticket', ['data' => $record]);

        $attrs = Attr::getRecordAttrs($id);
        foreach($attrs as $attr)
        {
            if($attr->is_public)
            {
                $template = $attr->type;
                $attr->modificator ? $template .= '.'.$attr->modificator : null;
                $this->data['views'][] = view('fogcms::lk/attrs//view/'.$template, array('attr' => $attr, 'vls' => array()));
            }
        }

        $comments = Comments::getComments($id);
        $this->data['views'][] = view('fogcms::lk/comments', ['record' => $record, 'data' => $comments, 'user_id' => Auth::user()->id, 'rid' => $id]);
        $this->data['views'][] = view('fogcms::lk/pages/endview');

        $data['title'] = $this->title;

        return $this->index();
    }

    public function addFeedback(Request $request, $id)
    {
        $this->validate($request, ['comment.text' => 'required|max:3000|min:2']);

        $post_data = $request->all();
        if (isset($post_data['comment']))
            Comments::saveComment($post_data['comment'], $id);

        return redirect()->route('view-ticket', ['id' => $id]);
    }

    public function qA(Request $request, $view = false)
    {

        $d = [];
        $this->title = __('Qa');
        $params = [];
        $params['orderBy'] = [
            'type' => 'ASC',
            'field' => 'Rating'
        ];

        $d['data'] = Records::getRecords($this->qa_reg_id, $params)->groupBy('entry');

        if($request->ajax() || $view)
            return view('fogcms::lk/pages/qa_themes', $d);
        else
        {
            $this->data['views'][] = view('fogcms::lk/pages/qa', $d);
            return $this->index();
        }
    }

    public function contactList(Request $request)
    {
        $this->getAccounts();
        $this->title = __('Contacts');
        $params = [];
        $params['filters']['multiaddress']['whereIn'][] = null;
        if(!empty($this->current_account)) {
            foreach($this->accounts as $acc)
            {
                $params['filters']['multiaddress']['whereIn'][] = $acc->address_rid;
            }
        }

        $params['orderBy'] = ['field' => 'rating', 'type' => 'ASC'];
        $contacts = Records::getRecords($this->contacts_reg_id, $params)->all();

        $this->data['views'][] = view('fogcms::lk/pages/contacts_list', ['contacts' => $contacts]);
        return $this->index($request, $this->data);
    }

    public function contactItem(Request $request, $id)
    {
        $item = Records::getRecord($id);

        isset($item['title']) ? $this->title = $item['title'] : $this->title = __('Contact');

        $this->data['views'][] = view('fogcms::lk/pages/contact_item', ['item' => $item]);
        return $this->index($request, $this->data);
    }

    public function documentsList(Request $request, $view = false)
    {
        $this->getAccounts();
        $this->title = __('Documents');

        $params = [];
        $params['filters']['multiaddress']['whereIn'][] = null;
        if(!empty($this->current_account)) {
            foreach($this->accounts as $acc)
            {
                $params['filters']['multiaddress']['whereIn'][] = $acc->address_rid;
            }
        }
        $params['orderBy'] = [
            'attr' => 'Date',
            'type' => 'DECS',
            'field' => 'value'
        ];
        $documents = Records::getRecords($this->documents_reg_id, $params)->all();

        if($request->ajax() || $view)
            return view('fogcms::lk/pages/documents_list', ['documents' => $documents]);
        else
        {
            $this->data['views'][] = view('fogcms::lk/pages/documents_list', ['documents' => $documents]);
            return $this->index();
        }
    }
}