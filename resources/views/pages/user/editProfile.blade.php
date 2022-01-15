@extends('layouts.app')

@php
$isOpen = $errors->has('password');
@endphp

@section('scripts')
    <script type="text/javascript" src={{ asset('js/user.js') }} defer></script>
    <script src=" {{ asset('js/select2tags.js') }}"> </script>
@endsection

@section('content')
    <section id="editProfileContainer">
        <form name="profileForm" method="POST" enctype="multipart/form-data"
            action="{{ route('editProfile', ['id' => $user['id']]) }}" class="container-fluid py-3 w-75">
            @method('put')
            @csrf

            <div class="row w-100 " id="editAvatarContainer">
                <label class="h2 py-0 my-0">Avatar</label>
                <div id="avatarPreviewContainer" class="d-flex align-items-center">
                    <img src={{ isset($user['avatar']) ? asset('storage/avatars/' . $user['avatar']) : $userImgPHolder }}
                        id="avatarPreview" onerror="this.src='{{ $userImgPHolder }}'" />
                    <input type="file" accept="image/*" id="imgInput" name='avatar' />
                    @if ($errors->has('avatar'))
                        <div class="alert alert-danger ms-3 w-50 text-center py-1" role="alert">
                            <p class="">{{ $errors->first('avatar') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row w-100 mt-3">
                <div class="col-6">
                    <div class="row w-100">
                        <div class="row">
                            <div class="col-6">
                                <label class="h2 pb-3 my-0" for="nameInput">Username</label>
                                <input type="text" required value="{{ old('name') ? old('name') : $user['name'] }}"
                                    class="h2 editInputs w-75" id="nameInput" name='name' />
                                @if ($errors->has('name'))
                                    <div class="alert alert-danger ms-3 w-50 text-center py-1" role="alert">
                                        <p class="">{{ $errors->first('name') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-6">
                                <label class="h2 pb-3 my-0" for="birthDateInput">Birth Date</label>
                                <input type="date" required value="{{ old('birthDate') ? old('birthDate') : $birthDate }}"
                                    class="w-75 h2 editInputs py-4 ps-3" id="birthDateInput" name='birthDate' />
                                @if ($errors->has('birthDate'))
                                    <div class="alert alert-danger ms-3 w-100 text-center py-1" role="alert">
                                        <p class="">{{ $errors->first('birthDate') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row w-100 mt-5">
                        <div class="row">
                            <div class="col-6">
                                <label class="h2 pb-3 my-0" for="countryInput">Country</label>
                                <div class="d-flex position-relative align-items-center h2 w-75" id='countryInputContainer'>
                                    <select required name='country'
                                        value="{{ old('country') ? old('country') : $user['country']['name'] }}"
                                        id="countryInput" size=1 class="my-0 border-0">
                                        @foreach ($countries as $country)
                                            <option value="{{ $country['name'] }}" @if (old('country') ? old('country') == $country['name'] : $user['country']['id'] == $country['id'])
                                                selected
                                        @endif>
                                        {{ $country['name'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <i class="fa fa-caret-down fa-1x position-absolute caretDown"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="h2 pb-3 my-0" for="cityInput">City</label>
                                <input type="text" value="{{ old('city') ? old('city') : $user['city'] }}"
                                    class="w-75 h2 editInputs" id="cityInput" name='city' />

                            </div>
                        </div>
                        @if ($errors->has('country'))
                            <div class="alert alert-danger ms-3 w-50 text-center py-1" role="alert">
                                <p class="">{{ $errors->first('country') }}</p>
                            </div>
                        @endif
                        @if ($errors->has('city'))
                            <div class="alert alert-danger ms-3 w-50 text-center py-1" role="alert">
                                <p class="">{{ $errors->first('city') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-6">
                    <label class="h2 mb-3" for="tagsInput">Favorite Tags</label>

                    <select required id="favoriteTags" name="favoriteTags[]" multiple>
                        @foreach ($tags as $tag)
                            <option class="m-0" @if (old('favoriteTags') ? in_array($tag['id'], old('favoriteTags')) : $favoriteTags->contains('id', $tag['id']))
                                selected
                        @endif
                        value="{{ $tag['id'] }}">{{ $tag['name'] }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('favoriteTags'))
                        <div class="alert alert-danger mt-2 mb-0 p-0 w-50 text-center" role="alert">
                            <p class="mb-0">{{ $errors->first('favoriteTags') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row w-100 mt-5">
                <label class="h2 pb-3 my-0" for="descriptionInput">Description</label>
                <textarea id="descriptionInput" name="description" rows="7"
                    class="h-100 editInputs py-2">{{ old('description') ? old('description') : $user['description'] }}</textarea>
                @if ($errors->has('description'))
                    <div class="alert alert-danger ms-3 w-50 text-center py-1" role="alert">
                        <p class="">{{ $errors->first('description') }}</p>
                    </div>
                @endif
            </div>

            <div class="row w-100 mt-2 d-flex justify-content-center">
                <button type="submit" class="w-auto text-center px-5">Edit Profile</button>
            </div>
        </form>

        <div class="container-fluid py-3 w-75 mt-5 pt-5 border-top border-dark">
            <div class="d-flex position-relative mb-3">
                <a class="h1 w-100" href="#advancedContainer" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-contols="advancedContainer">Advanced
                    Settings</a>
                <i class="fa fa-caret-down fa-1x position-absolute pe-none caretDown"></i>
            </div>
            <div <?php if ($isOpen) {
    echo 'class="colapse show"';
} else {
    echo 'class="collapse"';
} ?> id="advancedContainer">
                <form name="passForm" method="POST" action="{{ route('editProfile', ['id' => $user['id']]) }}">
                    @method('put')
                    @csrf
                    @if ($errors->has('password'))
                        <div class="alert alert-danger ms-3 w-50 text-center py-1" role="alert">
                            <p class="">Invalid Password</p>
                        </div>
                    @endif
                    <label for="currPassInput" class="form-label mt-4">Change Password</label>
                    <div class="d-flex align-items-end">
                        <div class="me-5">
                            <div class="form-text">Current Password</div>
                            <input type="password" required class="w-auto h2 editInputs" id="currPassInput" name='password'
                                placeholder="Current Password" />
                        </div>
                        <div class="me-5">
                            <div class="form-text">New Password</div>
                            <input type="password" required class="w-auto h2 editInputs" id="newPassInput"
                                name='new_password' placeholder="New Password" onkeyup="checkPass('#newPassInput')" />
                        </div>
                        <div class="me-5">
                            <div class="form-text">Confirm Password</div>
                            <input type="password" required name="new_password_confirmation" class="w-auto h2 editInputs"
                                id="newPassInput-confirm" placeholder="Confirm Password"
                                onkeyup="checkPass('#newPassInput')">
                            <span class="ms-2" id="matchingPass"></span>
                        </div>
                        <button type="submit">Change</button>
                    </div>
                    @if ($errors->has('new_password'))
                        <div class="alert alert-danger ms-3 w-50 text-center py-1" role="alert">
                            <p class="">{{ $errors->first('new_password') }}</p>
                        </div>
                    @endif
                </form>
                <form name="emailForm" method="POST" action="{{ route('editProfile', ['id' => $user['id']]) }}"
                    class="mt-3" autocomplete="off">
                    @method('put')
                    @csrf
                    <label for="emailPassInput" class="form-label mt-4">Change Email</label>
                    <div class="d-flex align-items-end">
                        <div class="me-5">
                            <div class="form-text">Current Password</div>
                            <input type="password" autocomplete="new-password" required class="w-auto h2 editInputs"
                                id="emailPassInput" name='password' placeholder="Current Password" />
                        </div>
                        <div class="me-5">
                            <div class="form-text">New Email</div>
                            <input type="email" required class="w-auto h2 editInputs" id="emailInput" name='email'
                                placeholder="New Email" value="{{ old('email') }}" />
                        </div>
                        <button type="submit">Change</button>
                    </div>
                    @if ($errors->has('email'))
                        <div class="alert alert-danger ms-3 w-50 text-center py-1" role="alert">
                            <p class="">{{ $errors->first('email') }}</p>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </section>
@endsection
