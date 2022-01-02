@extends('layouts.app')

@section('content')


<div class="my-5 container">
    <div class="d-flex flex-row row-cols-3">
        <div class="d-flex flex-column border shadow col bg-light me-3 h-100">
            <div class="linkContainer w-100">
                <div class="iconContainer shadow-lg rounded-circler">
                    <i class="fas fa-users-slash suspensionsIcon"></i>
                </div>
            </div>
            <a class="manageLink text-secondary" href="{{ url('/admin/suspensions') }}">Manage User Suspensions</a>
        </div>
        
        <div class="d-flex flex-column border shadow col bg-light me-3 h-100">
            <div class="linkContainer h-50 w-100">
                <i class="fas fa-ban reportsIcon shadow-lg rounded-circle"></i>
            </div>
            <a class="manageLink text-secondary" href="{{ url('/admin/reports') }}">Manage Reports</a>
        </div>
        
        <div class="d-flex flex-column border shadow col bg-light me-3 h-100">
            <div class="linkContainer h-50 w-100">
                <i class="tagsIcon shadow-lg rounded-circle fas fa-tags"></i>
            </div>
            <a class="manageLink text-secondary" href="{{ url('/admin/tags') }}">Manage Tags</a>
        </div>
    </div>
</div>


@endsection
