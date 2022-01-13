<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function inbox()
    {
        $this->authorize('messages', User::class);

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
}
