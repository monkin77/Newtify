@extends('layouts.app')

@section('content')

<h3>Manage Tags</h3>

<div class="container">
    @foreach ($tags as $tag)
        <div class="col h-50">
            <p>{{ $tag['name'] }}</p>
            <button type="button" class="btn btn-success w-25">Accept</button>
            <button type="button" class="btn btn-danger w-25">Reject</button>
        </div>
    @endforeach
    </div>
</div>

@endsection