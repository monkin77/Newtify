<?php

namespace App\Http\Controllers;


use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::orderBy('id')->get();
        return view('pages.articles', ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // not authenticated so redirect to create article form
        if (Auth::guest()) {
            return redirect('/article');
        }
        
        $validator = Validator::make($request -> all(),
            [
                'body' => 'required|string',
                'title' => 'required|string',
            ]
            );

        if ( $validator->fails() ) {
            return redirect()->back()->withErrors($validator);
        }
        
        $content = new Content;
        $content->body = $request->body;
        $content->author_id = Auth::id();
        $content->save();

        $article = new Article;
        $article->content_id = $content->id;
        $article->save();

        return redirect('/article/'.$article->content_id);
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
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show($id)//Article $article)
    {
        $article = Article::find($id);
        return view('partials.article', ['article' => $article]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
}
