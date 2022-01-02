@extends('layouts.app')

@section('content')


<div class="my-5 container">
    <div class="d-flex flex-row row-cols-3">
        <a href="{{ url('/admin/suspensions') }}" class="manageContainer h-100 me-5 text-secondary">
            <div class="d-flex flex-column manageContainer border col bg-light h-100">
                <div class="linkContainer w-100">
                    <div class="iconContainer shadow-lg rounded-circle">
                        <i class="fas fa-users-slash suspensionsIcon"></i>
                    </div>
                </div>
                <div class="manageLink">
                    Manage Users Suspension
                </div>
            </div>
        </a>
        
        <a class="manageContainer h-100 me-5 text-secondary" href="{{ url('/admin/reports') }}">
            <div class="d-flex flex-column manageContainer border col bg-light h-100">
                <div class="linkContainer w-100">
                    <div class="iconContainer shadow-lg rounded-circle">
                        <i class="fas fa-ban reportsIcon"></i>
                    </div>
                </div>
                <div class="manageLink">
                    Manage Reports
                </div>
            </div>
        </a>
        
        <a class="manageContainer h-100 me-5 text-secondary" href="{{ url('/admin/tags') }}">
            <div class="d-flex flex-column manageContainer border col bg-light h-100">
                <div class="linkContainer">
                    <div class="iconContainer shadow-lg rounded-circle">
                        <i class="tagsIcon fas fa-tags"></i>
                    </div>
                </div>
                <div class="manageLink">
                    Manage Tags
                </div>
            </div>
        </a>
    </div>
</div>


@endsection
