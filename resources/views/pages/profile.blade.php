@extends('layouts.app')

@php
$tagline = 'Que rego amiguinho';
@endphp

@section('content')

    <div id="userProfileContainer" class="d-flex py-3">
        <div class="container-fluid h-100" id="userInfo">
            <div class="row h-100 w-100">
                <div class="col-6 d-flex flex-column align-items-center h-100">
                    <div class="h-100">
                        <img src="https://i2.wp.com/www.cssscript.com/wp-content/uploads/2020/12/Customizable-SVG-Avatar-Generator-In-JavaScript-Avataaars.js.png?fit=438%2C408&ssl=1"
                            id="avatarImg" />
                        <h2 class="text-center">{{ $user['name'] }}</h2>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fa fa-birthday-cake me-3 fa-1x" onclick="console.log('cliked')"></i>
                            <h5 class="mb-0">April 1st 2001 (20 yo)</h5>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fa fa-flag me-3 fa-1x" onclick="console.log('cliked')"></i>
                            <h5 class="mb-0">Porto, Portugal</h5>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <h1> Areas of Expertise </h1>
                </div>

            </div>
            <div class="h-100 pt-4" id="description">
                <h6>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla at consequat odio. Nullam quis urna
                    commodo, scelerisque massa vulputate, facilisis enim. Mauris facilisis rutrum orci, sed congue enim
                    tempus sed. Ut dignissim odio in leo mattis, iaculis venenatis nisi ornare. In pellentesque, quam vitae
                    commodo vestibulum, ex magna vulputate massa, quis tincidunt velit metus et magna. Sed a augue urna.
                    Mauris ac elementum orci. Cras a posuere libero.</h6>
            </div>
        </div>
    </div>

@endsection
