@extends('layouts.app')

<script type="text/javascript" src={{ asset('js/user.js') }} defer></script>

@section('content')
    <section id="editProfileContainer">
        <form name="profileForm" method="POST" action="{{ route('editProfile', ['id' => $user['id']]) }}"
            class="container-fluid py-3 w-75">
            @csrf
            <div class="row w-100 mt-5" id="editAvatarContainer">
                <label class="h1 py-0 my-0">Avatar</label>
                <div class="d-flex align-items-center h-100">
                    <img src={{ isset($user['avatar']) ? $user['avatar'] : $userImgPHolder }} id="avatarPreview"
                        onerror="this.src='{{ $userImgPHolder }}'" />
                    <input type="file" accept="image/*" id="imgInput" />
                </div>
            </div>
            <div class="row w-100 mt-5">
                <label class="h1 pb-3 my-0" for="nameInput">Username</label>
                <input type="text" value="{{ $user['name'] }}" class="text-center w-auto h1 px-0 mx-0"
                    style="border:black 1px solid" id="nameInput" />
            </div>
            <div class="row w-100 mt-5">
                <label class="h1 pb-3 my-0" for="nameInput">Birth Date</label>
                <input type="text" value="{{ $user['name'] }}" class="text-center w-auto h1 px-0 mx-0"
                    style="border:black 1px solid" id="nameInput" />
            </div>
            <div class="row w-100 mt-5">
                <label class="h1 pb-3 my-0" for="nameInput">Location</label>
                <input type="text" value="{{ $user['name'] }}" class="text-center w-auto h1 px-0 mx-0"
                    style="border:black 1px solid" id="nameInput" />
            </div>
            <div class="row w-100 mt-5">
                <label class="h1 pb-3 my-0" for="nameInput">Description</label>
                <input type="text" value="{{ $user['description'] }}" class="text-center w-auto h1 px-0 mx-0"
                    style="border:black 1px solid" id="nameInput" />
            </div>
        </form>
    </section>
@endsection
