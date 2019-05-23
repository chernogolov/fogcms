<?php

namespace Chernogolov\Fogcms;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;

use Chernogolov\Fogcms\Attr;

class Records extends Model
{
    //
    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'records';

    public static $import = false;

    public function records()
    {
        return $this->hasMany('App\Records_Regs');
    }

    public static function getRecord($id, $fields = true)
    {
        $data = DB::table('records')
            ->join('records_regs', 'records.id', '=', 'records_regs.records_id')
            ->where('records.id', $id)
            ->select('records.*')
            ->first();

        if(!$data)
            return false;

        $data = (array) $data;
        if($fields)
        {
            $attrs = Attr::getRecordAttrs($id, 'name');
            foreach($attrs as $name => $attr)
                $data = array_merge($data, Self::flatAttr($name, $attr));
        }
        return $data;
    }

    public static function flatAttr($name, $attr)
    {
        $data = [];
        if($attr->type == 'digit')
            foreach($attr->values as $val)
                $data[$name] = $val->value;
        elseif($attr->type == 'select')
        {
            foreach($attr->values as $val)
                isset($attr->data[$val->value]) ? $data[$name] = $attr->data[$val->value]->value : null;
        }
        elseif($attr->type == 'image')
        {
            foreach($attr->values as $val)
            {
                $data[$name] = $val->value;
            }
        }
        elseif($attr->type == 'link')
        {
            foreach($attr->values as $val)
            {
                $inattrs = Attr::getRecordAttrs($val->value, 'name');
                foreach($inattrs as $inname => $inattr)
                    $data = array_merge($data, Self::flatAttr($name . '.' . $inname, $inattr));
            }
        }
        elseif($attr->type == 'date')
        {
            if(isset($attr->values->value))
                $data[$name] = date('d-m-Y', $attr->values->value);
        }
        else
        {
            if(isset($attr->values->value))
                $data[$name] = $attr->values->value;
        }

        return $data;
    }

