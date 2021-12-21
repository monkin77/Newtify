<?php

namespace App\Http\Controllers;


use App\Models\Article;
use App\Models\Content;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{

    /**
     * Gets all the articles.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $articles = Article::get();
        
        $articlesInfo = $articles->map(function ($article) {
            return [
            'title' => $article->title,
            'thumbnail' => $article->thumbnail,
            'body' => $article->body,
            'published_at' => $article->published_at,
            'likes' => $article->likes,
            'dislikes' => $article->dislikes,
            ];
        })->sortByDesc('published_at')->take(5);

        return view('pages.articles', ['articles' => $articlesInfo]);
    }

    /**
     * Display Create Article Form
     * 
     * @return \Illuminate\Http\Response
     */
    public function createForm() 
    {
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
            return redirect('/login');
        }
        
        $validator = Validator::make($request -> all(),
            [
                'body' => 'required|string|min:10',
                'title' => 'required|string|min:3|max:255',
                'thumbnail' => 'nullable|file|max:5000',
                'tags' => 'required|array|min:1|max:3',
                'tags.*' => 'required|integer|distinct|min:0',
            ]
            );

        if ( $validator->fails() ) {
            // go back to form and refill it
            return redirect()->back()->withInput()->withErrors($request);
        }

        foreach($request->tags as $tag) {
            $checkTag = Tag::find($tag);
            //check if is valid tag
            if (!$checkTag) {
                return redirect()->back()->withInput()->withErrors(['tags' => 'Tag not found: '.$tag->name]); 
            }
        }
        
        $content = new Content;
        $content->body = $request->body;
        $content->author_id = Auth::id();
        $content->save();

        $article = new Article;
        if (isset($request->thumbnail)) $article->thumbnail = $request->thumbnail;
        $article->content_id = $content->id;
        $article->title = $request->title;

        $article->save();

        $article->articleTags()->sync($request->tags);

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

        $articleInfo = [
            'title' => $article->title,
            'thumbnail' => $article->thumbnail,
            'body' => $article->body,
            'published_at' => $article->published_at,
            'likes' => $article->likes,
            'dislikes' => $article->dislikes,
        ];

        $author = $article->author()->first();
        $authorInfo = [
            'id' => $author->id,
            'name' => $author->name,
            'avatar' => $author->avatar,
            'country' => $author->country,
            'city' => $author->city,
            'isAdmin' => $author->is_admin,
            'description' => $author->description,
            'isSuspended' => $author->is_suspended,
            'reputation' => $author->reputation,
            'topAreasExpertise' => $author->topAreasExpertise(),
        ];

        $is_author = $author->id === Auth::id();

        // we could do the "load more" thing for comments to?
        $comments = $article->comments->map(function ($comment) {
            $author = $comment->author()->first();
            return [
                'body' => $comment->body,
                'likes' => $comment->likes,
                'dislikes' => $comment->dislikes,
                'authorId' => $author->id,  //for edit key
                'authorName' => $author->name,
                'authorAvatar' => $author->avatar,  
            ];
        })->sortByDesc('published_at')->take(10);

        $tags = $article->articleTags()->get()->map(function($tag){
            return [
                'name' => $tag->name,
            ];
        })->sortBy('name');

        return view('pages.article', [
            'article' => $articleInfo,
            'author' => $authorInfo,
            'comments' => $comments,
            'tags' => $tags,
            'is_author' => $is_author,
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

        $this->authorize('update', $article);

        $articleInfo = [
            'content_id' => $article->content_id,
            'title' => $article->title,
            'thumbnail' => $article->thumbnail,
            'body' => $article->body,
        ];

        $tagsInfo = $article->articleTags()->get()->map(function($tag){
            return [
                'id' => $tag->id,
                'name' => $tag->name,
            ];
        })->sortBy('name');

        return view('pages.edit_article', [
            'article' => $articleInfo,
            'tags' => $tagsInfo,
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

        $content = Content::find($article->content_id);
        if (is_null($content)) 
            return redirect()->back()->withErrors(['content' => 'Content not found, id:'.$id]);

        $this->authorize('update', $article);

        $validator = Validator::make($request -> all(),
        [
            'body' => 'nullable|string|min:10',
            'title' => 'nullable|string|min:1|max:255',
            'thumbnail' => 'nullable|file|max:5000',
            'tags' => 'nullable|array|min:1|max:3',
            'tags.*' => 'nullable|integer|min:0',
        ]);

        if ( $validator->fails() ) {
            // go back to form and refill it
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        if (isset($request->body)) $content->body = $request->body;
        if (isset($request->title)) $article->title = $request->title;
        if (isset($request->thumbnail)) $article->thumbnail = $request->thumbnail;
        
        $content->author_id = Auth::id();
        $content->save();
        
        $article->content_id = $content->id;
        $article->save();

        if (isset($request->tags)) {
            foreach($request->tags as $tag) {
                $checkTag = Tag::find($tag);
                //check if is valid tag
                if (!$checkTag) {
                    return redirect()->back()->withInput()->withErrors(['tags' => 'Tag not found: '.$tag->name]); 
                }
            }
            $article->articleTags()->sync($request->tags);
        }
        
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
        if (is_null($content)) 
            return redirect()->back()->withErrors(['content' => 'Content not found, id:'.$id]);

        $this->authorize('delete', $article);

        $user = Auth::user();
        $owner_id = $content->author_id;

        $has_feedback = ($content->likes != 0 || $content->dislikes != 0);
        $has_comments = !$article->comments->isEmpty();

        // cannot delete if is not admin or it has feedback and comments
        if (($has_feedback || $has_comments) && !$user->is_admin){
            return redirect()->back()->withErrors(['content' => "You can't delete an article with feedback"]);
        } else if ($user->id != $owner_id && !$user->is_admin) {
            return redirect()->back()->withErrors(['user' => "Only the owner of the article can delete it"]);
        } 

        $deleted = $article->delete();
        if ($deleted) 
            return redirect('/articles');
        else 
            return redirect("/article/${id}");
    }
}
