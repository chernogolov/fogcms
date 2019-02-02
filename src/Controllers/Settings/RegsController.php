<?php

namespace Chernogolov\Fogcms\Controllers\Settings;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use Chernogolov\Fogcms\Controllers\SettingsController;
use Chernogolov\Fogcms\Controllers\ImageController;

use Chernogolov\Fogcms\Reg;
use Chernogolov\Fogcms\Attr;


class RegsController extends SettingsController
{
    private $basic_route = 'reglist';

    //list registers
    public function reglist(Request  $request)
    {
        $post_data = $request->all();
        if ($post_data)
        {
            if(isset($post_data['delete']))
                foreach($post_data['delete'] as $id)
                    Reg::where('id', '=', $id)->first()->delete();

//            isset($post_data['moveleft']) ? $node = Reg::where('id', '=', $post_data['moveleft']) : null;

            isset($post_data['moveleft']) ? Reg::where('id', '=', $post_data['moveleft'])->first()->moveLeft() : null;
            isset($post_data['moveright']) ? Reg::where('id', '=', $post_data['moveright'])->first()->moveRight(): null;
            return redirect()->route('reglist');
        }

        $data['nodes'] = Reg::all();
        $this->title .= __('All journals');
        $this->views[] = view('fogcms::settings/regs', $data);
        return view('fogcms::settings', array('views' => $this->views, 'title' => $this->title, 'route' => $this->basic_route));
    }

    //edit register
    public function edit(Request  $request, $id)
    {
        $this->title .= __('Edit') . ': ';

        $data['node'] = Reg::where('id', '=', $id)->first();

        $post_data = $request->all();
        if ($post_data)
        {
            isset($post_data['attr']) ? Attr::addRegsAttrs($id, $post_data['attr']) : null;
            isset($post_data['delete']) ? Attr::deleteRegsAttrs($id, $post_data['delete']) : null;
            isset($post_data['attrs']) ?  Attr::updateRegsAttrs($post_data['attrs']) : null;
            isset($post_data['default_fields']['view']) ? Reg::where('id', '=', $id)->update((array) ['default_fields' => implode(',', array_keys($post_data['default_fields']['view']))]) : Reg::where('id', '=', $id)->update((array) ['default_fields' => '']);
            isset($post_data['default_fields']['public']) ? Reg::where('id', '=', $id)->update((array) ['public_fields' => implode(',', array_keys($post_data['default_fields']['public']))]) : Reg::where('id', '=', $id)->update((array) ['public_fields' => '']);

            if(isset($post_data['reg']))
            {
                foreach($post_data['reg'] as $item => $value)
                {
                    switch($item){
                        case 'image':$data['node']->$item = ImageController::uploadImage($request->file('reg')['image'], 32, 32);break;
                        case 'print_template': $data['node']->$item = str_replace('public/', '/storage/' ,Storage::putFile('/public/' . date('Y-m'), $request->file('reg')['print_template']));break;
                        default:$data['node']->$item = $value;
                    }
                }
                $data['node']->save();
                $this->title = __('Saved') . ': ';
            }

            if(isset($post_data['copy_attrs']) && isset($post_data['copy']['from']) && $post_data['copy']['from']!=null)
            {
                $regs_attrs = Attr::getRegsAttrs(intval($post_data['copy']['from']));

                foreach($regs_attrs as $attr )
                {
                    $new_id = Attr::addRegsAttrs($id, $attr->attr_id, (array) $attr);
                }
            }
        }

        $data['nodes'] = Reg::all();
        $data['default_fields'] = Config::get('fogcms.default_fields');
        $data['attrs'] = Attr::all();
        $data['regs_attrs'] = Attr::getRegsAttrs($id);

        $this->title .= $data['node']->name;
        $views[] = view('fogcms::settings/reg', $data);
        return view('fogcms::settings', array('views' => $views, 'title' => $this->title, 'route' => $this->basic_route));
    }

    //new register
    public function create(Request  $request)
    {
        $this->title .= __('New journal');
        $data['nodes'] = Reg::all();

        $post_data = $request->all();
        if (isset($post_data['reg']))
        {
            $parent = $post_data['reg']['parent'];
            unset($post_data['reg']['parent']);

            foreach($post_data['reg'] as $item => $value)
                $item == 'image' ? $post_data['reg']['image'] = ImageController::uploadImage($request->file('reg')['image'], 32, 32) : null;

            $child2 = Reg::create($post_data['reg']);
            $child2->makeChildOf($parent);
            $this->title = __('Saved') . ': ';
        }
        $views[] = view('fogcms::settings/reg', $data);
        return view('fogcms::settings', array('views' => $views, 'title' => $this->title, 'route' => $this->basic_route));
    }
}
