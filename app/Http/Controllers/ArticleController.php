<?php

namespace App\Http\Controllers;


use App\Models\Article;
use App\Models\Content;
use App\Models\Admin;   // Delete this, im just using it while i dont put policy to delete Article
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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
     * Display Create Article Form
     * 
     * @return \Illuminate\Http\Response
     */
    public function createForm() 
    {
        // do this in policy (?)
        if (Auth::guest()) {
            return redirect('/login');
        }

        return view('pages.create_article');
    }

    /**
     * Creates a new Article instance.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        if (Auth::guest()) {
            return redirect('/articles');
        }
        
        $validator = Validator::make($request -> all(),
            [
                'body' => 'required|string|min:10',
                'title' => 'required|string|min:1|max:255',
                'thumbnail' => 'nullable|file|max:5000'
            ]
            );

        if ( $validator->fails() ) {
            // go back to form and refill it
            return redirect()->back()->withInput()->withErrors($request);
        }
        
        $content = new Content;
        $content->body = $request->body;
        $content->author_id = Auth::id();
        $content->save();

        $article = new Article;
        
        $article->content_id = $content->id;
        $article->title = $request->title;
        $article->save();

        return redirect("/article/$article->content_id");
    }

    /**
     * Display Article Page.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id) : RedirectResponse
    {
        $article = Article::find($id);
        if (is_null($article)) 
            return redirect()->back()->withErrors(['article' => 'Article not found, id:'.$id]);

        $validator = Validator::make($request -> all(),
        [
            'body' => 'nullable|string|min:10',
            'title' => 'nullable|string|min:1|max:255',
            'thumbnail' => 'nullable|file|max:5000'
        ]);

        if ( $validator->fails() ) {
            // go back to form and refill it
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $content = Content::find($article->content_id);

        if (is_null($content)) 
            return redirect()->back()->withErrors(['content' => 'Content not found, id:'.$id]);

        if (isset($request->body)) $content->body = $request->body;
        if (isset($request->title)) $article->title = $request->title;
        if (isset($request->thumbnail)) $article->thumbnail = $request->thumbnail;

        $content->save();
        $article->save();
        
        return redirect("/article/${id}");
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
            return redirect()->back()->withErrors(['article' => 'Article not found, id:'.$id]);

        $content = Content::find($article->content_id);

        $user = Auth::user();
        $owner_id = $content->author_id;

        $has_feedback = ($content->likes != 0 || $content->dislikes != 0);
        $has_comments = $article->comments()->isEmpty();

        // cannot delete if is not admin and it has feedback and comments
        /*if (($has_feedback || $has_comments) || !$user->is_admin){
            return redirect()->back()->withErrors(['content' => "You can't delete an article with likes/dislikes"]);
        } else if ($user->id != $owner_id || !$user->is_admin) {
            return redirect()->back()->withErrors(['user' => "You are not the owner of the article so you can't delete it"]);
        } */

        $deleted = $article->delete();
        if ($deleted) 
            return redirect('/articles');
        else 
            return redirect("/article/${id}");
    }
}
