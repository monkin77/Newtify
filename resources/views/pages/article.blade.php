@extends('layouts.app')

@section('content')
    
    {{-- page of an article --}}
    <h1> {{ $article['title'] }} </h1>

    <p> {{ $article['body'] }} </p>

    @foreach ($comments as $comment)
        <p> Comentario: {{ $comment['body'] }} </p>
    @endforeach

    @foreach ($tags as $tag)
        <p> Tag: {{ $tag['name'] }} </p>
    @endforeach

    @foreach ($author['topAreasExpertise'] as $areaExpertise) 
        <p> Area Expertise: {{ $areaExpertise['tag_name'] }} </p>
    @endforeach

    {{-- is_author says if the uesr is the author of the article, so we show the edit button --}}
    <p> {{ $author['name'] }} <b> {{ $is_author }} </b> </p>

@endsection
