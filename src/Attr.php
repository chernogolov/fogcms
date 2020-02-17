<?php

namespace Chernogolov\Fogcms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

use Chernogolov\Fogcms\Controllers\ImageController;
use Chernogolov\Fogcms\Lists;



class Attr extends Model
{

    public static $import = false;

    public static function getFields($node, $fieldset = null)
    {
        empty($attrs) ? $attrs = collect(Attr::getRegsAttrs($node->id))->keyBy('name') : $attrs = collect($attrs)->keyBy('name');

        //if set default fields set
        $f = Config::get('fogcms.default_fields');
        foreach ($f as $fkey => $fitem)
            $params['default_fields'][$fkey] = (object)$fitem;


        if($node->default_fields != '')
            $params['fields'] = array_intersect_key($params['default_fields'], array_flip(explode(',', $node->default_fields)));

        foreach($attrs as $attr)
        {
            $template = $attr->type;

            $attr->modificator ? $template .= '.'.$attr->modificator : null;

            $params['default_fields'][$attr->name] = $attr;
            $params['default_fields'][$attr->name]->filter_template = 'fogcms::records/attrs/filter/'.$template;

            if($attr->is_filter == 1)
                $params['fields'][$attr->name] = $params['default_fields'][$attr->name];
        }

        if(!isset($params['fields']))
            $params['fields']['id'] = $params['default_fields']['id'];

        return $params;

    }

    public static function addRegsAttrs($reg_id, $attr_ids, $data = array())
    {
        $fields = ['count_values', 'is_filter', 'is_public', 'ordering', 'is_required', 'datalist'];

        if(!is_array($attr_ids))
            $attr_ids = array($attr_ids => '');

        if(empty($data))
            $data = ['count_values' => 0, 'is_filter' => 0, 'is_public' => 0];
        else
            foreach($data as $key => $value)
                if(!in_array($key, $fields))
                    unset($data[$key]);

        foreach($attr_ids as $attr_id => $v)
        {
            $result = DB::table('attrs_regs')->where([['attrs_regs.regs_id', '=', $reg_id], ['attrs_regs.attr_id', '=', $attr_id]])->first();
            if(empty($result))
                $id = DB::table('attrs_regs')->insertGetId(array_merge(['attr_id' => $attr_id, 'regs_id' => $reg_id], $data));
        }
    }

    public static function getRegsAttrs($reg_id, $rid = null)
    {

        $attrs = DB::table('attrs')
            ->join('attrs_regs', 'attrs.id', '=', 'attrs_regs.attr_id')
            ->where('attrs_regs.regs_id', '=', $reg_id)
            ->select(['attrs_regs.*', 'attrs.type', 'attrs.name', 'attrs.title', 'attrs.modificator', 'attrs.meta'])
            ->orderBy('ordering')
            ->get();

        $data = array();


        foreach($attrs as $k => $v)
        {
            $function_name = 'get'.ucfirst($v->type).'Data';
            $v->data = Self::$function_name($v);
            if($rid)
            {
                $function_name = 'get'.ucfirst($v->type).'Values';
                $v->values = Self::$function_name($v, $rid);
            }

            $data[$k] = $v;
        }

        return $data;
    }

    public static function getRecordAttrs($rid, $keyBy = 'attr_id')
    {
        $regs = Records::getRecordRegs($rid)->get();

        $attrs = [];
        foreach($regs as $reg)
        {
            $attrs[] = DB::table('attrs')
                ->join('attrs_regs', 'attrs.id', '=', 'attrs_regs.attr_id')
                ->where('attrs_regs.regs_id', '=', $reg->id)
                ->select(['attrs_regs.*', 'attrs.type', 'attrs.name', 'attrs.title', 'attrs.modificator', 'attrs.meta'])
                ->orderBy('ordering')
                ->get();
        }

        $attrs = collect($attrs)->flatten()->keyBy($keyBy);
        $data = array();
        foreach($attrs as $k => $v)
        {
            $function_name = 'get'.ucfirst($v->type).'Data';
            $v->data = Self::$function_name($v);
            if($rid)
            {
                $function_name = 'get'.ucfirst($v->type).'Values';
                $v->values = Self::$function_name($v, $rid);
            }

            $data[$k] = $v;
        }
        return $data;
    }

