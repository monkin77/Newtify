@extends('layouts.app')

@section('content')

    <div class="border text-center w-25 bg-light container mt-3">
        <h2 class="modal-titlemx-auto text-center fw-bold mt-4" id="exampleModalLabel">Forgot Password</h2>
        
        @if (Session::has('status')) 
            <div class="alert alert-success" role="alert">
                {{ Session::get('status') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('password.update') }}">
            @csrf  

            <label for="password">Password</label>
            <input name="password" class="bg-white w-50" type="password" id="password" placeholder="Password" required
                onkeyup="checkPass('#password')">

            @if ($errors->has('password'))
                <br>
                <span class="text-danger error">
                    {{ $errors->first('password') }}
                </span>
            @endif

            <label for="password">Confirm Password</label>
            <div class="">
                <input name="password_confirmation" class="bg-white w-50" type="password" id="password-confirm"
                    placeholder="Confirm Password" required onkeyup="checkPass('#password')">
                <span class="ms-3 mt-2 position-absolute" id="matchingPass"></span>
            </div>

            <input type="hidden" name="token" value="{{ request()->token }}">
            <input type="hidden" name="email" value="{{ request()->email }}">

            <button type="submit" class="button w-50 fw-bold">
                Reset Password
            </button>

        </form>

    </div>
@endsection