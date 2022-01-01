@extends('layouts.app')

@section('content')


<div class="container">
    <div class="d-flex shadow mb-5 flex-row row-cols-3">
        <h3 class="modal-titlemx-auto text-left fw-bold" id="exampleModalLabel">Admin Page</h3>
        <div class="container">
            <a class="row" href="{{ url('/admin/suspensions') }}">Manage Suspensions</a>
            <a class="row" href="{{ url('/admin/reports') }}">Manage Reports</a>
            <a class="row" href="{{ url('/admin/tags') }}">Manage Tags</a>
        </div>   
    </div>
</div>


@endsection
