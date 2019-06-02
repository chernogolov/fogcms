<?php

namespace Chernogolov\Fogcms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

use App\Http\Controllers\Controller;
use Chernogolov\Fogcms\Notifications\AddRecordComment;

use Chernogolov\Fogcms\Comments;
use Chernogolov\Fogcms\Records;
use Chernogolov\Fogcms\RegsUsers;
use Chernogolov\Fogcms\User;


class CommentsController extends Controller
{
    //
    public static function getComments($rid)
    {
        $comments = Comments::getComments($rid);
        return view('fogcms::records/comments', ['rid' => $rid, 'data' => $comments, 'user_id' => Auth::id()]);
    }

    public function editComments(Request $request, $rid)
    {
        $this->validate($request, ['comment.text' => 'required|max:3000|min:2']);

        $post_data = $request->all();
        if (isset($post_data['comment']))
        {
            $record = Records::getRecord($rid);
            $regs = Records::getRecordRegs($rid)->get();
            foreach ($regs as $reg) {
                $user_sends = RegsUsers::where([['user_id', '=', $record['user_id']], ['reg_id', '=', $reg->id]])->get();
                $channels = [];
                foreach ($user_sends as $send)
                    if(intval($send->email) == 1)
                        $channels[] = 'mail';

                $channels = array_unique($channels);
                $user = User::where('id', '=', $record['user_id'])->first();
            }
            try {
                Notification::send($user, new AddRecordComment((object)$record, '', $channels));
            } catch (\Exception $e) {
                file_get_contents('https://api.telegram.org/bot608599411:AAFPZIybZ-O9-t4y_rlRxmtQV4i-sV8aF6c/sendMessage?chat_id=293756888&text=gkh2 send ' . $user->email . ' notification error ' . $e);
            }
            Comments::saveComment($post_data['comment'], $rid);
        }


        return Self::getComments($rid);
    }

    public function deleteComments(Request $request, $rid)
    {
        $post_data = $request->all();
        if(isset($post_data['delete']))
            foreach($post_data['delete'] as $k => $v)
                Comments::deleteComment($k);

        return Self::getComments($rid);
    }
}
