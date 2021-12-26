@extends('layouts.app')

@php
function calculateExpertiseLevel($reputation)
{
    $step = 13;
    if ($reputation == 0) {
        return 0;
    } elseif ($reputation < 5) {
        return $step * 1;
    } elseif ($reputation < 10) {
        return $step * 2;
    } elseif ($reputation < 20) {
        return $step * 3;
    } elseif ($reputation < 40) {
        return $step * 4;
    } else {
        return $step * 5;
    }
}
$birthDate = date('F j, Y', strtotime($user['birthDate']));
$age = date_diff(date_create($user['birthDate']), date_create(date('d-m-Y')))->format('%y');
@endphp

@section('content')

    <div id="userProfileContainer" class="d-flex flex-column">
        <div class="container-fluid py-3" id="userInfo">
            <div class="row w-100 mt-5" id="userGraphics">
                <div class="col-6 d-flex justify-content-center h-100">
                    <img src="https://cdn.iconscout.com/icon/free/png-256/avatar-370-456322.png" id="avatarImg" />
                </div>
                <div class="col-6 d-flex flex-column align-items-center h-100">
                    <div class="h-100 text-dark" id="graphContainer">
                        <h4 class="text-center pt-3 pb-0 my-0">Areas of Expertise</h4>
                        <div class="d-flex flex-column h-100 justify-content-evenly pb-5">
                            <div class="d-flex align-items-center ms-3">
                                <p class="my-0 py-0 pe-3 tagName">{{ $topAreasExpertise[0]['tag_name'] }}</p>
                                <div class="tagBar"
                                    style="width: {{ calculateExpertiseLevel($topAreasExpertise[0]['reputation']) . '%' }}">
                                </div>
                            </div>
                            <div class="d-flex align-items-center ms-3">
                                <p class="my-0 py-0 pe-3 tagName">{{ $topAreasExpertise[1]['tag_name'] }}</p>
                                <div class="tagBar"
                                    style="width: {{ calculateExpertiseLevel($topAreasExpertise[1]['reputation']) . '%' }}">
                                </div>
                            </div>
                            <div class="d-flex align-items-center ms-3">
                                <p class="my-0 py-0 pe-3 tagName">{{ $topAreasExpertise[2]['tag_name'] }}</p>
                                <div class="tagBar"
                                    style="width: {{ calculateExpertiseLevel($topAreasExpertise[2]['reputation']) . '%' }}">
                                </div>
                            </div>
                        </div>

                    </div>
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
                    <div class="d-flex flex-column justify-content-center">
                        <div style="background-color: green; width: 15em; height: 1.5em; border-radius: 0.7em"> </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="my-0 py-0 pt-2 ">Reputation: 1500</h6>
                            <i class="fa fa-exclamation-circle fa-1x" id="reportIcon" onclick="console.log('cliked')"></i>
                        </div>

                    </div>
                </div>
            </div>

            <div class="mt-5" id="description">
                <h5>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla at consequat odio. Nullam quis urna
                    commodo, scelerisque massa vulputate, facilisis enim. Mauris facilisis rutrum orci, sed congue enim
                    tempus sed. Ut dignissim odio in leo mattis, iaculis venenatis nisi ornare. In pellentesque, quam
                    vitae
                    commodo vestibulum, ex magna vulputate massa, quis tincidunt velit metus et magna. Sed a augue urna.
                    Mauris ac elementum orci. Cras a posuere libero.</h5>
            </div>
        </div>
        <div class="container-fluid w-100 d-flex justify-content-center my-5" id="userArticles">
            <h2 class="border-bottom border-2 border-dark text-center pb-1" id="articlesTitle">Articles</h2>
        </div>
    </div>

@endsection
