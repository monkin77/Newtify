@extends('layouts.app')

@php
$tagline = 'Que rego amiguinho'
@endphp

@section('content')

<div id="homepage" class="d-flex justify-content-center py-3">
    <div class="d-flex flex-grow-1 justify-content-center createArticleContainer">
        <div id="createArticle" class="position-relative d-flex flex-column align-items-center" >
            <h1> Create Your Own Articles </h1>
            <h3> {{$tagline}} </h3>
            <div class="addIcon"> 
                <i class="fas fa-plus-circle fa-4x"></i>
            </div>   
        </div>
    </div>
</div>
 
@endsection