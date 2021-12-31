@extends('layouts.app')

<script type="text/javascript" src={{ asset('js/tags.js') }}></script>

{{-- ------------------------------------------------------------------------------------ --}}
@section('create-article')

<section class="home-section d-flex justify-content-center mb-3">
    <div class="d-flex flex-grow-1 justify-content-center home-container">
        <div id="createArticle" class="position-relative d-flex flex-column align-items-center" >
            <h1> Create Your Own Article </h1>
            <h3> Others will decide your faith </h3>
            <a class="addIcon btn" href="{{ route('createArticle') }}"> 
                <i class="fas fa-plus-circle fa-4x"></i>
            </a>   
        </div>
    </div>
</section>

@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('filters')
<section id="filterSection" class="d-flex flex-row border mb-3 py-2">
    <div class="btn-group btn-group-toggle me-auto" data-toggle="buttons">
        <input type="radio" class="btn-check" name="filterType" id="trending" autocomplete="off" checked>
        <label class="filter-button btn btn-outline-secondary ms-4 my-auto" for="trending">
            <i class="fas fa-fire-alt mt-2"></i> <span class="mx-2">Trending</span>
        </label>

        <input type="radio" class="btn-check" name="filterType" id="recent" autocomplete="off">
        <label class="filter-button btn btn-outline-secondary btn-lg ms-4 my-auto" for="recent">
            <i class="fas fa-history mt-2"></i> <span class="mx-2">Recent</span>
        </label>

        @if (Auth::check())
            <input type="radio" class="btn-check" name="filterType" id="recommended" autocomplete="off">
            <label class="filter-button btn btn-outline-secondary btn-lg ms-4 my-auto" for="recommended">
                <i class="far fa-star mt-2"></i> <span class="mx-2">Recommended</span>
            </label>
        @endif
    </div>

    <i class="fa fa-tag filter-tag mt-2 me-4"></i>
</section>
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

<section class="home-section d-flex justify-content-center mt-3">
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
</section>

@endsection

@section('articles')
    @include('partials.content.articles', ['articles' => $articles])
@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('content')

    @yield('create-article')
    @yield('filters')
    @yield('articles')

    @if ($canLoadMore)
        @yield('load-more')
    @endif

    @yield('propose-tag')

@endsection