    public static function getRecords($id, $params = array(), $attrs = array())
    {
        $where = [];
        $whereIn = [];
        $select[] = 'records.*';

        //base
        $tickets = DB::table('records')
            ->join('records_regs', 'records.id', '=', 'records_regs.records_id');


        !is_array($id) && $id!=null ? $id = [$id] : null ;

        //end base

        if(isset($params['type']))
        {
            $tickets->join('regs', 'regs.id', '=', 'records_regs.regs_id');
            $where[] = ['regs.type', '=', $params['type']];
        }
        if(!isset($params['fields']))
        {
            $fields = Attr::getRegsAttrs($id);
            foreach($fields as $f)
                $params['fields'][$f->name] = $f;
        }
//dd($params);
        //set fields
        if(isset($params['fields']) && is_array($params['fields']))
        {
            $alpha = range('a','z');
            $i=0;
            foreach($params['fields'] as $field => $fdata)
            {
                if(isset($fdata->attr_id))
                {
                    if($fdata->type == 'select')
                    {
                        $table = Attr::getAttrTable($field, $fdata) . ' as ' . $alpha[$i];
                        $tickets->leftJoin($table, function ($join) use($alpha, $fdata, $field, $i) {
                            $join->on($alpha[$i].'.record_id', '=', 'records.id')
                                ->where($alpha[$i].'.attr_id', '=', $fdata->attr_id);
                        });
                        $table_aliases[$field] = $alpha[$i];

                        $i++;
                        $table2 = 'lists as ' . $alpha[$i];
                        $tickets->leftJoin($table2, function ($join) use($alpha, $fdata, $field, $i) {
                            $join->on($alpha[$i-1].'.value', '=', $alpha[$i] . '.id')
                                ->where($alpha[$i].'.attr_id', '=', $fdata->attr_id);
                        });
                        $select[] = $alpha[$i] . '.value as ' . $field;
                        $table_aliases[$field . '_'] = $alpha[$i];
                        $i++;
                    }
                    elseif($fdata->type == 'link')
                    {

                        //получаем атрибуты связанного журнала
                        $link_attrs = Attr::getRegsAttrs($fdata->meta);

                        foreach ($link_attrs as $lattr) {
                            //если атрибут - публичный, добавляем его в выдачу
                            if($lattr->is_public == 1)
                            {
                                $table = Attr::getAttrTable($field, $fdata) . ' as ' . $alpha[$i];
                                $tickets->leftJoin($table, function ($join) use($alpha, $fdata, $field, $i) {
                                    $join->on($alpha[$i].'.record_id', '=', 'records.id')
                                        ->where($alpha[$i].'.attr_id', '=', $fdata->attr_id);
                                });
                                $table_aliases[$field] = $alpha[$i];

                                $i++;
                                $table2 = Attr::getAttrTable($lattr->name).' as ' . $alpha[$i];
                                $tickets->leftJoin($table2, function ($join) use($alpha, $fdata, $field, $i, $lattr) {
                                    $join->on($alpha[$i-1].'.value', '=', $alpha[$i] . '.record_id')
                                        ->where($alpha[$i].'.attr_id', '=', $lattr->attr_id);
                                });
                                $select[] = $alpha[$i] . '.value as ' . $field;
                                $select[] = $alpha[$i-1] . '.value as ' . $field . '_rid';
                                $table_aliases[$field . '_'] = $alpha[$i];
                                $i++;
                            }
                        }
                    }
                    else
                    {
                        $table = Attr::getAttrTable($field, $fdata) . ' as ' . $alpha[$i];
                        $tickets->leftJoin($table, function ($join) use($alpha, $fdata, $field, $i) {
                            $join->on($alpha[$i].'.record_id', '=', 'records.id')
                                ->where($alpha[$i].'.attr_id', '=', $fdata->attr_id);
                        });
                        $table_aliases[$field] = $alpha[$i];
                        $select[] = $alpha[$i] . '.value as ' . $field;
                        $i++;
                    }
                }
            }
        }
        //endfields

        //user
        isset($params['user_id']) ? $where[] = ['records.user_id', '=', $params['user_id']] : null;
        //end user

        //added users
        if(isset($params['added_user']))
        {
            $tickets->join('users_records', 'records.id', '=', 'users_records.record_id');
            $where[] = ['users_records.user_id', '=', $params['added_user']];
        }

        //filter
        if(isset($params['filters']))
        {
            foreach($params['filters'] as $key => $filter)
            {
                $attr_data = Attr::getAttrByName($key);
                if($attr_data && isset($table_aliases[$key]))
                {
                    if($attr_data->type == 'date')
                    {
                        $item[0] = $table_aliases[$key] . '.value';
                        if($item[1] == '=')
                        {
                            $where[] = [$item[0], '>', strtotime($item[2])];
                            $where[] = [$item[0], '<', strtotime($item[2])+86400];
                        }
                        else
                            $where[] = [$item[0], $item[1], strtotime($item[2])];
                    }
                    else
                        foreach($filter as $k => $item)
                        {
                            if($k === 'whereIn')
                            {
                                $whereIn[$table_aliases[$key] . '.value'] = $item;
                            }
                            else
                            {
                                $item[0] = $table_aliases[$key] . '.value';
                                $where[] = $item;
                            }
                        }
                }
                else
                {
                    if($key == 'created_at' || $key == 'updated_at')
                        foreach($filter as $f)
                            $f[1] == '=' ? $tickets->whereDate('records.'.$key, $f[2]) : $where[]  = $f;
                    else
                        $where = array_merge($where, $filter);
                }
            }
        }

        //end filter
        foreach ($whereIn as $kwhere => $kin) {
            $tickets->whereIn($kwhere, $kin);
        }

        $tickets->where($where);
        //include
        if(isset($params['include']))
        {
            !is_array($params['include'])? $params['include'] = array($params['include']) : null;
            $tickets->whereIn('records.id', $params['include']);
        }

        if($id)
            $tickets->whereIn('records_regs.regs_id', $id);

        //excluede
        if(isset($params['exclude']))
        {
            !is_array($params['exclude'])? $params['exclude'] = array($params['exclude']) : null;
            $tickets->whereNotIn('records.id', $params['exclude']);
        }

        $tickets->select($select);
        $tickets->groupBy('records.id');
        if(isset($params['orderBy']))
        {
            if(isset($params['orderBy']['attr']))
            {
                $attr_data = Attr::getAttrByName($params['orderBy']['attr']);
                if($attr_data->type == 'link' || $attr_data->type == 'select' && isset($table_aliases[$params['orderBy']['attr'].'_']))
                    $tickets->orderBy($table_aliases[$params['orderBy']['attr'].'_'] . '.' . $params['orderBy']['field'], $params['orderBy']['type']);
                else
                    $tickets->orderBy($table_aliases[$params['orderBy']['attr']] . '.' . $params['orderBy']['field'], $params['orderBy']['type']);
            }
            else
                $tickets->orderBy($params['orderBy']['field'], $params['orderBy']['type']);
        }
        else
        {
            $tickets->orderBy('status', 'ASC');
            $tickets->orderBy('created_at', 'DESC');
        }


        if(!isset($params['offset']))
        {
            !isset($params['limit'])? $params['limit'] = 30 : null;
            $r = $tickets->paginate($params['limit']);
            $r->map(function ($item, $key) {
                if(isset($item->deadline))
                    $item->deadline = date('Y-m-d h:i', $item->deadline);

                return $item;
            });
        }
        else
        {
            isset($params['limit'])? $tickets->limit($params['offset']) : $params['limit'] = 30 ;
            $r = $tickets->get();
        }


//        if(config('app.debug'))
//        {
//            var_dump($tickets->toSql());
//        }



        return $r;
    }

