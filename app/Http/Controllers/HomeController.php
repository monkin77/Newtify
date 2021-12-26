<?php

namespace App\Http\Controllers;

use App\Models\Article;
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
        $numArticles = 5;
        $articles = $this->getArticles($numArticles);

        return view('pages.home', [
            'articles' => $articles,

        ]);
    }

    public function getArticles($numArticles) {
        $articles = Article::get();
        
        // Should i check if its the owner and send information about that
        // in order to place an edit button in the blade page?
        $articlesInfo = $articles->map(function ($article) {
            return [
            'id' => $article->id,
            'title' => $article->title,
            'thumbnail' => $article->thumbnail,
            'body' => $article->body,
            'published_at' => $article->published_at,
            'likes' => $article->likes,
            'dislikes' => $article->dislikes,
            ];
        })->sortByDesc('published_at')->take($numArticles);

        return $articlesInfo;
    }

    /**
     * Return a partial with the filtered articles
     * 
     * @param  Illuminate\Http\Request  $request
     * @return View
     */
    public function filter(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'type' => ['nullable', 'string', Rule::in(['trending', 'recent', 'recommended'])],
            'tags' => 'nullable|array',
            'tags.*' => 'integer|min:0',
            'offset' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => 'Bad Request',
                'msg' => 'Failed to filter articles. Bad request',
                'errors' => $validator->errors(),
            ], 400);

        
    }
}
