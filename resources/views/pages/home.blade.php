@extends('layouts.app')

<script type="text/javascript" src={{ asset('js/tags.js') }}></script>

{{-- ------------------------------------------------------------------------------------ --}}
@section('create-article')

<div class="home-section d-flex justify-content-center pb-3">
    <div class="d-flex flex-grow-1 justify-content-center home-container">
        <div id="createArticle" class="position-relative d-flex flex-column align-items-center" >
            <h1> Create Your Own Article </h1>
            <h3> Others will decide your faith </h3>
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

{{-- ------------------------------------------------------------------------------------ --}}

@section('propose-tag')

<div class="home-section d-flex justify-content-center pt-3">
    <div class="d-flex flex-grow-1 justify-content-center home-container">
        <div id="proposeTag" class="position-relative d-flex flex-column align-items-center" >
            <h1 class="mb-2">
                <i class="fa fa-tag fa-sm fa-flip-horizontal px-2"></i>
                    Propose a new Tag
                <i class="fa fa-tag fa-sm px-2"></i>
            </h1>
            <h4 class="mb-5"> Help us improve on getting more variety of content </h4>

            <form id="proposeTagForm" class="d-flex flex-row mb-0" onsubmit="proposeTag(event)">
                <input id="tag-name" type="text" name="tagName" placeholder="Enter your tag" required>

                <button class="mx-4" type="submit"> Propose </button>
            </form>
        </div>
    </div>
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

    @yield('propose-tag')

@endsection
