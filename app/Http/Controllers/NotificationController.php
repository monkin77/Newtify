<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Returns a list of the user's notifications
     * 
     * @return View
     */
    public function show()
    {
        $this->authorize('notifications', User::class);

        $notifications = Auth::user()->notifications->sortByDesc('date');
        // TODO: Load on scroll
        return $notifications;
        return view('partials.notifications', [
            'notifications' => $notifications
        ]);
    }

    /**
     * Marks all user's notifications as read
     * 
     * @return Response
     */
    public function readNotifications()
    {
        $this->authorize('notifications', User::class);

        $notifications = Auth::user()->notifications;
        $notifications->each(function ($notification) {
            $notification->is_read = true;
            $notification->save();
        });

        return response()->json([
            'status' => 'OK',
            'msg' => 'Successfully marked notifications as read'
        ], 200);
    }
}
