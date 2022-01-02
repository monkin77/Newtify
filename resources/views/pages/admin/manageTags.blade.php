@extends('layouts.app')

<script type="text/javascript" src="{{ asset('js/tags.js') }}"></script>

@section('content')

<div class="text-center container">
    <h2 class="text-center mt-5">Manage Tags</h2>
    <div class="d-flex mb-5 flex-row row-cols-3">
        <div class="border bg-light me-3 statusContainer col">
            <h4 class="mt-5">Accepted Tags</h4>
            @foreach ($tags_accepted as $tag)
                <div class="mt-5 pb-3 pt-5 bg-light mb-5 manageTagContainer">
                    <div id="stateButton" class="d-flex align-items-center">
                        <h5 class="mx-3 my-0 py-0 w-75">{{ $tag['name'] }}</h5>
                        <button type="button" onclick="removeTag(this, {{ $tag['id'] }})" class="my-0 py-0 btn btn-lg btn-transparent">
                            <i class="fas fa-trash fa-2x mb-2 text-danger"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="border bg-light statusContainer col">
            <h4 class="mt-5">Pending Tags</h4>
            @foreach ($tags_pending as $tag)
                <div class="mt-5 pb-3 pt-5 bg-light mb-5 manageTagContainer">
                    <div id="stateButton" class="d-flex align-items-center">
                        <h5 class="mx-3 my-0 py-0 w-75">{{ $tag['name'] }}</h5>
                        <button type="button" onclick="acceptTag(this, {{ $tag['id'] }})" class="my-0 py-0 btn btn-lg btn-transparent">
                            <i class="fas fa-check fa-2x mb-2 text-success"></i>
                        </button>
                        <button type="button" onclick="rejectTag(this, {{ $tag['id'] }})" class="my-0 mx-1 py-0 btn btn-lg btn-tranparent">
                            <i class="fas fa-times fa-2x mb-2 text-danger"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="border bg-light mx-3 statusContainer col">
            <h4 class="mt-5">Rejected Tags</h4>
            @foreach ($tags_rejected as $tag)
                <div class="mt-5 pb-3 pt-5 bg-light mb-5 manageTagContainer">
                    <div id="stateButton" class="d-flex align-items-center">
                        <h5 class="mx-3 my-0 py-0 w-75">{{ $tag['name'] }}</h5>
                        <button type="button" onclick="acceptTag(this, {{ $tag['id'] }})" class="my-0 py-0 btn btn-lg btn-transparent">
                            <i class="fas fa-check fa-2x mb-2 text-success"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection