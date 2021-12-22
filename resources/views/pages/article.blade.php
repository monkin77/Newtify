@extends('layouts.app')

@section('content')
    
    <div class="container bg-light">
        <div class="row" style="margin-top: 15px; margin-bottom: 15px;">
            
            <div class="col-8">
                {{-- is_author says if the user is the author of the article, so we show the edit button --}}
                {{ $author['name'] }} <b> {{ $is_author }} </b> 

                <h1 class="text-dark"> {{ $article['title'] }} </h1>

                <p> {{ $article['body'] }} </p>
            </div>  

            <div class="col-4 text-dark" id="author-container">
                <div class="row mt-1">
                    <h2>Author</h2>
                </div>
                
                <div class="row mb-2">
                    {{-- {{ $article['thumbnail'] }} --}}
                    <div class="col-3">
                        <img id="avatar" src="https://i.pinimg.com/originals/e4/34/2a/e4342a4e0e968344b75cf50cf1936c09.jpg">
                    </div>

                    <div class="col-9" id="author-header">
                        <h4 class="mb-2"> {{ $author['name'] }} </h4>
                        <div class="mb-0"> {{ $author['city'] }}, {{ $author['country']->name }} </div>
                    </div>
                </div>

                <div class="row mb-1">
                    <p class="text-secondary">Reputation: {{ $author['reputation'] }} </p>
                </div>

                <div class="row my-3">
                    <p>Description: </p>
                    <p>{{ $author['description'] }}</p>
                </div>

                <div class="row my-5">
                    <h3>Areas of Expertise </h3>
                    @foreach ($author['topAreasExpertise'] as $areaExpertise) 
                        <p> Area Expertise: {{ $areaExpertise['tag_name'] }} </p>
                    @endforeach
                </div>
            </div>

            @foreach ($comments as $comment)
                <p> Comentario: {{ $comment['body'] }} </p>
            @endforeach

            @foreach ($tags as $tag)
                <p> Tag: {{ $tag['name'] }} </p>
            @endforeach

        </div>
    </div>

@endsection
