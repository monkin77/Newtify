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
                <input type="text" required value="{{ $user['name'] }}" class="text-center w-auto h2 editInputs"
                    id="nameInput" />
            </div>
            <div class="row w-100 mt-5">
                <label class="h1 pb-3 my-0" for="birthDateInput">Birth Date</label>
                <input type="date" required value="{{ $birthDate }}" class="text-center w-auto h2 editInputs py-4"
                    id="birthDateInput" />
            </div>
            <div class="row w-100 mt-5">
                <div class="d-flex">
                    <div class="pe-5 me-5">
                        <label class="h1 pb-3 my-0" for="nameInput">Country</label>
                        <input type="text" value="{{ $user['name'] }}" class="text-center w-auto h2 editInputs"
                            id="nameInput" />
                    </div>
                    <div class="ms-5">
                        <label class="h1 pb-3 my-0" for="nameInput">City</label>
                        <input type="text" value="{{ $user['name'] }}" class="text-center w-auto h2 editInputs"
                            id="nameInput" />
                    </div>
                </div>
            </div>
            <div class="row w-100 mt-5">
                <label class="h1 pb-3 my-0" for="descriptionInput">Description</label>
                <textarea id="descriptionInput" name="description" rows="10"
                    class="h-100 editInputs py-2">{{ $user['description'] }}</textarea>
            </div>
        </form>
    </section>
@endsection
