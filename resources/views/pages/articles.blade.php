@extends('layouts.app')

@section('title', 'Articles')

@section('content')

<section id="articles">
  @each('partials.article', $articles, 'article')
</section>

@endsection
