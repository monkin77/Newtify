@extends('layouts.app')

<script type="text/javascript" src={{ asset('js/user.js') }}></script>

{{-- TO-DO:
    - Use sendAjaxRequest method from App.js
    - Disable Follow, Message, Report Buttons For guests
    - Add Report Request
    - Improve Areas of Expertise Graph
    - Improve Tags Badge
    - Include Country Flag? --}}

{{-- ------------------------------------------------------------------------------------ --}}
@section('userInfo')
    <section id="userInfo">
        <div class="container-fluid py-3">
            <div class="row w-100 mt-5" id="userGraphics">
                <div class="col-6 d-flex justify-content-center h-100">
                    <img src={{ isset($user['avatar']) ? $user['avatar'] : $userImgPHolder }} id="avatarImg"
                        onerror="this.src='{{ $userImgPHolder }}'" />
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
                        <button type="button" class="btn btn-secondary px-5 my-0 py-0 me-3" id="followBtn"
                            onclick="unfollowUser({{ $user['id'] }})">Unfollow</button>
                    @else
                        <button type="button" class="btn btn-primary px-5 my-0 py-0 me-3" id="followBtn"
                            onclick="followUser({{ $user['id'] }})">Follow</button>
                    @endif
                    <i class="fa fa-users fa-1x me-3 text-dark"></i>
                    <p class="h5 py-0 my-0" id="followersCount">{{ $followerCount }}</p>
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
                        <h5 class="mb-0">
                            {{ (is_null($user['city']) ? '' : $user['city'] . ', ') . $user['country']['name'] }}</h5>
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
        @include('partials.content.articles', ['articles' => $articles])
    </section>
@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('content')
    <div id="userProfileContainer" class="d-flex flex-column">
        @yield('userInfo')
        @yield('articles')
    </div>
@endsection