    public static function addRecord($regs, $attrs, $data = null)
    {
        Attr::$import = Self::$import;
        unset($data['id']);
        $attrs_data = [];

        if(!is_array($regs))
            $regs = array($regs);

        foreach($regs as $reg_id)
        {
            //check required fields

            foreach($attrs as $k => $v)
            {
                $attr_d = Attr::getRegAttr($v['attr_id'], $reg_id);
                if($attr_d->is_required == 1)
                    if(empty($attrs[$k]['value']))
                        return false;

                $attrs_data[$k] = $attr_d;
            }
        }
        if(!isset($data['created_at']))
            $data['created_at']= \Carbon\Carbon::now()->toDateTimeString();

        if(!isset($data['updated_at']))
            $data['updated_at']= \Carbon\Carbon::now()->toDateTimeString();

        if(!isset($data['user_id']))
            Auth::check() ? $data['user_id'] = Auth::user()->id : $data['user_id'] = 0;

        //insert record data
        $record_id = DB::table('records')->insertGetId($data);

        foreach($regs as $reg)
            DB::table('records_regs')->insertGetId(array('records_id' => $record_id, 'regs_id' => $reg));

        foreach($attrs as $kk => $attr)
        {
            $attr['record_id'] = $record_id;
            if(Attr::saveAttr($attr, $attrs_data[$kk]) == false)
            {
                Self::deleteRecord($record_id);
                return false;
            }
        }

        //add crate status
        self::addRecordStatus($record_id, 1);

        return $record_id;
    }

