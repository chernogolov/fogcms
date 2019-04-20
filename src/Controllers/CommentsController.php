<?php

namespace Chernogolov\Fogcms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use Chernogolov\Fogcms\Comments;

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
            Comments::saveComment($post_data['comment'], $rid);

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
