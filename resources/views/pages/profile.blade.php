@extends('layouts.app')

@php
$tagline = 'Que rego amiguinho';
@endphp

@section('content')

    <div id="userProfileContainer" class="d-flex flex-column">
        <div class="container-fluid py-3" id="userInfo">
            <div class="row w-100 mt-5" id="userGraphics">
                <div class="col-6 d-flex justify-content-center h-100">
                    <img src="https://cdn.iconscout.com/icon/free/png-256/avatar-370-456322.png" id="avatarImg" />
                </div>
                <div class="col-6 d-flex justify-content-center h-100">
                    <div class="position-relative">
                        <h4 style="position: absolute; top: -1.5em;"> Areas of Expertise </h4>
                        <img src="https://depictdatastudio.com/wp-content/uploads/2017/01/Depict-Data-Studio_Bar-Charts_Vertical-or-Horizontal_Horizontal-1.jpg"
                            class="h-100" />
                    </div>
                </div>
            </div>
            <div class="row w-100 mt-5 mb-4">
                <div class="col-6 d-flex justify-content-center align-items-center">
                    <h2 class="text-center  my-0 py-0">{{ $user['name'] }}</h2>
                </div>
                <div class="col-6 d-flex justify-content-center align-items-center">
                    <i class="fa fa-comment-dots me-3 fa-2x" onclick="console.log('cliked')"></i>
                    <h3 class="text-center my-0 py-0">Follow</h3>
                </div>
            </div>
            <div class="row w-100 my-2">
                <div class="col-6 d-flex flex-column align-items-center">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fa fa-birthday-cake me-3 fa-1x" onclick="console.log('cliked')"></i>
                        <h5 class="mb-0">April 1st 2001 (20 yo)</h5>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fa fa-flag me-3 fa-1x" onclick="console.log('cliked')"></i>
                        <h5 class="mb-0">Porto, Portugal</h5>
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
