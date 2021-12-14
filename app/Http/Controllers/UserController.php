<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  $id Id of the user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
