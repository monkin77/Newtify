@extends('layouts.app')

@section('content')
    <section id="users" class="container">
        @include('partials.user.list', ['users' => $users])
    </section>
@endsection
