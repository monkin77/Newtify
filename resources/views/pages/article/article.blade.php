@extends('layouts.app')

@section('content')
    
    <div class="article-container container-fluid bg-light">

        <div class="d-flex flex-row my-2 h-100">
            
            <div class="articleInfoContainer d-flex flex-column p-3 mb-0 text-dark" >

                <div class="flex-row" id="article-header">

                    <div class="d-flex flex-row w-100">
                        <h1 class="flex-column m-0 w-75"> 
                            {{ $article['title'] }}
                        </h1>

                        <h2 class="flex-column w-25 mx-3">
                            @if ($is_author)
                                <a href="{{ route('editArticle', ['id' => $article['id']])}}">
                                    <i class="fas fa-edit me-4"></i>
                                </a>
                            @endif
                            @if ($is_author || $is_admin)
                                <form 
                                    name="deleteArticleForm" id="deleteArticleForm" 
                                    method="POST"
                                    action="{{ route('article', ['id' => $article['id']]) }}">

                                    @csrf
                                    @method('DELETE')
                                    
                                    @if ($errors->has('article'))
                                        <div class="alert alert-danger mt-2 mb-0 p-0 w-50 text-center" role="alert">
                                            <p class="mb-0">{{ $errors->first('article') }}</p>
                                        </div>
                                    @endif
                                    @if ($errors->has('content'))
                                        <div class="alert alert-danger mt-2 mb-0 p-0 w-50 text-center" role="alert">
                                            <p class="mb-0">{{ $errors->first('content') }}</p>
                                        </div>
                                    @endif
                                    @if ($errors->has('user'))
                                        <div class="alert alert-danger mt-2 mb-0 p-0 w-50 text-center" role="alert">
                                            <p class="mb-0">{{ $errors->first('user') }}</p>
                                        </div>
                                    @endif

                                    <button type="submit" class="btn btn-transparent py-0 my-0 mt-2"> 
                                        <i class="fas fa-trash fa-3x text-danger" ></i>
                                    </button>

                                </form>
                                
                            @endif
                        <h2>
                    </div>

                    @php
                        $article_published_at = date('F j, Y', /*, g:i a',*/ strtotime( $article['published_at'] ) )   
                    @endphp
                    {{ $article_published_at }}

                </div>

                <p class="flex-row mt-3 mb-1 h-25"> 

                    @foreach ($tags as $tag)
                        @include('partials.tag', ['tag' => $tag ])
                    @endforeach

                    <i class="fas fa-thumbs-up ps-5"> {{ $article['likes'] }}</i>
                    <i class="fas fa-thumbs-down ps-3"> {{ $article['dislikes'] }}</i>
                    
                    <i class="fas fa-share-alt ms-4"></i>
                </p>

                <div class="flex-row h-50 mb-5">
                    <img class="h-100 w-50" src={{
                        isset($article['thumbnail']) ?
                        $article['thumbnail']
                        :
                        $articleImgPHolder
                    }}>
                </div>
        
                <div class="flex-row h-75">
                    {{ $article['body'] }}
                </div>

            </div>  

            <div class="author-container flex-col p-3 text-dark">
                @include('partials.authorInfo', [
                    'author' => $author,
                    'isOwner' => $is_author
                ])
            </div>

        </div>

        <div class="d-flex flex-column" id="comments-section">
            <div class="flex-row mt-3 p-0">
                <h3 class="m-0">Comments</h3>
            </div>

            <div class="h-50">
                @if (Auth::check())
                <div class="d-flex flex-row mx-0 my-3 p-0 w-75"> 
                    <div class="flex-column h-100 commentHeader mx-5 my-0 p-0">
                        <img src={{
                            isset(Auth::user()->avatar) ?
                            Auth::user()->avatar
                            :
                            $userImgPHolder
                        }}>
                        <p>You</p>
                    </div>

                    <div class="flex-column m-0 p-0 w-100">
                        <form action="/make_comment.php" method="POST" id="comment_form" class="m-0">
                            <textarea class="flex-column m-0 p-2" placeholder="Type here"></textarea>
                            <button type="button">
                                Comment
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                @foreach ($comments as $comment)
                    @include('partials.content.comment', ['comment' => $comment])
                @endforeach
            
            </div>

        </div>
    </div>

@endsection