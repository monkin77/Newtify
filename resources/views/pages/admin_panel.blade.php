@extends('layouts.app')

@section('content')


<div class="my-5 container">
    <div class="d-flex flex-row row-cols-3">
        <div class="d-flex align-items-center flex-column border shadow bg-light me-3 linkContainer col">
            <i class="fas fa-users cir iconSize"></i>
            <a class="text-secondary" href="{{ url('/admin/reports') }}">Manage Suspensions</a>
        </div>
        <div class="d-flex text-center flex-column border shadow bg-light linkContainer col">
            <i class="fas fa-users iconSize"></i>
            <a class="text-secondary" href="{{ url('/admin/reports') }}">Manage Reports</a>
        </div> 
        <div class="d-flex text-center flex-column border shadow bg-light mx-3 linkContainer col">
            <i class="fas fa-users iconSize"></i>
            <a class="text-secondary" href="{{ url('/admin/reports') }}">Manage Tags</a>
        </div>
    </div>
</div>


@endsection
