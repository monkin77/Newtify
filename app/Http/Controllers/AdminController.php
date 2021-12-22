<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display the Administration Panel
     *
     * @return View
     */
    public function show()
    {
        $this->authorize('show', Admin::class);

        $admin = Admin::find(Auth::id());
        if (is_null($admin))
            return abort(404, 'User not found');

        $adminInfo = [
            'id' => $admin->id,
            'name' => $admin->name,
            'avatar' => $admin->avatar,
            'country' => $admin->country,
            'city' => $admin->city,
        ];

        return view('pages.admin_panel',[
            'admin' => $adminInfo,
        ]);
    }

    /**
     * Display the list of suspended users
     *
     * @return View
     */
    public function suspensions() {
        $this->authorize('suspensions', Admin::class);

        $suspendedUserList = User::where('is_suspended', true)->get();

        $suspendedUsers = $suspendedUserList->map(function ($user) {
            $history = $user->suspensions->map(function ($suspension) {

                $adminInfo = [
                    'id' => $suspension->admin_id,
                    'name' => Admin::find($suspension->admin_id)->name,
                ];

                return [
                    'reason' => $suspension->reason,
                    'start_date' => gmdate('d-m-Y', strtotime($suspension->start_time)),
                    'end_date' => gmdate('d-m-Y', strtotime($suspension->end_time)),
                    'admin' => $adminInfo,
                ];
            })->sortByDesc('start_date');

            return [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'country' => $user->country,
                'history' => $history,
                // The admin could select the user and see his history or see all suspensions
            ];
        });

        $suspensionHistory = DB::table('suspension')->get()->map(function ($suspension) {

            $adminInfo = [
                'id' => $suspension->admin_id,
                'name' => Admin::find($suspension->admin_id)->name,
            ];

            $user = User::find($suspension->user_id);
            $userInfo = [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'country' => $user->country,
            ];

            return [
                'reason' => $suspension->reason,
                'start_date' => gmdate('d-m-Y', strtotime($suspension->start_time)),
                'end_date' => gmdate('d-m-Y', strtotime($suspension->end_time)),
                'admin' => $adminInfo,
                'user' => $userInfo,
            ];
        })->sortByDesc('start_date');

        return view('pages.suspensions', [
            'suspendedUsers' => $suspendedUsers,
            'suspensionHistory' => $suspensionHistory,
        ]);
    }
}
