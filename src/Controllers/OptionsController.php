<?php

namespace Chernogolov\Fogcms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

use Chernogolov\Fogcms\Controllers\PanelController;

use App\Options;
use App\User;


class OptionsController extends PanelController
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

    public function notify_regs(Request  $request)
    {
        $data = [];
        $post_data = $request->all();
        $title = 'Настройка уведомлений';

        $views[] = view('panel/options/notify_regs', $data);
        return view('panel/options', array('views' => $views, 'title' => $title));
    }

    public function account(Request  $request)
    {
        $post_data = $request->all();
        $title = __('Edit account');

        if ($post_data)
        {
            if (isset($post_data['user']))
            {
                if($post_data['user']['password'] != '' && $post_data['user']['password_confirmation'] !='')
                    $this->validate($request, ['user.name' => 'min:3|required|string|max:255','user.description' => 'max:190','user.password' => 'required|string|min:6|confirmed', 'user.phone' => 'min:6|numeric|max:15']);
                else
                    $this->validate($request, ['user.name' => 'min:3|required|string|max:255','user.description' => 'max:190', 'user.phone' => 'numeric']);

                $update['name'] = $post_data['user']['name'];
                $update['phone'] = $post_data['user']['phone'];
                $update['description'] = $post_data['user']['description'];
                $post_data['user']['password'] != '' ? $update['password'] = bcrypt($post_data['user']['password']) : null;
                User::where('id', '=', Auth::user()->id)->update($update);
                $this->title = __('Saved');
                $post_data['user']['password'] != '' ? $this->title .= __('Password saved') : null;

                $title = __('Account saved') . ' ';
                if($post_data['user']['password'] != '' && $post_data['user']['password_confirmation'] !='')
                    $title .= __('Password saved');
            }
        }

        $data['user'] = User::where('id', '=', Auth::user()->id)->first();

        $views[] = view('fogcms::users/edit', $data);
        return view('fogcms::options', array('views' => $views, 'title' => $title));
    }
}
