@extends('layouts.app')

<script type="text/javascript" src={{ asset('js/user.js') }}></script>

@section('content')
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
                    @include('partials.user.reputationBar', ['user' => $user, 'isOwner' => true])
                </div>
            </div>

            <div class="mt-5" id="description">
                <h5>{{ $user['description'] }}</h5>
            </div>
        </div>
    </section>
@endsection
