<?php

namespace Chernogolov\Fogcms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Chernogolov\Fogcms\Controllers\ImageController;


class Comments extends Model
{
    //
    protected $table = 'comments';

    public static function saveComment($data, $rid)
    {
        $insertdata['record_id'] = $rid;
        $insertdata['text'] = $data['text'];
        $insertdata['added_on']= \Carbon\Carbon::now()->toDateTimeString();
        $insertdata['user_id'] = Auth::user()->id;

        $comment_id = DB::table('comments')->insertGetId($insertdata);
        if(isset($data['loaded']) && !empty($data['loaded']))
        {
            unset($data['image']);
            foreach($data['loaded'] as $l)
            {
                $img_data['comment_id'] = $comment_id;
                $img_data['image']  = $l;
                DB::table('comments_media')->insertGetId($img_data);
                unset($img_data);
            }
        }

        if(isset($data['image']) && !empty($data['image']))
        {
            foreach($data['image'] as $img)
            {
                $img_data['comment_id'] = $comment_id;
                $img_data['image']  = ImageController::uploadImage($img);
                DB::table('comments_media')->insertGetId($img_data);
                unset($img_data);
            }
        }

    }

    public static function getComments($rid)
    {
        $tickets = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->where('comments.record_id', '=', $rid)
            ->select(['comments.*', 'users.name', 'users.email', 'users.image']);

        $r =  $tickets->get()->keyBy('id');

        foreach($r as $id => $c)
            $r[$id]->images = DB::table('comments_media')->where('comment_id', '=', $id)->get();

        return $r;
    }

    public static function deleteComment($id)
    {
        $images = DB::table('comments_media')->where('comment_id', '=', $id)->get();
        foreach($images as $img)
            $r = Storage::delete('/public/' . $img->image);

        Self::where('id', '=', $id)->delete();
    }
}
