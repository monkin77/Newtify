<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    /**
     * Displays the home page
     * 
     * @return View
     */
    public function show()
    {
        $type = Auth::check() ? 'recommended' : 'trending';
        $results = $this->filterByType($type, 0, 5);

        return view('pages.home', [
            'articles' => $results['articles'],
            'canLoadMore' => $results['canLoadMore'],
        ]);
    }

    /**
     * Return a partial with the filtered articles
     * 
     * @param  Illuminate\Http\Request  $request
     * @return Response
     */
    public function filter(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'type' => ['nullable', 'string', Rule::in(['trending', 'recent', 'recommended'])],
            'tags' => 'nullable|array',
            'tags.*' => [
                'string',
                Rule::exists('tag', 'name')->where('state', 'ACCEPTED')
            ],
            'minDate' => 'nullable|string|date_format:Y-m-d|before:'.date('Y-m-d'),
            'maxDate' => 'nullable|string|date_format:Y-m-d|before:'.date('Y-m-d'),
            'offset' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => 'Bad Request',
                'msg' => 'Failed to filter articles. Bad request',
                'errors' => $validator->errors(),
            ], 400);

        if (isset($request->minDate)) $minTimestamp = strtotime($request->minDate);
        if (isset($request->maxDate)) $maxTimestamp = strtotime($request->maxDate);

        if (isset($request->minDate) && isset($request->maxDate) && $maxTimestamp < $minTimestamp)
            return response()->json([
                'status' => 'Bad Request',
                'msg' => 'Failed to filter articles. Bad request',
                'errors' => ['maxDate' => 'Max date cannot be after Min date'],
            ], 400);

        $articles = Article::all();

        if (isset($request->tags)) {
            $articles = $articles->filter(function ($article) use ($request) {
                $tags = $article->articleTags;
                foreach ($tags as $tag) {
                    if (in_array($tag->name, $request->tags)) return true;
                }
                return false;
            });
        }

        if (isset($request->minDate))
            $articles = $articles->filter(function ($article) use ($minTimestamp) {
                $timestamp = strtotime($article->published_at);
                return $timestamp >= $minTimestamp;
            });

        if (isset($request->maxDate))
            $articles = $articles->filter(function ($article) use ($maxTimestamp) {
                $timestamp = strtotime($article->published_at);
                return $timestamp <= $maxTimestamp;
            });

        $results = $this->filterByType($request->type, $request->offset, $request->limit, $articles);
        return response()->json([
            'html' => view('partials.content.articles', [
                'articles' => $results['articles'],
                'canLoadMore' => $results['canLoadMore'],
            ])->render(),
            'canLoadMore' => $results['canLoadMore']
        ], 200);
    }

    private function filterByType($type = 'trending', $offset = 0, $limit = null, $articles = null)
    {
        if (is_null($articles))
            $articles = Article::all();

        // TODO: Implement recommended articles
        if ($type === 'trending' || $type === 'recommended')
            $sortedArticles = $articles->sortByDesc(function ($article) {
                return $article->likes - $article->dislikes;
            });

        else if ($type === 'recent')
            $sortedArticles = $articles->sortByDesc('published_at');

        else $sortedArticles = $articles;

        $sortedArticles = $sortedArticles->skip($offset);
        $canLoadMore = is_null($limit) ? false : $sortedArticles->count() > $limit;
        $results = $sortedArticles->take($limit)
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'thumbnail' => $article->thumbnail,
                    'body' => $article->body,
                    'published_at' => $article->published_at,
                    'likes' => $article->likes,
                    'dislikes' => $article->dislikes,
                ];
            });

        return [
            'articles' => $results,
            'canLoadMore' => $canLoadMore,
        ];
    }
}
