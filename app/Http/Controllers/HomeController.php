<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Displays Home page
     * 
     * @return \Illuminate\Http\Response
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
<<<<<<< HEAD
            'id' => $article->id,
=======
>>>>>>> Articles in homepage added
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
}
