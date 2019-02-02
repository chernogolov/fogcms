<?php

namespace Chernogolov\Fogcms\Controllers\Settings;

use Chernogolov\Fogcms\Controllers\SettingsController;
use Illuminate\Http\Request;
use Chernogolov\Fogcms\Attr;

class AttrController extends SettingsController
{
    public  $title;
    private $basic_route = 'attrlist';

    //show all attributes
    public function attrlist(Request  $request)
    {
        $this->title .= __('All attributes');

        $post_data = $request->all();
            if(isset($post_data['delete']))
                Attr::deleteAttr($post_data['delete']);

        $data['attrs'] = Attr::all();
        $this->views[] = view('fogcms::settings/attrs', $data);
        return view('fogcms::settings', array('views' => $this->views, 'title' => $this->title, 'route' => $this->basic_route));
    }

    //create a new attributes
    public function create(Request  $request)
    {
        $this->title .= __('New attribute');
        $post_data = $request->all();
        isset($post_data['attr']) ? Attr::createAttr($post_data['attr']) : null;

        $this->views[] = view('fogcms::settings/attr');
        return view('fogcms::settings', array('views' => $this->views, 'title' => $this->title, 'route' => $this->basic_route));
    }

    //edit attributes
    public function edit(Request  $request, $id)
    {
        $this->title .= __('Edit') . ': ';

        $post_data = $request->all();
        isset($post_data['attr']) ? Attr::updateAttr($id, $post_data['attr']) : null;

        $data['attr'] = Attr::where('id', '=', $id)->first();
        $this->views[] = view('fogcms::settings/attr', $data);
        return view('fogcms::settings', array('views' => $this->views, 'title' => $this->title, 'route' => $this->basic_route));
    }

}
