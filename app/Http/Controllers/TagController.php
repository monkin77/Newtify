<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    /**
     * Show Page that contains all the tags and the user's favorite tags highlighted.
     *
     * @return View
     */
    public function showUserFavorites()
    {
        // Could be a policy?
        if (Auth::guest()) {
            return redirect('/login');  // This is returning a 401 Unauthorized in the openAPI. Should we change it?
        }

        $userTags = Auth::user()->favoriteTags->map(function ($tag) {
            return [
                'id' => $tag->id
            ];
        });

        $tags = Tag::listAcceptedTags();

        return view('pages.tagsList', [
            'tags' => $tags,
            'userTags' => $userTags
        ]);
    }

    /**
     * Accept a Tag
     *
     * @return \Illuminate\Http\Response
     */
    public function accept(int $tag_id)
    {
        $this->authorize('accept', Tag::class);

        $tag = Tag::find($tag_id);
        if (is_null($tag)) return Response()->json([
            'status' => 'NOT FOUND',
            'tag_id' => $tag_id
        ], 404);

        if ($tag->state == 'ACCEPTED')
            return Response()->json([
                'status' => 'OK',
                'msg' => 'Tag was already accepted',
                'tag_id' => $tag_id
            ], 200);

        $tag->state = 'ACCEPTED';
        $tag->save();

        return Response()->json([
            'status' => 'OK',
            'msg' => 'Successfuly accepted tag',
            'tag_id' => $tag_id
        ], 200);
    }

    /**
     * Reject a Tag
     *
     * @return \Illuminate\Http\Response
     */
    public function reject(int $tag_id)
    {
        $this->authorize('reject', Tag::class);

        $tag = Tag::find($tag_id);
        if (is_null($tag)) return Response()->json([
            'status' => 'NOT FOUND',
            'tag_id' => $tag_id
        ], 404);

        if ($tag->state == 'REJECTED')
            return Response()->json([
                'status' => 'OK',
                'msg' => 'Tag was already rejected',
                'tag_id' => $tag_id
            ], 200);

        $tag->state = 'REJECTED';
        $tag->save();

        return Response()->json([
            'status' => 'OK',
            'msg' => 'Successfuly rejected tag',
            'tag_id' => $tag_id
        ], 200);
    }

    public function addUserFavorite($tag_id)
    {
        $this->authorize('addFavorite', Tag::class);

        $tag = Tag::find($tag_id);
        if (is_null($tag)) return Response()->json([
            'status' => 'NOT FOUND',
            'tag_id' => $tag_id
        ], 404);

        $user = Auth::user();

        if ($tag->isFavorite($user->id))
            return Response()->json([
                'status' => 'OK',
                'msg' => 'Tag already added to favorites, id: ' . $tag_id,
            ], 200);



        $tag->favoriteUsers()->attach($user->id);

        return Response()->json([
            'status' => 'OK',
            'msg' => 'Successfuly added tag to user favorites',
            'tag_id' => $tag_id,
        ], 200);
    }

    public function removeUserFavorite($tag_id)
    {
        $this->authorize('removeFavorite', Tag::class);

        $tag = Tag::find($tag_id);
        if (is_null($tag)) return Response()->json([
            'status' => 'NOT FOUND',
            'tag_id' => $tag_id
        ], 404);

        $user = Auth::user();

        if (!$tag->isFavorite($user->id))
            return Response()->json([
                'status' => 'OK',
                'msg' => 'Tag was not a favorite, id: ' . $tag_id,
            ], 200);



        $tag->favoriteUsers()->detach($user->id);

        return Response()->json([
            'status' => 'OK',
            'msg' => 'Successfuly removed tag from user favorites',
            'tag_id' => $tag_id,
        ], 200);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