    public static function saveRecord($regs, $data, $attrs, $reg_id = null)
    {
        Attr::$import = Self::$import;
        if(!is_array($regs))
            $regs = array($regs);

        $data['updated_at']= \Carbon\Carbon::now()->toDateTimeString();

        //save record data ----------------------
        $record_id = $data['id'];

        $record = Self::find($record_id);
        foreach ($record as $key => $value) {
            if(isset($data[$key]))
                $record[$key] = $data[$key];
        }
        $record->save();

        //save record regs ----------------------
        //get all regs
//        $rs = DB::table('records_regs')->where([['records_id', '=', $record_id]])->select('records_regs.regs_id')->get();
//        $new_rs = array();
//        foreach($rs as $r)
//        {
//            $new_rs[] = $r->regs_id;
        //ig regs_id not in regs
//            if(!in_array($r->regs_id, $regs))
//                DB::table('records_regs')->where([['records_id', '=', $record_id],['regs_id', '=', $r->regs_id]])->delete();
//        }
//        foreach($regs as $reg)
//        {
//            if(!in_array($reg, $new_rs))
//                $id = DB::table('records_regs')->insertGetId(array('records_id' => $record_id, 'regs_id' => $reg));
//        }

        if(!empty($regs))
        {
            foreach($regs as $reg)
            {
                foreach($attrs as $attr)
                {
                    $attr['record_id'] = $record_id;
                    if(Attr::saveAttr($attr, null, $reg) == false)
                        return false;
                }
            }
        }
        else
            foreach($attrs as $attr)
            {
                $attr['record_id'] = $record_id;
                if(Attr::saveAttr($attr) == false)
                    return false;
            }

        //save attributes ----------------------


    }

    public static function deleteRecord($record_id, $regs_id = null, $nocart = false)
    {
        if(!$nocart)
        {
            $where = [['records_id', '=', $record_id]];
            $regs_id ? $where[] = ['regs_id', '=', $regs_id] : null;
            DB::table('records_regs')->where($where)->delete();
        }
        else
        {
            $where = [['id', '=', $record_id]];
            DB::table('records')->where($where)->delete();
        }
    }

    public static function clearReg($regs_id)
    {
        $where = [['regs_id', '=', $regs_id]];
        DB::table('records_regs')->where($where)->delete();
    }

    public static function copyRecord($record_id, $destination)
    {
        $success = [];
        foreach($destination as $reg)
        {
            //check current destination
            $result = DB::table('records_regs')->where([['records_id', '=', $record_id],['regs_id', '=', $reg]])->get();
            if(count($result)<1)
            {
                $success[] = $reg;
                DB::table('records_regs')->insertGetId(array('records_id' => $record_id, 'regs_id' => $reg));
            }
        }

        return $success;
    }

