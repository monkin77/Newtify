<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function inbox()
    {
        if (Auth::guest())
            return redirect('/login');

        // One message per user, sorted by most recent
        $messages = Message::where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->orderByDesc('published_at')->get()
            ->groupBy(function ($msg) {
                return $msg['sender_id'] === Auth::id() ?
                    $msg['receiver_id']
                    :
                    $msg['sender_id'];
            })->map(function ($item) {
                $msg = $item[0];
                $sentByUser = $msg->sender_id === Auth::id();
                $friend = $sentByUser ? $msg->receiver : $msg->sender;

                return [
                    'message' => [
                        'id' => $msg->id,
                        'sentByUser' => $sentByUser,
                        'body' => $msg->body,
                        'published_at' => $msg->published_at,
                        'is_read' => $msg->is_read,
                    ],
                    'friend' => $friend->only([
                        'id', 'name', 'is_admin', 'avatar', 'city', 'country'
                    ])
                ];
            });

        return view('pages.messages.inbox', [
            'messages' => $messages
        ]);
    }

    public function messageThread(int $id)
    {
        if (Auth::guest())
            return redirect('/login');

        $user = User::find($id);
        if (is_null($user))
            return abort(404, 'User not found, id: ' . $id);

        $userInfo = Auth::user()->only([
            'id', 'name', 'is_admin', 'avatar', 'city', 'country', 'is_suspended'
        ]);

        $friendInfo = User::find($id)->only([
            'id', 'name', 'is_admin', 'avatar', 'city', 'country', 'is_suspended'
        ]);

        $messages = Message::select('body', 'published_at', 'is_read')
        ->where(function ($query) use($id) {
            $query->where('sender_id', $id)
                ->where('receiver_id', Auth::id());

        })->orWhere(function ($query) use($id) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $id);

        })->orderByDesc('published_at')->get();

        return view('pages.messages.thread', [
            'userInfo' => $userInfo,
            'friendInfo' => $friendInfo,
            'messages' => $messages,
        ]);
    }

    public function create(Request $request, int $id)
    {
        $this->authorize('messages', User::class);

        $user = User::find($id);
        if (is_null($user))
            return Response()->json([
                'status' => 'NOT FOUND',
                'msg' => 'User not found, id: ' . $id
            ], 404);

        $validator = Validator::make($request->all(),
            [
                'body' => 'required|string|min:10',
            ]
        );

        if ($validator->fails()) {
            return Response()->json([
                'status' => 'Bad Request',
                'msg' => 'Failed to send message. Bad Request',
                'errors' => $validator->errors(),
            ], 400);
        }

        $message = new Message;
        $message->body = $request->body;
        $message->sender_id = Auth::id();
        $message->receiver_id = $id;
        $message->save();

        return Response()->json([
            'status' => 'OK',
            'msg' => 'Successfully sent message',
            'id' => $message->id,
        ], 200);
    }

    
}
