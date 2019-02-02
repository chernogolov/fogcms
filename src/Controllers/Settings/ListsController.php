<?php

namespace Chernogolov\Fogcms\Controllers\Settings;

use Illuminate\Http\Request;

use Chernogolov\Fogcms\Controllers\SettingsController;

use Chernogolov\Fogcms\Lists;
use Chernogolov\Fogcms\Attr;

class ListsController extends SettingsController
{
    public $title;
    private $basic_route = 'lists';

    //get all lists
    public function lists(Request  $request)
    {
        $this->title .= __('All lists');

        $post_data = $request->all();
        isset($post_data['clear']) ? Lists::clearList($post_data['clear']) : null;

        $data['lists'] = Lists::getLists();
        $this->views[] = view('fogcms::settings/lists', $data);
        return view('fogcms::settings', array('views' => $this->views, 'title' => $this->title));
    }

    //edit list
    public function edit(Request  $request, $id)
    {

        $this->title .= __('List') . ': ';

        $post_data = $request->all();
        if ($post_data)
        {
            isset($post_data['clear']) ? Lists::deleteListItems($post_data['clear']) : null;
            isset($post_data['add_values']) ? Lists::addValues($id, $post_data['values']) : null;
            isset($post_data['save_list']) ? Lists::saveValues($post_data['list']) : null;

            return redirect()->route('editlist', ['id' => $id]);
        }

        $data = [];
        $list = Attr::getAttrsById($id);

        $this->title .= $list->title;
        $data['list'] = Lists::getList($id);

        $this->views[] = view('fogcms::settings/list', $data);
        return view('fogcms::settings', array('views' => $this->views, 'title' => $this->title));
    }
}
