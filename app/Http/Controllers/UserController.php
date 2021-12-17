<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Country;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

// TODO: Check headers for redirects
class UserController extends Controller
{
    /**
     * Display the User profile.
     *
     * @param  $id Id of the user
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $user = User::find($id);
        if (is_null($user))
            return abort(404, 'User not found, id: '.$id);

        $userInfo = [
            'id' => $id,
            'name' => $user->name,
            'email' => $user->email,
            'birthDate' => $user->birth_date,
            'isAdmin' => $user->is_admin,
            'description' => $user->description,
            'avatar' => $user->avatar,
            'country' => $user->country,
            'city' => $user->city,
            'isSuspended' => $user->is_suspended,
            'reputation' => $user->reputation,
        ];

        $follows = false;
        if (Auth::check()) {
            $follows = Auth::user()->isFollowing($id);
        }

        $areasExpertise = $user->topAreasExpertise();
        $followerCount = count($user->followers);

        $articles = $user->articles()->map(function ($article) {
            return [
                'title' => $article->title,
                'thumbnail' => $article->thumbnail,
                'body' => $article->body,
                'published_at' => $article->published_at,
                'likes' => $article->likes,
                'dislikes' => $article->dislikes
            ];
        })->sortByDesc('published_at')->take(4);

        return view('pages.profile', [
            'user' => $userInfo,
            'follows' => $follows,
            'topAreasExpertise' => $areasExpertise,
            'followerCount' => $followerCount,
            'articles' => $articles,
        ]);
    }

    /**
     * Show the form for editing the user profile.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $user = User::find($id);
        if (is_null($user))
            return abort(404, 'User not found, id: '.$id);

        return view('pages.edit_profile');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id) : RedirectResponse
    {
        $user = User::find($id);
        if (is_null($user))
            return redirect()->back()->withErrors(['user' => 'User not found, id: '.$id]);

        $validator = Validator::make($request->all(),[
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:authenticated_user',
            'password' => 'required|string|password',
            'new_password' => 'nullable|string|min:6|confirmed',
            'birthDate' => 'nullable|string|date_format:d-m-Y|before:'.date('d-m-Y'), // before today
            'country' => 'nullable|string|exists:country,name',
            'avatar' => 'nullable|file|max:5000', // max 5MB
            // TODO: File upload
            'description' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            // Go back to form and refill it
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        if (isset($request->name)) $user->name = $request->name;
        if (isset($request->email)) $user->email = $request->email;
        if (isset($request->new_password)) $user->password = $request->new_password;
        if (isset($request->birthDate)) $user->birth_date = $request->birthDate;
        if (isset($request->country)) $user->country_id = Country::getIdByName($request->country);
        if (isset($request->description)) $user->description = $request->description;
        if (isset($request->city)) $user->city = $request->city;

        $user->save();
        return redirect("/user/${id}");
    }

    /**
     * Deletes a user account.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, int $id) : RedirectResponse
    {
        $user = User::find($id);
        if (is_null($user))
            return redirect()->back()->withErrors(['user' => 'User not found, id: '.$id]);

        $validator = Validator::make($request->all(),[
            'password' => 'required|string|password'
        ]);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator->errors());

        $deleted = $user->delete();
        if ($deleted)
            return redirect('/');
        else
            return redirect()->back()->withErrors(['user' => 'Failed to delete user account. Try again later']);
    }

    public function report(Request $request, int $id)
    {
        $user = User::find($id);
        if (is_null($user))
            return response()->json([
                'status' => 'Not Found',
                'msg' => 'User not found, id: '.$id,
                'errors' => ['user' => 'User not found, id: '.$id]
            ], 404);

            $validator = Validator::make($request->all(),[
                'reason' => 'required|string|min:5|max:200',
            ]);

        if ($validator->fails())
            return response()->json([
                'status' => 'Bad Request',
                'msg' => 'Failed to report user. Bad request',
                'errors' => $validator->errors(),
            ], 400);

        $report = new Report();
        $report->reason = $request->reason;
        $report->reported_id = $id;
        $report->reporter_id = Auth::id();
        $report->reported_at = gmdate('Y-m-d H:i:s');
        $report->save();

        return response()->json([
            'status' => 'OK',
            'msg' => 'Successful user report',
            'id' => $report->id
        ], 200);
    }

    public function suspension(int $id)
    {
        $user = User::find($id);
        if (is_null($user))
            return response()->json([
                'status' => 'Not Found',
                'msg' => 'User not found, id: '.$id,
                'errors' => ['user' => 'User not found, id: '.$id]
            ], 404);

        if (!$user->is_suspended)
            return response()->json([
                'status' => 'Conflict',
                'msg' => 'User is not suspended, id: '.$id,
                'errors' => ['user' => 'User is not suspended, id: '.$id]
            ], 409);

        return $user->suspensionEndInfo();
    }

    public function followed(int $id)
    {
        $user = User::find($id);
        if (is_null($user))
            return abort(404, 'User not found, id: '.$id);

        $followedUsers = $user->following->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'country' => $user->country,
                'city' => $user->city,
                'reputation' => $user->reputation,
                'isSuspended' => $user->is_suspended
            ];
        });

        return view('pages.followedUsers', [
            'users' => $followedUsers,
        ]);
    }
}
