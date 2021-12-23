@extends('layouts.app')

@section('content')
    
    <div class="article-container container bg-light">
        <div class="d-flex flex-row my-2 h-100">
            
            <div class="d-flex flex-column w-75 p-3 text-dark" >
                <div class="flex-row"> 
                    <h1> 
                        {{ $article['title'] }} 
                        @if ($is_author)
                            <i class="iconify" style="width: 0.8em; height: 0.7em;" data-icon="bi:pencil-fill"></i>
                        @endif
                    </h1>
                </div>

                <p class="flex-row"> 
                    @php
                        $published_at = date('F j, Y', /*, g:i a',*/ strtotime( $article['published_at'] ) )   
                    @endphp
                    {{ $published_at }}
                </p>

                <p class="flex-row"> 
                    @foreach ($tags as $tag)
                        {{ $tag['name'] }}
                    @endforeach
                    <i class="fa fa-thumbs-up ps-5"> {{ $article['likes'] }}</i>
                    <i class="fa fa-thumbs-down ps-3"> {{ $article['dislikes'] }}</i>
                    
                    <i class="iconify ms-5" data-icon="bi:share-fill"></i>
                </p>
                
                <div class="flex-row h-25" style="border: 1px solid red;">
                    <img class="h-100" src="https://i.pinimg.com/originals/e4/34/2a/e4342a4e0e968344b75cf50cf1936c09.jpg">
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
                    {{-- {{ $article['thumbnail'] }} --}}
                    <div class="flex-col w-25" style="margin-right: 1em;">
                        <img id="avatar" class="h-100" src="https://i.pinimg.com/originals/e4/34/2a/e4342a4e0e968344b75cf50cf1936c09.jpg">
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
                    <p>Description: </p>
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

        <div class="flex-row">
            @foreach ($comments as $comment)
                <p> Comentario: {{ $comment['body'] }} </p>
            @endforeach

        </div>
    </div>

@endsection
