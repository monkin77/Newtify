<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Database\QueryException;

class TagController extends Controller
{
    const tagStates = [
        'accepted' => 'ACCEPTED',
        'rejected' => 'REJECTED',
        'pending' => 'PENDING'
    ];

    /**
     * Show Page that contains all the tags and the user's favorite tags highlighted.
     *
     * @return View
     */
    public function showUserFavorites()
    {
        if (Auth::guest()) {
            return redirect('/login');
        }

        $userTags = Auth::user()->favoriteTags->map(function ($tag) {
            return [
                'id' => $tag->id
            ];
        });

        $tags = Tag::listTagsByState(self::tagStates['accepted'])->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name
            ];
        });

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
            'msg' => 'There is no Tag with id' . $tag_id,
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
            'msg' => 'There is no Tag with id' . $tag_id,
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

    /**
     * Add a tag to the currently authenticated user's favorite tags
     * @return \Illuminate\Http\Response 
     */
    public function addUserFavorite($tag_id)
    {
        $this->authorize('addFavorite', Tag::class);

        $tag = Tag::find($tag_id);
        if (is_null($tag)) return Response()->json([
            'status' => 'NOT FOUND',
            'msg' => 'There is no Tag with id' . $tag_id,
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

    /**
     * Remove a tag from the currently authenticated user's favorite tags
     * @return \Illuminate\Http\Response 
     */
    public function removeUserFavorite($tag_id)
    {
        $this->authorize('removeFavorite', Tag::class);

        $tag = Tag::find($tag_id);
        if (is_null($tag)) return Response()->json([
            'status' => 'NOT FOUND',
            'msg' => 'There is no Tag with id' . $tag_id,
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

    public function showFilteredTags($tag_state)
    {
        $this->authorize('showFilteredTags', Tag::class);

        $tags = Tag::listTagsByState(self::tagStates[$tag_state])->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
                'proposed_at' => $tag->proposed_at,
                'state' => $tag->state,
                'user' => [
                    'id' => $tag->user_id,
                    'name' => $tag->user->name,
                    'avatar' => $tag->user->avatar
                ]
            ];
        });

        return view('partials.tagsList', [
            'tags' => $tags,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->authorize('destroy', Tag::class);

        $tag = Tag::find($id);

        if (is_null($tag))
            return Response()->json([
                'status' => 'NOT FOUND',
                'msg' => 'There is no Tag with id' . $id,
                'tag_id' => $id
            ], 404);

        $deleted = $tag->delete();
        if (!$deleted)
            return Response()->json([
                'status' => 'Internal Server Error',
                'msg' => 'There was an error deleting the tag with id ' . $id,
            ], 500);

        return Response()->json([
            'status' => 'OK',
            'msg' => 'Successfuly removed tag',
            'tag_id' => $id,
        ], 200);
    }

    /**
     * Propose a new Tag.
     *
     * @return \Illuminate\Http\Response
     */
    public function propose(Request $request)
    {
        $this->authorize('propose', Tag::class);

        $validator = Validator::make($request->all(), [
            'tag_name' => 'required|string|min:2|unique:tag,name'
        ]);

        if ($validator->fails()) {
            return Response()->json([
                'status' => 'Bad Request',
                'msg' => 'Failed to propose a new tag. Bad Request',
            ], 400);
        }

        $tag = new Tag;
        $tag->name = $request->tag_name;
        $tag->user_id = Auth::id();
        $tag->save();

        return Response()->json([
            'status' => 'OK',
            'msg' => 'Successfuly proposed tag',
            'tag_name' => $request->tag_name,
        ], 200);
    }
}