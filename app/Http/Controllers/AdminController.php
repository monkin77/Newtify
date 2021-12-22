<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
