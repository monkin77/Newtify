@extends('layouts.app')

@php
$birthDate = date('F j, Y', strtotime($user['birthDate']));
$age = date_diff(date_create($user['birthDate']), date_create(date('d-m-Y')))->format('%y');
@endphp

{{-- ------------------------------------------------------------------------------------ --}}

@section('userInfo')
    <section id="userInfo">
        <div class="container-fluid py-3">
            <div class="row w-100 mt-5" id="userGraphics">
                <div class="col-6 d-flex justify-content-center h-100">
                    <img src={{ $user['avatar'] }} id="avatarImg" />
                </div>
                <div class="col-6 d-flex flex-column align-items-center h-100">
                    @include('partials.user.areasOfExpertiseGraph', ['topAreasExpertise' => $topAreasExpertise ])
                </div>
            </div>
            <div class="row w-100 mt-5 mb-4">
                <div class="col-6 d-flex justify-content-center align-items-center">
                    <h2 class="text-center  my-0 py-0">{{ $user['name'] }}</h2>
                </div>
                <div class="col-6 d-flex justify-content-center align-items-center">
                    <i class="fa fa-comment-dots me-3 fa-2x text-dark" onclick="console.log('clicked')"></i>
                    @if ($follows)
                        <button type="button" class="btn btn-secondary px-5 my-0 py-0 me-3"
                            onclick="console.log('clicked')">Unfollow</button>
                    @else
                        <button type="button" class="btn btn-primary px-5 my-0 py-0 me-3"
                            onclick="console.log('clicked')">Follow</button>
                    @endif
                    <i class="fa fa-users fa-1x me-3 text-dark"></i>
                    <p class="h5 py-0 my-0">{{ $followerCount }}</p>
                </div>
            </div>
            <div class="row w-100 my-2">
                <div class="col-6 d-flex flex-column align-items-center">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fa fa-birthday-cake me-3 fa-1x" onclick="console.log('cliked')"></i>
                        <h5 class="mb-0">{{ $birthDate . ' (' . $age . ')' }}</h5>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fa fa-flag me-3 fa-1x" onclick="console.log('cliked')"></i>
                        <h5 class="mb-0">{{ $user['city'] . ', ' . $user['country']['name'] }}</h5>
                    </div>
                </div>
                <div class="col-6 d-flex justify-content-center align-items-center">
                    @include('partials.user.reputationBar', ['user' => $user])
                </div>
            </div>

            <div class="mt-5" id="description">
                <h5>{{ $user['description'] }}</h5>
            </div>
        </div>
    </section>
@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('articles')
    <section id="articles">
        <div class="container-fluid w-100 d-flex justify-content-center my-5" id="userArticles">
            <h2 class="border-bottom border-2 border-dark text-center pb-1" id="articlesTitle">Articles</h2>
        </div>
        <div class="container">
            @foreach ($articles as $article)
                @include('partials.article', ['article' => $article])
            @endforeach
        </div>
    </section>
@endsection

@section('content')
    <div id="userProfileContainer" class="d-flex flex-column">
        @yield('userInfo')
        @yield('articles')
    </div>
@endsection
