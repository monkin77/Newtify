@extends('layouts.app')

@section('title', 'Articles')

@section('content')

<section id="articles">
  @foreach($articles as $article)
    @include('partials.article', ['article' => $article])
  @endforeach
</section>

@endsection