    public static function getRegAttr($attr_id, $reg_id = null)
    {
        $where[] = ['attrs.id', '=', $attr_id];

        if($reg_id)
            $where[] = ['attrs_regs.regs_id', '=', $reg_id];

        $data = DB::table('attrs')
            ->join('attrs_regs', 'attrs.id', '=', 'attrs_regs.attr_id')
            ->where($where)
            ->select(['attrs_regs.*', 'attrs.type', 'attrs.name', 'attrs.title', 'attrs.modificator'])
            ->first();
        return $data;
    }

    public static function deleteRegsAttrs($reg_id, $attr_ids = array())
    {
        foreach($attr_ids as $attr_id => $v)
        {
            DB::table('attrs_regs')->where('id', '=', $attr_id)->delete();
        }
    }

    public static function createAttr($data)
    {
        //indert record data
        $id = DB::table('attrs')->insertGetId(
            $data
        );
    }

    public static function updateRegsAttrs($data)
    {
        foreach($data as $key => $values)
        {
            $r = DB::table('attrs_regs')
                ->where('id', $key)
                ->update((array) $values);

        }
    }

    public static function deleteAttr($attr_ids = array())
    {
        foreach($attr_ids as $attr_id => $v)
        {
            DB::table('attrs')->where('id', '=', $attr_id)->delete();
        }
    }

    public static function updateAttr($id, $data)
    {
            DB::table('attrs')
                ->where('id', $id)
                ->update($data);
    }

    public static function getAttrsByType($type)
    {
        return DB::table('attrs')->where('type', '=', $type)->get();
    }

    public static function getAttrByName($name)
    {
        return DB::table('attrs')->where('name', '=', $name)->first();
    }

    public static function getRegsAttrByName($name)
    {
        $data = DB::table('attrs')
            ->join('attrs_regs', 'attrs.id', '=', 'attrs_regs.attr_id')
            ->where('attrs.name', '=', $name)
            ->select(['attrs_regs.*', 'attrs.type', 'attrs.name', 'attrs.title', 'attrs.modificator'])
            ->first();

        return $data;
    }

    public static function getAttrsById($id)
    {
        if(is_array($id))
        {
            $attrs = DB::table('attrs')->whereIn('id', $id)->get();
            foreach($attrs as $k => $v)
            {
                $function_name = 'get'.ucfirst($v->type).'Data';
                if(!isset($v->attr_id))
                    $v->attr_id = $v->id;
                $v->data = Self::$function_name($v);
                $data[$k] = $v;
            }
        }
        else
        {
            $attrs = DB::table('attrs')->where('id', '=', $id)->first();

            $function_name = 'get'.ucfirst($attrs->type).'Data';
            $attrs->data = Self::$function_name($attrs);
        }
        return $attrs;
    }

    public static function saveAttr($data, $attr_data = null, $reg_id = null)
    {
        if(!$attr_data)
            $attr_data = Attr::getRegAttr($data['attr_id'], $reg_id);

        $function_name = 'set'.ucfirst($attr_data->type).'Value';

        if($attr_data->is_required == 1 && empty($data['value']) && empty($data['save']))
            return false;

        if(isset($data['save']))
        {
            $function_name2 = 'update'.ucfirst($attr_data->type).'Value';
            $r = Self::$function_name2($data, $attr_data);
        }

        $r = Self::$function_name($data, $attr_data);

        if($attr_data->is_required == 1)
        {
            if($r === false)
                return false;
        }

        return true;
    }

    //----------------------------------------------------------------------------

    public static function getRecordFiles($rid)
    {
        return DB::table('attrs_media')->where('record_id', '=', $rid)->get();
    }

    public static function getImageValues($attr, $rid)
    {
        return DB::table('attrs_media')->where([['attr_id', '=', $attr->attr_id], ['record_id', '=', $rid]])->get();
    }

    public static function getFileValues($attr, $rid)
    {
        return DB::table('attrs_media')->where([['attr_id', '=', $attr->attr_id], ['record_id', '=', $rid]])->get();
    }

    public static function getSelectValues($attr, $rid)
    {
        return DB::table('attrs_digit')->where([['attr_id', '=', $attr->attr_id], ['record_id', '=', $rid]])->get();
    }

    public static function getCharsValues($attr, $rid)
    {
        return DB::table('attrs_chars')->where([['attr_id', '=', $attr->attr_id], ['record_id', '=', $rid]])->first();
    }

    public static function getTextValues($attr, $rid)
    {
        return DB::table('attrs_text')->where([['attr_id', '=', $attr->attr_id], ['record_id', '=', $rid]])->first();
    }

