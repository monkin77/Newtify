<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Content;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Creates a new Comment instance.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Comment::class);

        $validator = Validator::make($request -> all(),
            [
                'body' => 'required|string',
                'article_id' => 'required|exists:article,content_id',
                'parent_comment_id' => 'nullable|exists:comment,content_id'
            ]
        );
        if ( $validator->fails() ) {
            return response()->json([
                'status' => 'Bad Request',
                'msg' => 'Failed to create comment. Bad request',
                'errors' => $validator->errors(),
            ], 400);
        }

        $content = new Content;
        $content->body = $request->body;
        $content->author_id = Auth::id();
        $content->save();

        $comment = new Comment;
        $comment->content_id = $content->id;
        $comment->article_id = $request->article_id;

        if (isset($request->parent_comment_id))
            $comment->parent_comment_id = $request->parent_comment_id;

        $comment->save();

        return response()->json([
            'status' => 'OK',
            'msg' => 'Successfully created comment',
            'id' => $comment->content_id,
        ], 200);
    }
}
