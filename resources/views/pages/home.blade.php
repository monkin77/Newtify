@extends('layouts.app')

@section('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script type="text/javascript" src={{ asset('js/filter.js') }}></script>
    <script type="text/javascript" src=" {{ asset('js/select2tags.js') }}"></script>
    <script type="text/javascript" src={{ asset('js/tags.js') }}></script>
@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('filters')
    <section id="filterSection" class="d-flex flex-row align-items-center border border-light rounded-pill py-4 px-2 mt-3 mb-4">
        <div class="btn-group btn-group-toggle me-auto" data-toggle="buttons">
            @if (Auth::check())
                <input type="radio" class="btn-check" name="filterType" id="recommended" autocomplete="off" checked>
                <label data-bs-toggle="tooltip" data-bs-placement="bottom" title="From your favorite authors and tags"
                    class="filter-button btn btn-outline-warning text-light btn-lg ms-4 my-auto" for="recommended"
                >
                <i class="far fa-star mt-2 text-warning"></i>
                <span class="mx-2">Recommended</span>
            </label>
            @endif

            <input type="radio" class="btn-check" name="filterType" id="trending" autocomplete="off"
            @if (Auth::guest()) checked @endif>
            <label data-bs-toggle="tooltip" data-bs-placement="bottom" title="Hottest articles of the day"
                class="filter-button btn btn-outline-danger text-light ms-4 my-auto" for="trending"
            >
                <i class="fas fa-fire-alt mt-2 text-danger"></i> <span class="mx-2">Trending</span>
            </label>

            <input type="radio" class="btn-check" name="filterType" id="recent" autocomplete="off">
            <label data-bs-toggle="tooltip" data-bs-placement="bottom" title="The latest articles"
                class="filter-button btn btn-outline-info text-light btn-lg ms-4 my-auto" for="recent"
            >
                <i class="fas fa-history mt-2 text-info"></i> <span class="mx-2">Recent</span>
            </label>
        </div>

        <div id="daterangeContainer" class="flex-fill d-flex justify-content-evenly">
            <input type="text" name="daterange" class="my-0 mx-5 text-center text-light border-light"
            placeholder="Filter by Publish Date" />
        </div>

        <select id="filterTags" onchange="filterArticles()" multiple>
            @foreach($tags as $tag)
                <option value="{{ $tag['id'] }}">
                    {{ $tag['name'] }}
                </option>
            @endforeach
        </select>

        <i class="fa fa-tag filter-tag mx-4 text-lightPurple"></i>
    </section>
@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('articles')
    <section id="articles" class="container-fluid">

        @if ($articles->isEmpty())
            <div class="alert alert-custom mb-4 text-center" role="alert">
                <h3 class="my-3">No results found</h3>
            </div>  
        @endif

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
    <section class="home-section d-flex flex-column align-items-center mt-5 bg-secondary">
        <div class="d-flex flex-grow-1 justify-content-center home-container">
            <div id="proposeTag" class="position-relative d-flex flex-column align-items-center">
                <h1 class="mb-2">
                    <i class="fa fa-tag fa-sm fa-flip-horizontal px-2 text-lightPurple"></i>
                    Propose a new Tag
                    <i class="fa fa-tag fa-sm px-2 text-lightPurple"></i>
                </h1>
                <h4 class="mb-5 text-light"> Help us improve on getting more variety of content </h4>

                <form id="proposeTagForm" class="d-flex flex-row mb-0" onsubmit="proposeTag(event)">
                    <input id="tag-name" type="text" name="tagName" placeholder="Enter your tag" required>

                    <button class="mx-4" type="submit"> Propose </button>
                </form>
            </div>
        </div>
    </section>

@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('content')

    @yield('filters')
    @yield('articles')

    @if ($canLoadMore)
        @yield('load-more')
    @endif

    @yield('propose-tag')

@endsection
