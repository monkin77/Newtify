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
                        <label class="h1 pb-3 my-0" for="countryInput">Country</label>
                        <div class="d-flex position-relative align-items-center">
                            <select required name='countrySelector' value="{{ $user['country']['name'] }}"
                                id="countryInput" size=1 class="my-0">
                                <option value="Afganistan">Afghanistan</option>
                                <option value="Albania">Albania</option>
                            </select>
                            <i class="fa fa-caret-down fa-1x position-absolute" id='countryCaret'></i>
                        </div>
                    </div>
                    <div class="ms-5">
                        <label class="h1 pb-3 my-0" for="cityInput">City</label>
                        <input type="text" value="{{ $user['city'] }}" class="text-center w-auto h2 editInputs"
                            id="cityInput" />
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
