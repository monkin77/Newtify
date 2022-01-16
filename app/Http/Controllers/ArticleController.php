<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Models\Content;
use App\Models\Tag;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display Create Article Form
     * 
     * @return \Illuminate\Http\Response
     */
    public function createForm() 
    {
        if (Auth::guest()) 
            return redirect('/login');

        $user = User::find(Auth::id());
        if (is_null($user)) 
            return redirect('/login');

        $authorInfo = [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'country' => $user->country->getInfo(),
            'city' => $user->city,
            'isAdmin' => $user->is_admin,
            'description' => $user->description,
            'isSuspended' => $user->is_suspended,
            'reputation' => $user->reputation,
            'topAreasExpertise' => $user->topAreasExpertise(),
        ];

        $tags = Tag::where('state', "ACCEPTED")->get()->map(function($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
            ];
        })->sortBy('name');

        return view('pages.article.create_article', [
            'author' => $authorInfo,
            'tags' => $tags,
        ]);
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
                'title' => 'required|string|min:3|max:100',
                'thumbnail' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:4096',
                'tags' => 'required|array|min:1|max:3',
                'tags.*' => 'required|string|min:1',
            ]
        );
        if ( $validator->fails() ) {
            // go back to form and refill it
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $tagsIds = [];

        foreach($request->tags as $tag) {
            $checkTag = Tag::find($tag);

            //check if is valid tag
            if (!$checkTag || $checkTag->state != 'ACCEPTED') {
                return redirect()->back()->withInput()->withErrors(['tags' => 'Invalid Tag: '.$tag]); 
            }
            array_push($tagsIds, $checkTag->id);
        }

        $content = new Content;
        $content->body = $request->body;
        $content->author_id = Auth::id();
        $content->save();

        $article = new Article;
        $article->content_id = $content->id;
        $article->title = $request->title;

        if (isset($request->thumbnail)) {
            $thumbnail = $request->thumbnail;
            $imgName = time().'.'.$thumbnail->extension();
            $thumbnail->storeAs('public/thumbnails', $imgName);
            $article->thumbnail = $imgName;
        }

        $article->save();

        $article->articleTags()->sync($tagsIds);

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
            'id' => $article->content_id,
            'title' => $article->title,
            'thumbnail' => $article->thumbnail,
            'body' => $article->body,
            'published_at' => $article->published_at,
            'likes' => $article->likes,
            'dislikes' => $article->dislikes,
        ];

        $author = $article->author;

        if (isset($author))
            $authorInfo = [
                'id' => $author->id,
                'name' => $author->name,
                'avatar' => $author->avatar,
                'country' => $author->country->getInfo(),
                'city' => $author->city,
                'isAdmin' => $author->is_admin,
                'description' => $author->description,
                'isSuspended' => $author->is_suspended,
                'reputation' => $author->reputation,
                'topAreasExpertise' => $author->topAreasExpertise(),
            ];
        else $authorInfo = null; // Anonymous, account deleted

        $is_author = isset($author) ? $author->id === Auth::id() : false;

        // TODO: "load more" thing for comments too
        $comments = $article->comments->map(function ($comment) {
            $commentAuthor = $comment->author;
            $comment_published_at = date('F j, Y', /*, g:i a',*/ strtotime( $comment['published_at'] ) ) ;  

            return [
                'id' => $comment->content_id,
                'body' => $comment->body,
                'likes' => $comment->likes,
                'dislikes' => $comment->dislikes,
                'published_at' =>$comment_published_at,
                'author' => isset($commentAuthor) ? [
                    'id' => $commentAuthor->id,
                    'name' => $commentAuthor->name,
                    'avatar' => $commentAuthor->avatar,
                ] : null,
            ];
        })->sortByDesc('likes')->take(10);

        $tags = $article->articleTags->map(function($tag) {
            return [
                'name' => $tag->name,
            ];
        })->sortBy('name');

        $user = Auth::user();
        $is_admin = Auth::user() ? $user->is_admin : false;

        $feedback = Auth::check() 
            ? Feedback::where('user_id', '=', Auth::id())->where('content_id', '=', $id)->first()
            : null;

        $liked = false;
        $disliked = false;

        if (!is_null($feedback)){
            $liked = $feedback->is_like;
            $disliked = !$feedback->is_like;
        }

        return view('pages.article.article', [
            'article' => $articleInfo,
            'author' => $authorInfo,
            'comments' => $comments,
            'tags' => $tags,
            'is_author' => $is_author,
            'is_admin' => $is_admin,
            'liked' => $liked,
            'disliked' => $disliked
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

        $content = Content::find($article->content_id);
        if (is_null($content))
            return abort(404, 'Content not found, id: '.$article->content_id);

        $this->authorize('update', $content);

        $articleInfo = [
            'content_id' => $article->content_id,
            'title' => $article->title,
            'thumbnail' => $article->thumbnail,
            'body' => $article->body,
        ];

        $articleTags = $article->articleTags->map(function($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
            ];
        })->sortBy('name');

        $tags = Tag::where('state', "ACCEPTED")->get()->map(function($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
            ];
        })->sortBy('name');
        
        $author = $article->author;
        $authorInfo = [
            'id' => $author->id,
            'name' => $author->name,
            'avatar' => $author->avatar,
            'country' => $author->country->getInfo(),
            'city' => $author->city,
            'isAdmin' => $author->is_admin,
            'description' => $author->description,
            'isSuspended' => $author->is_suspended,
            'reputation' => $author->reputation,
            'topAreasExpertise' => $author->topAreasExpertise(),
        ];

        return view('pages.article.edit_article', [
            'article' => $articleInfo,
            'tags' => $tags,
            'articleTags' => $articleTags,
            'author' => $authorInfo,
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

        $this->authorize('update', $content);

        $validator = Validator::make($request -> all(),
        [
            'body' => 'nullable|string|min:10',
            'title' => 'nullable|string|min:1|max:255',
            'thumbnail' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:4096',
            'tags' => 'required|array|min:1|max:3',
            'tags.*' => 'required|string|min:1',
        ]);

        if ( $validator->fails() ) {
            // go back to form and refill it
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        if (isset($request->body)) $content->body = $request->body;
        if (isset($request->title)) $article->title = $request->title;
        if (isset($request->thumbnail)) {
            $newThumbnail = $request->thumbnail;
            $oldThumbnail = $article->thumbnail;

            $imgName = time().'.'.$newThumbnail->extension();
            $newThumbnail->storeAs('public/thumbnails', $imgName);
            $article->thumbnail = $imgName;

            if (!is_null($oldThumbnail))
                Storage::delete('public/thumbnails/'.$oldThumbnail);
        }

        $tagsIds = [];

        foreach($request->tags as $tag) {
            $checkTag = Tag::find($tag);

            //check if is valid tag
            if (!$checkTag || $checkTag->state != 'ACCEPTED') {
                return redirect()->back()->withInput()->withErrors(['tags' => 'Invalid Tag: '.$tag]); 
            }
            array_push($tagsIds, $checkTag->id);
        }

        $content->save();

        $article->save();

        $article->articleTags()->sync($tagsIds);

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

        $this->authorize('delete', $content);

        $user = Auth::user();
        $owner_id = $content->author_id;

        $has_feedback = ($content->likes != 0 || $content->dislikes != 0);
        $has_comments = !$article->comments->isEmpty();

        if ($user->id != $owner_id && !$user->is_admin) {
            return redirect()->back()->withErrors(['user' => "Only the owner of the article can delete it"]);
        }

        if (($has_feedback || $has_comments) && !$user->is_admin) {
            // cannot delete if is not admin or it has feedback and comments
            return redirect()->back()->withErrors(['content' => "You can't delete an article with feedback"]);
        }

        $deleted = $article->delete();

        if ($deleted) 
            return redirect('/');
        else 
            return redirect("/article/${id}");
    }
}
