<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
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

        $tags = Tag::listTagsByState('ACCEPTED')->map(function($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
            ];
        });

        return view('pages.home', [
            'articles' => $results['articles'],
            'canLoadMore' => $results['canLoadMore'],
            'tags' => $tags,
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

        // Sort by top posts of the day
        if ($type === 'trending' || ($type === 'recommended' && Auth::guest()))
        {
            $sortedArticles = $articles->sortBy([
                fn ($a, $b) => $this->compareArticleDates($a, $b),
                fn ($a, $b) => $this->compareArticleFeedback($a, $b)
            ]);
        }

        else if ($type === 'recommended')
        {
            $sortedArticles = $articles->sortBy([
                function ($a, $b) {
                    $isAFollowed = Auth::user()->followers->pluck('id')->contains($a->author->id);
                    $isBFollowed = Auth::user()->followers->pluck('id')->contains($b->author->id);

                    return $isBFollowed <=> $isAFollowed;
                },
                function ($a, $b) {
                    $favTagsA = $a->articleTags->filter(
                        fn ($tag) => Auth::user()->favoriteTags->pluck('id')->contains($tag->id)
                    );

                    $favTagsB = $b->articleTags->filter(
                        fn ($tag) => Auth::user()->favoriteTags->pluck('id')->contains($tag->id)
                    );

                    return count($favTagsB) <=> count($favTagsA);
                },
                fn ($a, $b) => $this->compareArticleDates($a, $b),
                fn ($a, $b) => $this->compareArticleFeedback($a, $b)
            ]);
        }

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

    private function compareArticleDates($a, $b)
    {
        $d1 = date_create($a->published_at);
        $d2 = date_create($b->published_at);

        $daysDiff = date_diff($d1, $d2)->format("%a");
        if ($daysDiff === "0") return 0;
        return $d2 <=> $d1;
    }

    private function compareArticleFeedback($a, $b)
    {
        $karma1 = $a->likes - $a->dislikes;
        $karma2 = $b->likes - $b->dislikes;
        return $karma2 <=> $karma1;
    }
}
