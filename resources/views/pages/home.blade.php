@extends('layouts.app')

@php
$tagline = 'Others will decide your faith'
@endphp

{{-- ------------------------------------------------------------------------------------ --}}
@section('create-article')

<div id="homepage" class="d-flex justify-content-center pb-3">
    <div class="d-flex flex-grow-1 justify-content-center createArticleContainer">
        <div id="createArticle" class="position-relative d-flex flex-column align-items-center" >
            <h1> Create Your Own Article </h1>
            <h3> {{ $tagline }} </h3>
            <a class="addIcon btn" href="{{ route('newArticlePage') }}"> 
                <i class="fas fa-plus-circle fa-4x"></i>
            </a>   
        </div>
    </div>
</div>

@endsection


{{-- ------------------------------------------------------------------------------------ --}}

@section('articles')
    <section id="articles" class="container">
        @include('partials.content.articles', ['articles' => $articles])
    </section>
@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('load-more')
<div id="load-more">

    <button onclick="loadMoreHome()">Load more</button>

</div>
@endsection


@section('articles')
    @include('partials.content.articles', ['articles' => $articles])
@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('content')

    @yield('create-article')
    @yield('articles')

    @if ($canLoadMore)
        @yield('load-more')
    @endif    

@endsection
