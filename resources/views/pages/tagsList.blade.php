@extends('layouts.app')

@section('scripts')
    <script src=" {{ asset('js/favoriteTags.js') }}"> </script>
@endsection

@section('content')
    <section id="tagsContainer" class="container-fluid">
        <h1>Tags List</h1>
        <div class="row">
            @foreach ($tags as $tag)
                <div class="col-3">
                    <div class="d-flex justify-content-center align-items-center text-white tagContainer <?php if ($userTags->contains('id', $tag['id'])) {
    echo 'selectedTag';
} ?>"
                        id={{ $tag['id'] }}>
                        <h3>{{ $tag['name'] }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
