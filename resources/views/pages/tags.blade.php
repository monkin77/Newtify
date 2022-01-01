@extends('layouts.app')

<script type="text/javascript" src="{{ asset('js/tags.js') }}"></script>

@section('content')

<div class="container">
    <h2 class="text-center mt-5">Manage Tags</h2>
    <div class="d-flex mb-5 flex-row row-cols-3">
        <div class="border text-center bg-light mx-3 statusContainer col">
            <h4 class="mt-5">Accepted Tags</h4>
            @foreach ($tags_accepted as $tag)
                <div class="mt-5 pb-3 pt-5 bg-light mb-5 tagContainer">
                    <p>{{ $tag['name'] }}</p>
                    <div id="stateButton">
                        <button type="button" onclick="removeTag(this, {{ $tag['id'] }})" class="btn btn-lg btn-danger">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="border bg-light statusContainer col">
            <h4 class="text-center mt-5">Pending Tags</h4>
            @foreach ($tags_pending as $tag)
                <div class="mt-5 pb-3 pt-5 text-center bg-light mb-5 tagContainer">
                    <p>{{ $tag['name'] }}</p>
                    <div id="stateButton">
                        <button type="button" onclick="acceptTag(this, {{ $tag['id'] }})" class="btn btn-lg btn-success">Accept</button>
                        <button type="button" onclick="rejectTag(this, {{ $tag['id'] }})" class="btn btn-lg btn-danger">Reject</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="border bg-light mx-3 statusContainer col">
            <h4 class="text-center mt-5">Rejected Tags</h4>
            @foreach ($tags_rejected as $tag)
                <div class="mt-5 pb-3 pt-5 text-center bg-light mb-5 tagContainer">
                    <p>{{ $tag['name'] }}</p>
                    <div id="stateButton">
                        <button type="button" onclick="acceptTag(this, {{ $tag['id'] }})" class="btn btn-lg btn-success">Accept</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection