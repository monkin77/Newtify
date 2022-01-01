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
     * @param  int $id Id of the user
     * @return View
     */
    public function show(int $id)
    {
        $user = User::find($id);
        if (is_null($user))
            return abort(404, 'User not found, id: ' . $id);

        $userInfo = [
            'id' => $id,
            'name' => $user->name,
            'email' => $user->email,
            'birthDate' => $user->birth_date,
            'isAdmin' => $user->is_admin,
            'description' => $user->description,
            'avatar' => $user->avatar,
            'country' => $user->country->getInfo(),
            'city' => $user->city,
            'isSuspended' => $user->is_suspended,
            'reputation' => $user->reputation,
        ];

        $follows = false;
        $isOwner = false;
        if (Auth::check()) {
            $follows = Auth::user()->isFollowing($id);
            $isOwner = Auth::id() == $userInfo['id'];
        }


        $areasExpertise = $user->topAreasExpertise();

        $followerCount = count($user->followers);

        $articles = $user->articles()->map(function ($article) {
            return [
                'id' => $article->content_id,
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
            'birthDate' => date('F j, Y', strtotime($userInfo['birthDate'])),
            'age' => date_diff(date_create($userInfo['birthDate']), date_create(date('d-m-Y')))->format('%y'),
            'isOwner' => $isOwner,
        ]);
    }

    /**
     * Show the form for editing the user profile.
     *
     * @param  int $id
     * @return View
     */
    public function edit(int $id)
    {
        $user = User::find($id);
        if (is_null($user))
            return abort(404, 'User not found, id: ' . $id);

        $this->authorize('update', $user);

        return view('pages.edit_profile');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $user = User::find($id);
        if (is_null($user))
            return redirect()->back()->withErrors(['user' => 'User not found, id: ' . $id]);

        $this->authorize('update', $user);

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:authenticated_user',
            'password' => 'required|string|password',
            'new_password' => 'nullable|string|min:6|confirmed',
            'birthDate' => 'nullable|string|date_format:d-m-Y|before:' . date('d-m-Y'), // before today
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
     * @param  Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, int $id): RedirectResponse
    {
        $user = User::find($id);
        if (is_null($user))
            return redirect()->back()->withErrors(['user' => 'User not found, id: ' . $id]);

        $this->authorize('delete', $user);

        $validator = Validator::make($request->all(), [
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

    /**
     * Reports a user account.
     * 
     * @param  Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request, int $id)
    {
        $user = User::find($id);
        if (is_null($user))
            return response()->json([
                'status' => 'Not Found',
                'msg' => 'User not found, id: ' . $id,
                'errors' => ['user' => 'User not found, id: ' . $id]
            ], 404);

        $this->authorize('report', $user);

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|min:5|max:200',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => 'Bad Request',
                'msg' => 'Reason must have a number of characters between 5 and 200.',
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

    /**
     * Information on the user's suspension
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function suspension(int $id)
    {
        $user = User::find($id);
        if (is_null($user))
            return response()->json([
                'status' => 'Not Found',
                'msg' => 'User not found, id: ' . $id,
                'errors' => ['user' => 'User not found, id: ' . $id]
            ], 404);

        $this->authorize('suspension', $user);

        if (!$user->is_suspended)
            return response()->json([
                'status' => 'Conflict',
                'msg' => 'User is not suspended, id: ' . $id,
                'errors' => ['user' => 'User is not suspended, id: ' . $id]
            ], 409);

        return $user->suspensionEndInfo();
    }

    /**
     * Display the given user's followed users
     * 
     * @param int $id
     * @return View
     */
    public function followed(int $id)
    {
        $user = User::find($id);
        if (is_null($user))
            return abort(404, 'User not found, id: ' . $id);

        $this->authorize('followed', $user);

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

        return view('pages.followed_users', [
            'users' => $followedUsers,
        ]);
    }

    /**
     * Return html with the user's written articles
     * 
     * @param  Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response|View
     */
    public function articles(Request $request, int $id)
    {
        $user = User::find($id);
        if (is_null($user))
            return response()->json([
                'status' => 'Not Found',
                'msg' => 'User not found, id: ' . $id,
                'errors' => ['user' => 'User not found, id: ' . $id]
            ], 404);

        $validator = Validator::make($request->all(), [
            'offset' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => 'Bad Request',
                'msg' => 'Failed to fetch user\'s articles. Bad request',
                'errors' => $validator->errors(),
            ], 400);

        $userArticles = $user->articles()->map(function ($article) {
            return [
                'title' => $article->title,
                'thumbnail' => $article->thumbnail,
                'body' => $article->body,
                'published_at' => $article->published_at,
                'likes' => $article->likes,
                'dislikes' => $article->dislikes
            ];
        })->sortByDesc('published_at');

        if (!isset($request->offset)) $request->offset = 0;

        $articles = $userArticles->slice($request->offset, $request->limit);

        return view('partials.user_articles', [
            'articles' => $articles
        ]);
    }

    public function follow(int $id)
    {
        $userToFollow = User::find($id);
        if (is_null($userToFollow))
            return response()->json([
                'status' => 'Not Found',
                'msg' => 'User not found, id: ' . $id,
                'errors' => ['user' => 'User not found, id: ' . $id]
            ], 404);

        $this->authorize('follow', $userToFollow);

        if (Auth::user()->isFollowing($id))
            return response()->json([
                'status' => 'OK',
                'msg' => 'User already followed',
                'id' => $id,
            ], 200);

        $userToFollow->followers()->attach(Auth::id());

        return response()->json([
            'status' => 'OK',
            'msg' => 'Successful user follow',
            'id' => $id,
        ], 200);
    }

    public function unfollow(int $id)
    {
        $userToUnfollow = User::find($id);
        if (is_null($userToUnfollow))
            return response()->json([
                'status' => 'Not Found',
                'msg' => 'User not found, id: ' . $id,
                'errors' => ['user' => 'User not found, id: ' . $id]
            ], 404);

        $this->authorize('unfollow', $userToUnfollow);

        if (!Auth::user()->isFollowing($id))
            return response()->json([
                'status' => 'OK',
                'msg' => 'User already not followed',
                'id' => $id,
            ], 200);

        $userToUnfollow->followers()->detach(Auth::id());

        return response()->json([
            'status' => 'OK',
            'msg' => 'Successful user unfollow',
            'id' => $id,
        ], 200);
    }
}
