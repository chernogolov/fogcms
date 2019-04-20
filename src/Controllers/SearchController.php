<?php

namespace Chernogolov\Fogcms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Chernogolov\Fogcms\Attr;
use Chernogolov\Fogcms\Records;
use Chernogolov\Fogcms\Reg;
use Chernogolov\Fogcms\Search;

class SearchController extends Controller
{

    public $node;
    //
    public function search(Request $request, $id)
    {
        $this->node = Reg::where('id', '=', $id)->first();
        $data['records'] = [];
        $data['id'] = $id;
        //get params

        $params = Attr::getFields($this->node);
        $placeholder = [];

        $public_fields = array_flip(explode(',', $this->node->public_fields));
        if(is_array($public_fields) && !empty($public_fields))
            $s_attrs = array_intersect_key($params['default_fields'], $public_fields);

        foreach($params['fields'] as $item)
            if($item->type == 'chars' && isset($item->is_public) && $item->is_public)
            {
                $s_attrs[$item->name] = $item;
                $s_attrs2[$item->name] = $item;
                $placeholder[] = $item->title;
            }

        $post_data = $request->all();
        $params['fields'] = array_intersect_key($params['fields'], $s_attrs);
        $limit = 20;
        if(isset($post_data['limit']))
            $limit = intval($post_data['limit']);

        foreach($params['fields'] as $k => $f)
        {
            $params['orderBy']['field'] = $k;
            $params['orderBy']['type'] = 'ASC';
        }

        if(isset($post_data['text']))
        {
            $include = array_keys(Search::searchByAttrs($post_data['text'], $s_attrs2));
            $params['include'] = $include;
            $params['limit'] = $limit;
            $records = Records::getRecords($id, $params)->keyBy('id');
            foreach($include as $k)
                isset($records[$k]) ? $data['records'][$k] = $records[$k] : null;

            $data['attrs'] = array_keys($s_attrs);
            return view('fogcms::search/result', $data);
        }
        else
        {
            $params['limit'] = $limit;
            $data['records'] = Records::getRecords($id, $params);
            if($data['records']->total() <= $params['limit'])
            {
                $data['records'] = $data['records']->keyBy('id');
                $data['attrs'] = array_keys($s_attrs);
                return view('fogcms::search/result', $data);
            }
        }
    }
}
