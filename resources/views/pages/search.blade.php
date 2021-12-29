@extends('layouts.app')

{{-- ------------------------------------------------------------------------------------ --}}

@section('error-handler')
    <div class="alert alert-danger mb-0 text-center" role="alert">
        @foreach ($errors->all() as $error)
            <h4 class="my-3">{{ $error }}</h4 class="my-3">
        @endforeach
    </div>
@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@if ($errors->any())
    @section('content')
        @yield('error-handler')
    @endsection
@else

{{-- ------------------------------------------------------------------------------------ --}}

@section('searchInfo')

@if ($results->isEmpty())

    <div class="alert alert-custom mb-0 text-center" role="alert">
        <h3 class="my-3">No results found</h3>
    </div>

@else

    <div class="alert alert-secondary search-nfo my-3 text-center" role="alert">
        <h3 class="mb-0">Displaying {{ $type }} results for: <i>{{$query}}</i> </h3>
    </div>

@endif

@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('results')

@if ($type === 'article')

    @include('partials.content.articles', ['articles' => $results])

@else {{-- Users --}}

    @include('partials.user.list', ['users' => $results])

@endif

@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('load-more')
<div id="load-more">

    <button>Load more</button>

</div>
@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('content')
    @yield('searchInfo')
    @yield('results')

    @if ($canLoadMore)
        @yield('load-more')
    @endif

@endsection
@endif

{{--
- Load more com AJAX
- Ativar o butao de search
--}}