    public static function getDigitValues($attr, $rid)
    {
        return DB::table('attrs_digit')->where([['attr_id', '=', $attr->attr_id], ['record_id', '=', $rid]])->get()->each(function ($item, $key) {$item->value = $item->value / 100;});
    }

    public static function getDateValues($attr, $rid)
    {
        return DB::table('attrs_digit')->where([['attr_id', '=', $attr->attr_id], ['record_id', '=', $rid]])->first();
    }

    public static function getRegisterValues($attr, $rid)
    {
        return DB::table('attrs_digit')->where([['attr_id', '=', $attr->attr_id], ['record_id', '=', $rid]])->get();
    }

    public static function getArrValues($attr, $rid)
    {
        return DB::table('attrs_text')->where([['attr_id', '=', $attr->attr_id], ['record_id', '=', $rid]])->first();
    }


    public static function getLinkValues($attr, $rid)
    {
        $fields = [];
        $r = DB::table('attrs_digit')->where([['attr_id', '=', $attr->attr_id], ['record_id', '=', $rid]])->get()->keyBy('value');
        $attrs = Self::getRegsAttrs($attr->meta);
        foreach($attrs as $item)
            if($item->type == 'chars' && isset($item->is_public) && $item->is_public)
                $fields[$item->name] = $item->title;

        foreach($r as $k => $item)
        {
            $r[$k]->data = Records::getRecord($k);
            $r[$k]->fields = $fields;
        }

        return $r;
    }


    //----------------------------------------------------------------------------

    public static function getImageData($attr)
    {
        return array();
    }

    public static function getArrData($attr)
    {
        return array();
    }

    public static function getFileData($attr)
    {
        return array();
    }

    public static function getSelectData($attr)
    {
        isset($attr->attr_id) ? $id = $attr->attr_id : $id = $attr->id;
        return Lists::getList($id)->keyBy('id');
    }

    public static function getCharsData($attr)
    {
        return array();
    }

    public static function getTextData($attr)
    {
        return array();
    }

    public static function getDigitData($attr)
    {
        if($attr->modificator == 'destination' && Auth::user())
        {
            $node = Reg::where('name', '=', 'root')->first();
            $root = Reg::where('name', '=', 'root')->first();
            $access = RegsUsers::where([['user_id', '=', Auth::user()->id],['view', '=', 1]])->get()->keyBy('reg_id');
            $nodes = $root->descendantsAndSelf()->withoutNode($node)->whereIn('id', $access->keys())->get();
            $data = collect();
            foreach($nodes as $node)
            {
                $node->access = $access[$node->id];
                $data->push($node);
            }

            return $data;
        }
        return array();
    }

    public static function getDateData($attr)
    {
        $darr = [1,2,3];
        $deadlines = [];
        foreach($darr as $deadline)
            $deadlines[$deadline] = Carbon::now()->addWeekday($deadline);

        return $deadlines;
    }

    public static function getRegisterData($attr)
    {
        return array();
    }

    public static function getLinkData($attr)
    {
        $fields = [];
        $attrs = Self::getRegsAttrs($attr->meta);
        foreach($attrs as $item)
            if($item->type == 'chars' && isset($item->is_public) && $item->is_public)
                $fields[$item->name] = $item->title;

        return $fields;
    }


    //----------------------------------------------------------------------------

    public static function setImageValue($data, $attr)
    {
        unset($data['type']);
        unset($data['save']);
        if(isset($data['value']) && !empty($data['value']))
        {
            if(!isset($data['id']))
            {
                $i = 0;
                if(!is_array($data['value']))
                    $data['value'] = array($data['value']);

                foreach($data['value'] as $v)
                {
                    $d = $data;
                    $d['value'] = ImageController::uploadImage($v);
                    if($d['value'])
                        $id = DB::table('attrs_media')->insertGetId($d);
                }
            }
            else
            {
                $id = $data['id'];
                unset($data['id']);
                $d['value'] = ImageController::uploadImage($data['value']);
                if($d['value'])
                    DB::table('attrs_media')->where('id', $id)->update($data);
            }
        }
    }

    public static function setFileValue($data, $attr)
    {
        if(!isset($data['save']))
            DB::table('attrs_media')->where([['attr_id', '=', $data['attr_id']],['record_id', '=', $data['record_id']]])->delete();

        unset($data['type']);
        unset($data['save']);
        if(isset($data['value']) && !empty($data['value']))
        {
            if(!is_array($data['value']))
                $data['value'] = array($data['value']);

            foreach($data['value'] as $v)
            {
                $filename = time() . str_slug(str_limit($v->getClientOriginalName(), 50)) . '.' . $v->getClientOriginalExtension();
                Storage::putFileAs('/public/' . date('Y-m') . '/' , $v, $filename);
                $d = $data;
                $d['value'] = '/storage/' . date('Y-m') . '/' . $filename;
                if($d['value'])
                    $id = DB::table('attrs_media')->insertGetId($d);
            }
        }
    }

