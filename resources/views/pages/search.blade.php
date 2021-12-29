@extends('layouts.app')

{{-- ------------------------------------------------------------------------------------ --}}
@section('searchInfo')

@if ($results->isEmpty())

    <div class="alert alert-custom mb-0 text-center" role="alert">
        <h3 class="my-3">No results found</h3>
    </div>

@else

    <div class="alert alert-secondary searchInfo my-3 text-center" role="alert">
        <h3 class="mb-0">Displaying {{ $type }} results for: <i>{{$query}}</i> </h3>
    </div>

@endif

@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('results')

@if ($type === 'article')

    @include('partials.content.articles', ['articles' => $results])

@else {{-- Users --}}
    
@endif

@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('content')

    @yield('searchInfo')
    @yield('results')

@endsection
