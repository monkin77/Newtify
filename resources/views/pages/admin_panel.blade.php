@extends('layouts.app')

@section('content')


<div class="my-5 container">
    <div class="d-flex flex-row row-cols-3">
        <a href="{{ url('/admin/suspensions') }}" class="text-secondary">
            <div class="d-flex flex-column manageContainer border col bg-light me-3 h-100">
                <div class="linkContainer w-100">
                    <div class="iconContainer shadow-lg rounded-circle">
                        <i class="fas fa-users-slash suspensionsIcon"></i>
                    </div>
                </div>
                <div class="manageLink">
                    Manage User Suspensions
                </div>
            </div>
        </a>
        
        <div class="d-flex flex-column manageContainer border col bg-light me-3 h-100">
            <div class="linkContainer">
                <div class="iconContainer shadow-lg rounded-circle">
                    <i class="fas fa-ban reportsIcon"></i>
                </div>
            </div>
            <div class="manageLink">
                <a class="text-secondary" href="{{ url('/admin/reports') }}">Manage Reports</a>
            </div>
        </div>
        
        <div class="d-flex flex-column manageContainer border col bg-light me-3 h-100">
            <div class="linkContainer">
                <div class="iconContainer shadow-lg rounded-circle">
                    <i class="tagsIcon fas fa-tags"></i>
                </div>
            </div>
            <div class="manageLink">
                <a class="text-secondary" href="{{ url('/admin/tags') }}">Manage Tags</a>
            </div>
        </div>
    </div>
</div>


@endsection
