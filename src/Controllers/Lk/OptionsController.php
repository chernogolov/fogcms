<?php

namespace Chernogolov\Fogcms\Controllers\Lk;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use Chernogolov\Fogcms\Controllers\ImageController;
use Chernogolov\Fogcms\Controllers\LkController;

use Chernogolov\Fogcms\Options;
use Chernogolov\Fogcms\User;
use Chernogolov\Fogcms\Records;
use Chernogolov\Fogcms\RegsUsers;
use Chernogolov\Fogcms\Jobs\LoadErcData;
use Illuminate\Support\Facades\Session;

use Chernogolov\Fogcms\Reg;


class OptionsController
{
    /**
     * Show the options.
     *
     * @return \Illuminate\Http\Response
     */

    public function getdata(Request  $request, $group = null)
    {

        $post_data = $request->all();
        isset($post_data['options']) ? Options::saveOptions($group, $post_data['options']) : null;

        $default_options = Config::get('user.'.$group);
        $options = Options::getOptions($group);

        foreach($options as $option)
            if(isset($default_options[$option->name]))
                $default_options[$option->name]['value'] = $option->value;

        return view('fogcms::options', ['options' => $default_options]);
    }

    public function getNodes()
    {
        $root = Reg::where('name', '=', 'root')->first();
        $node = $root;
        $this->nodes = $root->descendantsAndSelf()->withoutNode($node)->where('is_public', '=', 1)->get();
    }

    public function notifications(Request  $request)
    {

        $post_data = $request->all();
        $data['title'] = __('Edit notifications');
        $data['views'] = [];

        if ($post_data)
        {
            if(isset($post_data['regs']))
            {
                foreach($post_data['regs'] as $key => $reg)
                {
                    $insertdata = array('reg_id' => $key, 'user_id' => intval(Auth::user()->id));
                    $insertdata = array_merge($insertdata, $reg);

                    if(!RegsUsers::where([['reg_id', '=', $key],['user_id', '=', Auth::user()->id]])->update($insertdata))
                        RegsUsers::create($insertdata);

                }
            }
        }

        //get useravailable regs
        $this->getNodes();
        $data['nodes'] = $this->nodes;
        $data['records'] = Records::getRecords(Config::get('fogcms.accounts_reg_id'), ['added_user' => Auth::user()->id]);
        $data['access'] = RegsUsers::where('user_id', '=', Auth::user()->id)->get()->keyBy('reg_id');
        $data['user'] = User::where('id', '=', Auth::user()->id)->first();

        $data['sidebar'][] = view('fogcms::lk/user/sidebar');
        $data['views'][] = view('fogcms::lk/user/notifications', $data);

        return view('fogcms::lk', $data);


    }

    public function profile(Request  $request)
    {
        $post_data = $request->all();
        $data['title'] = __('Edit profile');

        if($post_data)
        {
            if (isset($post_data['user']))
            {
                $this->validate($request, [
                    'user.name' => 'min:3|required|string|max:255',
                    'user.description' => 'max:190',
                    'user.image' => 'mimes:jpeg,png|max:10240',
                    ]
                );

                if(isset($post_data['user']['image']) && $request->file('user')['image']->getSize()>0)
                    $update['image'] = ImageController::uploadImage($request->file('user')['image'], 250, 250, true);

                $update['name'] = $post_data['user']['name'];
                $update['phone'] = $post_data['user']['phone'];
                $update['description'] = $post_data['user']['description'];

                User::where('id', '=', Auth::user()->id)->update($update);

                $data['title'] = __('Profile saved') . ' ';
            }
        }

        $data['user'] = User::where('id', '=', Auth::user()->id)->first();
        $data['sidebar'][] = view('fogcms::lk/user/sidebar');
        $data['views'][] = view('fogcms::lk/user/profile', $data);

        return view('fogcms::lk', $data);
    }

    public function accounts(Request  $request)
    {
        $post_data = $request->all();
        $data['title'] = __('Edit accounts');
        $data['views'] = [];

        if ($post_data)
        {
            if (isset($post_data['user'])) {
                $this->validate($request, [
                        'user.account_number' => 'min:3|required|string|max:255',
                        'user.pin' => 'min:3|required|string|max:255',
                    ]
                );
                $params = [
                    'filters' => [
                        'account_number' => [['account_number','=',$post_data['user']['account_number']]],
                        'pin' => [['pin','=',$post_data['user']['pin']]],
                    ]
                ];

                $result = Records::getRecords(Config::get('fogcms.accounts_reg_id'), $params);
                if($result->count()==0)
                    $data['user_errors'][] = __('Such a pair of account id - PIN not found');
                else
                    $data['user_messages'][] = __('Account id succesfuly added');

                foreach($result as $r)
                {
                    Records::addRecordUser($r->id, Auth::user()->id);
                    $job = (new LoadErcData($r));
                    dispatch($job);
                }
            }

            if(isset($post_data['remove-account']))
                if(Records::deleteRecordUser(intval($post_data['remove-account']), Auth::user()->id))
                {
                    Session::forget('ca' . Auth::user()->id);
                    $data['user_messages'][] = __('Account id succesfuly deleted');
                }
        }

        $data['records'] = Records::getRecords(Config::get('fogcms.accounts_reg_id'), ['added_user' => Auth::user()->id]);
        $data['user'] = User::where('id', '=', Auth::user()->id)->first();

        $data['sidebar'][] = view('fogcms::lk/user/sidebar');
        $data['views'][] = view('fogcms::lk/user/accounts', $data);

        return view('fogcms::lk', $data);
    }

    public function password(Request  $request)
    {
        $post_data = $request->all();
        $data['title'] = __('Edit password');
        $data['views'] = [];

        if ($post_data)
        {
            if (isset($post_data['user']))
            {
                $this->validate($request, ['user.password' => 'required|string|min:4|confirmed']);

                $post_data['user']['password'] != '' ? $update['password'] = bcrypt($post_data['user']['password']) : null;
                User::where('id', '=', Auth::user()->id)->update($update);
                $data['title'] = __('Password saved');
            }
        }

        $data['user'] = User::where('id', '=', Auth::user()->id)->first();

        $data['sidebar'][] = view('fogcms::lk/user/sidebar');
        $data['views'][] = view('fogcms::lk/user/password', $data);

        return view('fogcms::lk', $data);
    }
}