    public static function setSelectValue($data, $attr)
    {
        if (isset($data['value']))
        {
            if((intval($data['value']) === 0))
            {
                $attr_data = Self::getSelectData($attr)->keyBy('value');
                if(isset($attr_data[$data['value']]))
                    $data['value'] = $attr_data[$data['value']]->id;
                else
                    $data['value'] = Lists::addValue($attr->attr_id, $data['value']);
            }

            if(!isset($data['id']))
                $id = DB::table('attrs_digit')->insertGetId($data);
            else
            {
                $id = $data['id'];
                unset($data['id']);
                DB::table('attrs_digit')->where('id', $id)->update($data);
            }

            return $id;
        }
    }

    public static function setCharsValue($data, $attr)
    {
        if(!isset($data['id']))
            $id = DB::table('attrs_chars')->insertGetId($data);
        else
        {
            $id = $data['id'];
            unset($data['id']);
            DB::table('attrs_chars')->where('id', $id)->update($data);
        }

        return $id;
    }

    public static function setTextValue($data, $attr)
    {
        if(!isset($data['id']))
            $id = DB::table('attrs_text')->insertGetId($data);
        else
        {
            $id = $data['id'];
            unset($data['id']);
            DB::table('attrs_text')->where('id', $id)->update($data);
        }

        return $id;
    }

    public static function setArrValue($data, $attr)
    {
        if(is_string($data['value']))
            $data['value'] = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $data['value']);

        if(is_object($data['value']) || is_array($data['value']))
            $data['value'] = json_encode((array)$data['value']);

        if(!isset($data['id']))
            $id = DB::table('attrs_text')->insertGetId($data);
        else
        {
            $id = $data['id'];
            unset($data['id']);
            DB::table('attrs_text')->where('id', $id)->update($data);
        }

