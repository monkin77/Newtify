<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Suspension;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
    public function suspensions()
    {
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

    /**
     * Suspends a user
     * 
     * @param  Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function suspendUser(Request $request, int $id)
    {  
        $user = User::find($id);
        if (is_null($user))
            return response()->json([
                'status' => 'Not Found',
                'msg' => 'User not found, id: '.$id,
                'errors' => ['user' => 'User not found, id: '.$id]
            ], 404);

        $this->authorize('suspendUser', $user);

        $validator = Validator::make($request->all(),[
            'reason' => 'required|string|min:5|max:200',
            'end_time' => 'required|string|date_format:d-m-Y H:i:s',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => 'Bad Request',
                'msg' => 'Failed to suspend user. Bad request',
                'errors' => $validator->errors(),
            ], 400);

        $start_timestamp = time();
        $end_timestamp = strtotime($request->end_time);

        if ($start_timestamp >= $end_timestamp)
            return response()->json([
                'status' => 'Bad Request',
                'msg' => 'Failed to suspend user. Bad request',
                'errors' => ['end_time' => 'End time must be after now'],
            ], 400);

        $suspension = new Suspension();
        $suspension->reason = $request->reason;
        $suspension->start_time = gmdate('Y-m-d H:i:s', $start_timestamp);
        $suspension->end_time = gmdate('Y-m-d H:i:s', $end_timestamp);
        $suspension->user_id = $id;
        $suspension->admin_id = Auth::id();

        $user->is_suspended = true;

        $suspension->save();
        $user->save();

        return response()->json([
            'status' => 'OK',
            'msg' => 'Successful user suspension',
            'id' => $suspension->id,
        ], 200);
    }

    /**
     * Suspends a user
     * 
     * @param  Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function unsuspendUser(int $id)
    {
        $user = User::find($id);
        if (is_null($user))
            return response()->json([
                'status' => 'Not Found',
                'msg' => 'User not found, id: '.$id,
                'errors' => ['user' => 'User not found, id: '.$id]
            ], 404);

        $this->authorize('suspendUser', $user);

        if (!$user->is_suspended)
            return response()->json([
                'status' => 'OK',
                'msg' => 'User was not suspended, id: '.$id,
            ], 200);

        Suspension::where('user_id', $id)
            ->where('end_time', '>', gmdate('Y-m-d H:i:s'))
            ->update(['end_time' => gmdate('Y-m-d H:i:s')]);

        $user->is_suspended = false;
        $user->save();

        return response()->json([
            'status' => 'OK',
            'msg' => 'Successfully unsuspended user'
        ], 200);
    }
}
