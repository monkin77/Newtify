@extends('layouts.app')

@section('scripts')
    <script src=" {{ asset('js/favoriteTags.js') }}" defer> </script>
@endsection

@section('content')
    <section id="tagsContainer" class="container-fluid d-flex flex-column align-items-center" style="background-color: gray">
        <h1 class="my-5">Choose your Favorite Tags!</h1>

        <div class="d-flex flex-row w-50 align-items-center rounded border my-5" id="tagSearch">
            <i class="fas fa-search ms-4"></i>
            <input class="no-border my-0 ms-3" type="search" placeholder="Search" name="query" autocomplete="off"
                value="{{ old('query') }}" />
        </div>

        <div class="row me-0">
            @foreach ($tags as $tag)
                <div class="col-2">
                    <div class="d-flex justify-content-center align-items-center text-white tagContainer <?php if ($userTags->contains('id', $tag['id'])) {
    echo 'selectedTag';
} ?>"
                        id={{ $tag['id'] }} onclick="toggleSelected(this)">
                        <h3 class="my-0 mx-0">{{ $tag['name'] }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="container-fluid text-center my-4">
            <button type="button" class="btn btn-primary btn-lg" id="favoriteTagsButton"
                onclick="saveFavoriteTags({{ $userTags }}, {{ $userId }})">Submit</button>
        </div>
    </section>
@endsection
