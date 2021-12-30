@extends('layouts.app')

@section('content')

    <h3 class="modal-titlemx-auto text-left fw-bold" id="exampleModalLabel">Admin Page</h3>
    <div class="container">
            <a class="row" href="{{ url('/admin/suspensions') }}">Manage Suspensions</a>
            <a class="row" href="{{ url('/admin/reports') }}">Manage Reports</a>
            <a class="row" href="{{ url('/admin/tags') }}">Manage Tags</a>
    </div>


@endsection
