@extends('layouts.app')

@section('content')

<div class="border border-light border-3 text-center bg-secondary container-fluid mt-4 mb-5" id="registerContainer">
    <h2 class="modal-titlemx-auto text-center fw-bold mt-4" id="exampleModalLabel">Sign Up</h2>
    <form class="form-group" method="post" action="{{ route('signup') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <label for="name">Name</label>
        <input name="name" class="w-50 customInput" type="text" id="name" placeholder="Enter name" value="{{ old('name') }}" required autofocus>
        @if ($errors->has('name'))
        <br>
        <span class="text-danger error">
            {{ $errors->first('name') }}
        </span>
        @endif

        <label for="email">Email address</label>
        <input name="email" class="w-50 customInput" type="email" id="email" placeholder="Enter email" value="{{ old('email') }}" required>
        @if ($errors->has('email'))
        <br>
        <span class="text-danger error">
            {{ $errors->first('email') }}
        </span>
        @endif

            <label for="password">Password</label>
            <input name="password" class="w-50 customInput" type="password" id="password" placeholder="Password" required
                onkeyup="checkPass('#password')">
            @if ($errors->has('password'))
                <br>
                <span class="text-danger error">
                    {{ $errors->first('password') }}
                </span>
            @endif

            <label for="password">Confirm Password</label>
            <div class="">
                <input name="password_confirmation" class="w-50 customInput" type="password" id="password-confirm"
                    placeholder="Confirm Password" required onkeyup="checkPass('#password')">
                <span class="ms-3 mt-2 position-absolute" id="matchingPass"></span>
            </div>

        <label for="birthDate">Birth Date</label>
        <input name="birthDate" class="customInput" type="date" id="birthDate" value="{{ old('birthDate') }}" required>
        @if ($errors->has('birthDate'))
        <br>
        <span class="text-danger error">
            {{ $errors->first('birthDate') }}
        </span>
        @endif

        <label for="country">Country</label>
        <input name="country" class="w-50 customInput" type="text" id="country" placeholder="Country" value="{{ old('country') }}" required>
        @if ($errors->has('country'))
        <br>
        <span class="text-danger error">
            {{ $errors->first('country') }}
        </span>
        @endif

        <label for="avatar">Choose avatar</label>
        <input name="avatar" type="file" id="avatar">
        <br>
        <button type="submit" class="btn btn-purple btn-lg customBtn w-50 fw-bold">Sign Up</button>
    </form>
</div>

@endsection
