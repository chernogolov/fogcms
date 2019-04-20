<?php

namespace Chernogolov\Fogcms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Chernogolov\Fogcms\Controllers\PanelController;

use Chernogolov\Fogcms\Reg;
use Chernogolov\Fogcms\RegsUsers;
use Chernogolov\Fogcms\User;


class UsersController extends PanelController
{
    private $nodes = array();


    /**
     * Show the users.
     *
     * @return \Illuminate\Http\Response
     */

    public function getNodes()
    {
        $node = Reg::where('name', '=', 'root')->first();
        $root = Reg::where('name', '=', 'root')->first();
        $this->nodes = $root->descendantsAndSelf()->withoutNode($node)->get();
    }

    public function users(Request $request)
    {
        $this->title = __('All users');

        $post_data = $request->all();
        if ($post_data && isset($post_data['delete']))
            foreach($post_data['delete'] as $k => $v)
                User::where('id', '=', $k)->delete();

        $data['users'] = User::all();

        $views[] = view('fogcms::users/list', $data);
        return view('fogcms::users', array('views' => $views, 'title' => $this->title));
    }

    public function create(Request $request)
    {
        $this->title = __('New user');

        $data['users'] = User::all();
        $this->getNodes();

        $post_data = $request->all();
        if ($post_data)
        {

            if(isset($post_data['user']))
            {
                $this->validate($request, [
                    'user.name' => 'min:2|required|string|max:255',
                    'user.email' => 'required|string|email|max:255|unique:users,email',
                    'user.description' => 'max:190',
                    'user.password' => 'required|string|min:4|confirmed',
                ]);
                $res = User::create([
                    'name' => $post_data['user']['name'],
                    'email' => $post_data['user']['email'],
                    'phone' => $post_data['user']['phone'],
                    'description' => $post_data['user']['description'],
                    'password' => bcrypt($post_data['user']['password']),
                ]);
                if (isset($post_data['regs']))
                {
                    foreach($post_data['regs'] as $key => $reg)
                    {
                        $insertdata = array('reg_id' => $key, 'user_id' => intval($res->id));
                        $insertdata = array_merge($insertdata, $reg);

                        if(!RegsUsers::where([['reg_id', '=', $key],['user_id', '=', $res->id]])->update($insertdata))
                            RegsUsers::create($insertdata);
                    }
                }
            }


            $this->title = __('Saved') . ': ';
        }

        $views[] = view('fogcms::users/new', array('nodes' => $this->nodes));
        return view('fogcms::users', array('views' => $views, 'title' => $this->title));
    }


    public function edit(Request $request, $id)
    {
        $this->title = __('Edit user') . ' ';
        $this->getNodes($id);
        $data['nodes'] = $this->nodes;

        $post_data = $request->all();
        if ($post_data)
        {
            if (isset($post_data['user']))
            {
                if($post_data['user']['password'] != '' && $post_data['user']['password_confirmation'] !='')
                    $this->validate($request, ['user.name' => 'min:3|required|string|max:255','user.description' => 'max:190','user.password' => 'required|string|min:4|confirmed']);
                else
                    $this->validate($request, ['user.name' => 'min:3|required|string|max:255','user.description' => 'max:190',]);

                $update['name'] = $post_data['user']['name'];
                $update['phone'] = $post_data['user']['phone'];
                $update['description'] = $post_data['user']['description'];
                $post_data['user']['password'] != '' ? $update['password'] = bcrypt($post_data['user']['password']) : null;
                User::where('id', '=', $id)->update($update);
                $this->title = __('Saved');
                $post_data['user']['password'] != '' ? $this->title .= ', ' . __('Password changed') : null;
            }
            if (isset($post_data['regs']))
            {
                foreach($post_data['regs'] as $key => $reg)
                {
                    $insertdata = array('reg_id' => $key, 'user_id' => intval($id));
                    $insertdata = array_merge($insertdata, $reg);

                    if(!RegsUsers::where([['reg_id', '=', $key],['user_id', '=', $id]])->update($insertdata))
                        RegsUsers::create($insertdata);

                }
            }
        }

        $data['user'] = User::where('id', '=', $id)->first();
        $data['access'] = RegsUsers::where('user_id', '=', $id)->get()->keyBy('reg_id');

        $this->title .= $data['user']['name'];

        $views[] = view('fogcms::users/edit', $data);
        return view('fogcms::users', array('views' => $views, 'title' => $this->title));
    }


    public function regs()
    {
        $this->title = __('Journals access');
        $views = array();
        return view('fogcms::users', array('views' => $views, 'title' => $this->title));
    }

    public function blocking()
    {
        $this->title = __('IP blocking');
        $views = array();
        return view('fogcms::users', array('views' => $views, 'title' => $this->title));
    }

    public function searchUser(Request $request)
    {
        $post_data = $request->all();
        if ($post_data && isset($post_data['text']))
        {
            $users = DB::table('users')
                ->where('name', 'LIKE', '%' . $post_data['text'] . '%')
                ->orWhere('email', 'LIKE', '%' . $post_data['text'] . '%')
                ->orWhere('phone', 'LIKE', '%' . $post_data['text'] . '%')
                ->get();
//            $sphinx = new SphinxSearch();
//            $results = $sphinx->search($post_data['text'], 'searchUser')->get();
//            dd($sphinx);
//            return $results;

            return view('fogcms::search/results', ['users' => $users]);
        }

        return view('fogcms::search');


    }
}
