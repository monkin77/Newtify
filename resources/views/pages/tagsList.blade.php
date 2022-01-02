@extends('layouts.app')

@section('content')
    <section id="tagsContainer" class="container-fluid">
        <h1>Tags List</h1>
        <div class="row">
            @foreach ($tags as $tag)
                <div class="col-3">
                    <div class="d-flex justify-content-center align-items-center bg-dark tagContainer">
                        <h3>{{ $tag['name'] }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
