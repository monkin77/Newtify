<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    public function searchUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|string',
            'offset' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => 'Bad Request',
                'msg' => 'Failed to search users. Bad request',
                'errors' => $validator->errors(),
            ], 400);

        $users = $this->getUserSearch($request->value, $request->offset, $request->limit);

        return view('partials.user_search', [
            'users' => $users
        ]);
    }

    public function searchArticles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|string',
            'offset' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => 'Bad Request',
                'msg' => 'Failed to search articles. Bad request',
                'errors' => $validator->errors(),
            ], 400);

        $articles = $this->getArticleSearch($request->value, $request->offset, $request->limit);

        return view('partials.article_search', [
            'articles' => $articles
        ]);
    }

    private function getUserSearch(string $value, $offset = 0, $limit = null)
    {
        $rawUsers = User::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$value])
            ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$value])
            ->get()->slice($offset, $limit);

        return $rawUsers->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'description' => $user->description,
                'avatar' => $user->avatar,
                'country' => $user->country,
                'city' => $user->city,
                'reputation' => $user->reputation,
            ];
        });
    }

    private function getArticleSearch(string $value, $offset = 0, $limit = null)
    {
        $rawArticles = Article::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$value])
            ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$value])
            ->get()->slice($offset, $limit);

        return $rawArticles->map(function ($article) {
            return [
                'id' => $article->id,
                'title' => $article->title,
                'thumbnail' => $article->thumbnail,
                'body' => $article->body,
                'published_at' => $article->published_at,
                'likes' => $article->likes,
                'dislikes' => $article->dislikes
            ];
        });
    }
}
