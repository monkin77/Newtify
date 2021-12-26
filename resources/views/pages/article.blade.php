@extends('layouts.app')

@section('content')
    
    <div class="article-container container bg-light">

        <div class="d-flex flex-row my-2 h-100">
            
            <div class="d-flex flex-column w-75 p-3 mb-0 text-dark" >

                <div class="flex-row" >

                    <h1 class="m-0"> 
                        {{ $article['title'] }} 
                        @if ($is_author)
                            <i class="iconify" style="width: 0.8em; height: 0.7em;" data-icon="bi:pencil-fill"></i>
                            <i class="iconify" style="width: 0.8em; height: 0.7em" data-icon="bi:trash-fill"></i>
                        @endif
                    </h1>

                    @php
                        $article_published_at = date('F j, Y', /*, g:i a',*/ strtotime( $article['published_at'] ) )   
                    @endphp
                    {{ $article_published_at }}

                </div>

                <p class="flex-row mt-3 mb-1"> 

                    @foreach ($tags as $tag)
                        @include('partials.tag', ['tag' => $tag ])
                    @endforeach

                    <i class="fa fa-thumbs-up ps-5"> {{ $article['likes'] }}</i>
                    <i class="fa fa-thumbs-down ps-3"> {{ $article['dislikes'] }}</i>
                    
                    <i class="iconify ms-5" data-icon="bi:share-fill"></i>
                </p>
                
                <div class="flex-row h-50 mb-5">
                    {{-- {{ $article['thumbnail'] }} --}}
                    <img class="h-100 w-50" src="https://i.pinimg.com/originals/e4/34/2a/e4342a4e0e968344b75cf50cf1936c09.jpg">
                </div>
                                
                <div class="flex-row h-75">
                    {{ $article['body'] }}
                </div>

            </div>  

            <div class="flex-col w-25 p-3 text-dark" id="author-container">
                <div class="flex-row mt-1">
                    <h2>Author</h2>
                </div>
                
                <div class="d-flex flex-row mb-3">
                    <div class="flex-col w-25" style="margin-right: 1em;">
                        {{-- {{ $author['thumbnail'] }} --}}
                        <img id="authorAvatar" class="h-100" src="https://i.pinimg.com/originals/e4/34/2a/e4342a4e0e968344b75cf50cf1936c09.jpg">
                    </div>
                    <div class="flex-col w-75" id="author-header" style="padding-bottom: 0;">
                        <h4 class="mb-2"> {{ $author['name'] }} </h4>
                        <p> {{ $author['city'] }}, {{ $author['country']->name }} </p>
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
            </div>

        </div>

        <div class="d-flex flex-column" id="comments-section">
            <div class="flex-row mt-3 p-0">
                <h3 class="m-0">Comments</h3>
            </div>

            <div class="h-50">
                <div class="d-flex flex-row mx-0 my-3 p-0 w-75"> 
                    <div class="flex-column h-100 commentHeader mx-5 my-0 p-0">
                        {{-- buscar o User autenticado e meter a foto --}}
                        <img src="https://i.pinimg.com/originals/e4/34/2a/e4342a4e0e968344b75cf50cf1936c09.jpg">
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
                
                @foreach ($comments as $comment)
                    @include('partials.comment', ['comment' => $comment])
                @endforeach
            
            </div>

        </div>
    </div>

@endsection
