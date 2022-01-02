@extends('layouts.app')

@php
$guest = !Auth::check();
@endphp

<script type="text/javascript" src={{ asset('js/user.js') }} defer></script>

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
                    <div class="col-6 w-50 h-100">
                        @include('partials.user.areasOfExpertiseGraph', ['topAreasExpertise' => $topAreasExpertise ])
                    </div>
                </div>
            </div>
            <div class="row w-100 mt-5 mb-4">
                <div class="col-6 d-flex justify-content-center align-items-center">
                    <h2 class="text-center  my-0 py-0">{{ $user['name'] }}</h2>
                </div>
                <div class="col-6 d-flex justify-content-center align-items-center">
                    @if ($isOwner)
                        <button type="button" class="btn transparentButton my-0 py-0 me-5 rounded-circle">
                            <a class="fa fa-pencil fa-3x" style="color:orange" href="/user/{{ $user['id'] }}/edit"></a>
                        </button>
                    @else
                        @if (!$guest)
                            <i class="fa fa-comment-dots me-3 fa-2x text-dark" onclick="console.log('clicked')"></i>
                            @if ($follows)
                                <button type="button" class="btn btn-secondary px-5 my-0 py-0 me-3" id="followBtn"
                                    onclick="unfollowUser({{ $user['id'] }})">Unfollow</button>
                            @else
                                <button type="button" class="btn btn-primary px-5 my-0 py-0 me-3" id="followBtn"
                                    onclick="followUser({{ $user['id'] }})">Follow</button>
                            @endif
                        @endif
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
                        &#{{ $user['country']['flag'][0] }}&#{{ $user['country']['flag'][1] }}
                        <h5 class="mb-0 ms-3">
                            {{ (is_null($user['city']) ? '' : $user['city'] . ', ') . $user['country']['name'] }}</h5>
                    </div>
                </div>
                <div class="col-6 d-flex justify-content-center align-items-center">
                    @include('partials.user.reputationBar', ['user' => $user, 'isOwner' => $isOwner])
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
    <div class="container-fluid w-100 d-flex justify-content-center my-5" id="userArticles">
        <h2 class="border-bottom border-2 border-dark text-center pb-1" id="articlesTitle">Articles</h2>
    </div>
    <section id="articles">
        @include('partials.content.articles', ['articles' => $articles])
    </section>
@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('load-more')
<div id="load-more">

    <button onclick="loadMoreUser({{ Auth::id() }})">Load more</button>

</div>
@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('report')
    <section id="reportElement" class="d-block d-none">
        <div id="backdrop" onclick="toggleReportPopup()"></div>
        <div id="reportContainer" class="d-flex flex-column align-items-center justify-content-center">
            <div id="reportInsideContainer" class="d-flex flex-column align-items-center justify-content-evenly">
                <h3 class="text-black">Give us a reason to report this user</h3>
                <div class="text-danger d-flex d-none py-0 my-0 align-items-center text-center px-5" id="reportError">
                    <i class="fa fa-exclamation me-3 fa-1x"></i>
                    <h5 class="py-0 my-0" id="reportErrorText"></h5>
                </div>
                <textarea id="reason" rows="10" placeholder="Insert report reason here"></textarea>
                <button onclick="reportUser({{ $user['id'] }})">SUBMIT</button>
                <button class="btn p-0 m-0 transparentButton" id="closePopupBtn" onclick="toggleReportPopup()">
                    <i class="fa fa-times fa-3x" id="closeIcon"></i>
                </button>
            </div>
        </div>
    </section>
@endsection

{{-- ------------------------------------------------------------------------------------ --}}

@section('content')
    <div id="userProfileContainer" class="d-flex flex-column">
        @yield('userInfo')
        @yield('articles')
        @yield('report')

        @if ($canLoadMore)
            @yield('load-more')
        @endif
    </div>
@endsection
