@extends('layouts.app')

@php
$tagline = 'Que rego amiguinho';
@endphp

@section('content')

    <div id="userProfileContainer" class="d-flex justify-content-center py-3">
        <div id="userInfo">
            <h1> {{ $user['name'] }} </h1>
        </div>
    </div>

@endsection
