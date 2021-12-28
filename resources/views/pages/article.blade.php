@extends('layouts.app')

@section('content')
    
    <div class="article-container container bg-light">

        <div class="d-flex flex-row my-2 h-100">
            
            <div class="d-flex flex-column w-75 p-3 mb-0 text-dark" >

                <div class="flex-row" id="article-header">

                    <div class="d-flex flex-row w-100">
                        <h1 class="flex-column m-0 w-75"> 
                            {{ $article['title'] }}  jdioewjdiwajswioa dwioajdwaiojswa dwjoiasjiwaoi dwaoijswa
                        </h1>

                        <h2 class="flex-column mx-3">
                            @if ($is_author)
                                <i class="iconify" data-icon="bi:pencil-fill"></i>
                                <i class="iconify" data-icon="bi:trash-fill"></i>
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

                    <i class="fa fa-thumbs-up ps-5"> {{ $article['likes'] }}</i>
                    <i class="fa fa-thumbs-down ps-3"> {{ $article['dislikes'] }}</i>
                    
                    <i class="iconify ms-5" data-icon="bi:share-fill"></i>
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

            <div class="flex-col w-25 p-3 text-dark" id="author-container">
                <div class="flex-row mt-1">
                    <h2>Author</h2>
                </div>

                @if (isset($author))
                    <div class="d-flex flex-row mb-3">
                        <div class="flex-col w-25" style="margin-right: 1em;">
                            <img id="authorAvatar" class="h-100" src={{
                                isset($author['thumbnail']) ?
                                $author['thumbnail']
                                :
                                $userImgPHolder
                            }}>
                        </div>
                        <div class="flex-col w-75" id="author-header" style="padding-bottom: 0;">
                            <h4 class="mb-2"> {{ $author['name'] }} </h4>
                            <p> @if (isset($author['city']))
                                    {{ $author['city'] }}, {{ $author['country']->name }}
                                @else
                                    {{ $author['country']->name }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex-row mb-1">
                        <p class="text-secondary">Reputation: {{ $author['reputation'] }} </p>
                    </div>

                    <div class="flex-row my-3">
                        <p>{{ $author['description'] }}</p>
                    </div>
 
                    <div class="flex-row my-5">
                        <h3>Areas of Expertise </h3>
                        @foreach ($author['topAreasExpertise'] as $areaExpertise) 
                            <p>{{ $areaExpertise['tag_name'] }} </p>
                        @endforeach
                    </div>
                @else
                    <div class="d-flex flex-row mb-3">
                        <div class="flex-col w-25" style="margin-right: 1em;">
                            <img id="authorAvatar" class="h-100" src={{ $userImgPHolder }}>
                        </div>
                        <div class="flex-col w-75" id="author-header" style="padding-top: 1em;">
                            <h4 class="mb-2"><i>Anonymous</i></h4>
                        </div>
                    </div>

                    <div class="flex-row my-3" style="padding-top: 50%">
                        <p>The author has deleted his account</p>
                    </div>
                @endif
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
                        <img src= {{
                            isset(Auth::user()->avatar) ?
                            Auth::user()->avatar
                            :
                            $userImgPHolder
                        }}
                        >
                        You
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
