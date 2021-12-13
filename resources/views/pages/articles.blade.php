@extends('layouts.app')

@section('title', 'Articles')

@section('articles')

<section id="articles">
  @each('partials.article', $articles, 'article')
</section>

@endsection

<h1>@yield('title')</h1>

@yield('articles')