    public static function getTrash()
    {
        $tickets = DB::table('records')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('records_regs')
                    ->whereRaw('records.id = records_regs.records_id');
            })
            ->select('records.*')
            ->get();

        return $tickets;
    }

    public static function clearTrash()
    {
        $tickets = DB::table('records')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('records_regs')
                    ->whereRaw('records.id = records_regs.records_id');
            })
            ->select('records.*')
            ->delete();

        return $tickets;
    }

    public static function changeRecordStatus($rid, $sid)
    {
        //add crate status
        if(Auth::check()) {
            $updated_at = \Carbon\Carbon::now()->toDateTimeString();
            DB::table('records')->where('id', $rid)->update(array('status' => $sid, 'updated_at' => $updated_at));
            self::addRecordStatus($rid, $sid);
            $statuses = [1 => ['status' => 'Новая', 'class' => 'danger'], 2 => ['status' => 'В работе', 'class' => 'warning'], 3 => ['status' => 'Выполнено', 'class' => 'success'], 4 => ['status' => 'Закрыто автором', 'class' => 'default']];
            return $statuses[$sid];
        }
    }

    public static function addRecordStatus($rid, $sid)
    {
        //add crate status
        $user_id = 1;

        if (Auth::check())
            $user_id = Auth::user()->id;


        $added_on = \Carbon\Carbon::now()->toDateTimeString();
        $r = Self::getRecordStatuses($rid, 'asc');
        if(count($r)>20)
            DB::table('records_statuses')->where('id', '=', $r[0]->id)->delete();

        DB::table('records_statuses')->insertGetId(array('record_id' => $rid, 'status' => $sid, 'user_id' => $user_id, 'added_on' => $added_on));

    }

    public static function getRecordStatuses($rid, $sort = 'desc')
    {
        return DB::table('records_statuses')->join('users', 'records_statuses.user_id', '=', 'users.id')->where('record_id', '=', $rid)->orderBy('added_on', $sort)->select(['records_statuses.*', 'users.name', 'users.email'])->get();
    }

    public static function getRecordRegs($rid)
    {
        return DB::table('regs')
            ->join('records_regs', 'regs.id', '=', 'records_regs.regs_id')
            ->where('records_regs.records_id', '=', $rid)
            ->select('regs.*');

    }

    public static function updateReg($reg_ids, $data)
    {
        !is_array($reg_ids) ? $reg_ids = array($reg_ids) : null;

        $default_fields = array_keys(Config::get('fogcms.default_fields'));

        foreach($reg_ids as $id)
        {
            $record = [];
            $attrs = [];

            $reg_attrs = Collect(Attr::getRegsAttrs($id))->keyBy('name');
            foreach($data as $item)
            {
                if(is_object($item))
                    $item = (array)$item;

                if(isset($item['id']) && $item['id'] && Self::getRecord($item['id']))  // update record
                {
                    foreach($item as $key => $value)
                    {
                        if($reg_attrs->get($key) || in_array($key, $default_fields))
                        {
                            if(in_array($key, $default_fields))
                                $record[$key] = $value;
                            else
                            {
                                $reg_attr = Attr::getAttrValueByRecordId($item['id'], $key,  Attr::getAttrTable($key));
                                foreach($reg_attr as $ri)
                                    $attrs[$key]['id'] = $ri->id;

                                $attrs[$key]['attr_id'] = $reg_attrs[$key]->attr_id;
                                $attrs[$key]['value'] = $value;
                            }
                        }
                    }
                    Self::saveRecord($id, $record, $attrs);
                }
                else   // add new record
                {

                    foreach($item as $key => $value)
                    {
                        if($reg_attrs->get($key))
                        {
                            if(in_array($key, $default_fields))
                                $record[$key] = $value;
                            else
                            {
                                $attrs[$key]['attr_id'] = $reg_attrs[$key]->attr_id;
                                $attrs[$key]['value'] = $value;
                            }
                        }
                    }
                    Self::addRecord($id, $attrs, $record);
                }
            }
        }
    }

    public static function getDestinationNodes($attrs, $data)
    {
        $regs = [];
        $i = 1;

        foreach ($attrs as $item) {
            if($item->type == 'link' && isset($data[$item->name]['value']))
            {
                $record = Attr::getRecordAttrs($data[$item->name]['value'], 'name');
                if(isset($record['destination']))
                {
                    if($i === 1)
                        $regs = $record['destination']->values->keyBy('value')->keys()->toArray();
                    else
                        $regs = array_intersect($regs, $record['destination']->values->keyBy('value')->keys()->toArray());

                    $i++;
                }
            }
        }

        return $regs;
    }

    public static function addRecordUser($record_id, $user_id)
    {
        $data = [];
        $data['record_id']= $record_id;
        $data['user_id']= $user_id;

        if(!DB::table('users_records')->where($data)->get()->count())
        {
            $data['created_at']= \Carbon\Carbon::now()->toDateTimeString();
            $data['updated_at']= \Carbon\Carbon::now()->toDateTimeString();
            DB::table('users_records')->insert($data);
        }
    }

    public static function deleteRecordUser($record_id, $user_id)
    {
        $where = [];
        $where[] = ['record_id', '=', $record_id];
        $where[] = ['user_id', '=', $user_id];
        return DB::table('users_records')->where($where)->delete();
    }

    public static function OnOff($rid, $sid)
    {
        $updated_at= \Carbon\Carbon::now()->toDateTimeString();
        DB::table('records')->where('id', $rid)->update(array('status' => $sid, 'updated_at' => $updated_at));
        $statuses = [1 => ['status' => '<img src="/img/on.png" class="off">', 'class' => 'primary', 'change' => '0'], 0 => ['status' => '<img src="/img/off.png" class="off">', 'class' => 'default', 'change' => '1']];
        return $statuses[$sid];
    }

    public static function Rate($rid, $rating)
    {
        $updated_at= \Carbon\Carbon::now()->toDateTimeString();
        DB::table('records')->where('id', $rid)->update(array('rating' => intval($rating), 'updated_at' => $updated_at));
        return $rating;
    }
}