        return $id;
    }

    public static function setDigitValue($data, $attr)
    {
        if($data['value'] == 'autoincrement')
        {
            //получаем все значение этого атрибута в данном
            $max = DB::table('attrs_digit')->where([['attr_id', $data['attr_id']]])->max('value');
            $data['value'] = (int)$max/100 + 1;
        }

        if(!isset($data['value']))
            return DB::table('attrs_digit')->where([['attr_id', $data['attr_id']], ['record_id', $data['record_id']]])->delete();

        if(is_string($data['value']))
            $data['value'] = explode(';', $data['value']);

        if(is_array($data['value']))
        {
            $cv = [];
            $current_values = DB::table('attrs_digit')->where([['attr_id', $data['attr_id']], ['record_id', $data['record_id']]])->get()->keyBy('id');
            foreach ($current_values as $val) {
                $cv[$val->id] = $val->value/100;
            }

            //находом значения на сохранение, это те, что есть в старом и в новом
            $del = array_diff($cv, $data['value']);
            foreach($del as $k => $d)
                DB::table('attrs_digit')->where('id', '=', $k)->delete();

            //добавляем новые
            $new = array_diff($data['value'], $cv);

            foreach($new as $v)
            {
                $insertdata = $data;
                unset($insertdata['id']);
                $insertdata['value'] = floatval($v) * 100;;
                DB::table('attrs_digit')->insert($insertdata);
            }
        }
        else
        {
            $data['value'] = floatval($data['value']) * 100;
            if(!isset($data['id']))
                $id = DB::table('attrs_digit')->insertGetId($data);
            else
                DB::table('attrs_digit')->where('id', $data['id'])->update($data);
        }
    }

    public static function setDateValue($data, $attr)
    {
        //delete if have empty value
        if(!isset($data['value']))
        {
            if($attr->modificator == 'deadline')
                $data['value'] = time() + 172800;
            else
                return DB::table('attrs_digit')->where([['attr_id', $data['attr_id']], ['record_id', $data['record_id']]])->delete();
        }


        //working with array
        if(is_array($data['value']))
        {

        }
        else
        {
            if($attr->modificator != 'deadline')
                $data['value'] = strtotime($data['value']);

            //insert
            if(!isset($data['id']))
            {
                if($attr->modificator == 'deadline' && $data['value'] < 3600000)
                    $data['value'] = time() + $data['value'];

                $id = DB::table('attrs_digit')->insertGetId($data);
            }
            //save
            else
            {
                $id = $data['id'];
                unset($data['id']);
                DB::table('attrs_digit')->where('id', $id)->update($data);
            }
        }
    }

    public static function setRegisterValue($data, $attr)
    {
        if(!isset($data['id']))
            $id = DB::table('attrs_digit')->insertGetId($data);
        else
        {
            $id = $data['id'];
            unset($data['id']);
            DB::table('attrs_digit')->where('id', $id)->update($data);
        }

        return $id;
    }

    public static function setLinkValue($data, $attr)
    {
        $ids = [];
        if(!isset($data['value']) || empty($data['value']))
        {
            if($attr->is_required == 1)
                return false;
            else
            {
                DB::table('attrs_digit')->where([['attr_id', '=', $data['attr_id']],['record_id', '=', $data['record_id']]])->delete();
                return true;
            }
        }

        if(!empty($data['value']) && Self::$import) //import settings
        {
            if(strpos($data['value'],'::')===false)
                $data['value'] = Self::getLinkValue($data['value'], $data['attr_id'], $attr);
            else
                $data['value'] = intval(str_replace('::', '', $data['value']));
        }

        if(!is_array($data['value']))
            $data['value'] = [$data['value']];

        foreach($data['value'] as $value) {
            $insert_data = $data;
            $insert_data['value'] = $value;

            if (isset($insert_data['id']))
            {
                dd($insert_data);
            }
            $ids[] = DB::table('attrs_digit')->updateOrInsert($insert_data, ['value' => $value]);
        }

        $r = DB::table('attrs_digit')
            ->where([['attr_id', '=', $data['attr_id']],['record_id', '=', $data['record_id']]])
            ->whereNotIn('value', $data['value'])
            ->delete();

        if(!empty($ids))
            return $ids;
        else
            return true;
    }

    // --------------------------------------------------------------------

    public static function updateImageValue($data, $attr)
    {
        $r = DB::table('attrs_media')->where([['attr_id', '=', $data['attr_id']],['record_id', '=', $data['record_id']]])->get();
        foreach($r as $item)
        {
            if(!in_array($item->id, $data['save']))
            {
                $r = Storage::delete(str_replace('/storage/', '/public/', $item->value));
                DB::table('attrs_media')->where('id', '=', $item->id)->delete();
            }
        }
    }

    public static function updateFileValue($data, $attr)
    {
        $r = DB::table('attrs_media')->where([['attr_id', '=', $data['attr_id']],['record_id', '=', $data['record_id']]])->get();
        foreach($r as $item)
        {
            if(!in_array($item->id, $data['save']))
            {
                $r = Storage::delete(str_replace('/storage/', '/public/', $item->value));
                DB::table('attrs_media')->where('id', '=', $item->id)->delete();
            }
        }
    }

    public static function updateLinkValue($data, $attr)
    {

        foreach($data['save'] as $item)
            DB::table('attrs_digit')->where('id', $item)->update(['value' => $data['value']]);
    }


    public static function getAttrValueByRecordId($rid, $name, $table = 'attr_digit')
    {
        $attr = Self::getAttrByName($name);
        return DB::table($table)->where([['attr_id', '=', $attr->id],['record_id', '=', $rid]])->get();
    }

    public static function getAttrTable($attr_name, $attr = null)
    {
        !$attr ? $attr = Self::getAttrByName($attr_name) : null;

        if(in_array($attr->type, ['select', 'date', 'register', 'link']))
            return 'attrs_digit';

        if(in_array($attr->type, ['image', 'file']))
            return 'attrs_media';

        if(in_array($attr->type, ['arr']))
            return 'attrs_text';

        return 'attrs_'.$attr->type;
    }

    public static function saveDefaultFields($id, $data)
    {
    }

    public static function getLinkValue($value, $attrId, $reg_attr)
    {
        $attr = Self::getAttrsById($attrId);
        if(isset($attr->meta) && intval($attr->meta)>0 && !empty($attr->data))
        {
            foreach($attr->data as $k => $item)
            {
                $params = [
                    'filters' => [
                        $k => [
                            [$k,'=',$value]
                        ]
                    ]
                ];
                $result = Records::getRecords($attr->meta, $params);
                if($result->count()>0)
                    return $result->first()->id;
            }
        }
        return false;
    }

}
