<?php

namespace App\Http\Controllers;


use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{

    /**
     * Gets all the articles by id.
     * Probably change this in order to get just a few articles
     * Order by a filter.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::orderBy('id')->get();
        return view('pages.articles', ['articles' => $articles]);
    }

    /**
     * Creates a new Article instance.
     *
     * @param array $data
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
        // not authenticated so can't create articles. redirect to articles page
        // probably should not check it here since this is already the creation of the article
        // do this in the show form for article?
        if (Auth::guest()) {
            return redirect('/articles');
        }
        
        $validator = Validator::make($request -> all(),
            [
                'body' => 'required|string',
                'title' => 'required|string',
                'thumbnail' => 'nullable|file|max:5000'
            ]
            );

        if ( $validator->fails() ) {
            // go back to form and refill it
            return redirect()->back()->withInput();
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
 
    }

    /**
     * Display Article Page.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)//Article $article)
    {
        $article = Article::find($id);
        if (is_null($article)) 
            return abort(404, 'Article not found, id: '.$id);

        // Should i check if its the owner and send information about that
        // in order to place an edit button in the blade page?

        return view('pages.article', [
            'article' => $article
        ]);
    }

    /**
     * Show the form for editing an Article.
     *
     * @param  \App\Models\Article $article
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $article = Article::find($id);
        if (is_null($article)) 
            return abort(404, 'Article not found, id: '.$id);

        // should we check if the user is the owner here?
        return view('pages.edit_article', [
            'article' => $article
        ]);
    }

    /**
     * Updates an Article.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $article = Article::find($id);
        if (is_null($article)) 
            return abort(404, 'Article not found, id: '.$id);

        $validator = Validator::make($request -> all(),
        [
            'body' => 'nullable|string',
            'title' => 'nullable|string',
            'thumbnail' => 'nullable|file|max:5000'
        ]
        );

        $content = Content::find($article->content_id);

        if ( $validator->fails() ) {
            // go back to form and refill it
            return redirect()->back()->withInput();
        }

        if (isset($request->body)) $content->body = $request->body;
        if (isset($request->title)) $article->body = $request->title;
        if (isset($request->thumbnail)) $article->thumbnail = $request->thumbnail;

        $content->save();
        $article->save();
        
        return redirect("article/${id}");
    }


    /**
     * Deletes an Article.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        $article = Article::find($id);
        if(is_null($article))
            return abort(404, 'Article not found, id: '.$id);

        $content = Content::find($article->content_id);

        $admin = Admin::find($id);
        // its not the owner neither an Admin so it can't delete it
        if ($content->author_id != Auth::id() || is_null($admin)) 
            return redirect()->back();

        $deleted = $article->delete();
        if ($deleted) 
            return redirect('/');
        else 
            return redirect("/article/${id}");
    }
}
