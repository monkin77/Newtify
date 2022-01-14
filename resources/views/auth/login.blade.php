@extends('layouts.app')

@section('content')

<div class="border text-center w-25 bg-light container mt-3">
    <h2 class="modal-titlemx-auto text-center fw-bold mt-4" id="exampleModalLabel">Log In</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email" class="sr-only">Email address</label>
        <input name ="email" type="email" id="email" class="bg-white" value="{{ old('email') }}" placeholder="Email address" required autofocus>
        @if ($errors->has('email'))
            <span class="error text-danger">
                {{ $errors->first('email') }}
            </span>
        @endif
        
        <label for="password" class="sr-only">Password</label>
        <input name="password" type="password" id="password" class="bg-white" placeholder="Password" required="">
        @if ($errors->has('password'))
            <span class="error text-danger">
                {{ $errors->first('password') }}
            </span>
        @endif

        <label>
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
        </label>

        @if ($errors->has('suspended'))
            <div class="error text-danger text-center">
                {{ $errors->first('suspended') }} for the following reason:
            </div>
            <div class="error text-danger fw-bold fst-italic my-3">
                {{ $errors->first('reason') }}
            </div>
            <div class="error text-danger mb-3">
                You will be unsuspended on {{ $errors->first('endDate') }}
            </div>
        @endif

        <button type="submit" class="button w-50 fw-bold">
        Login
        </button>
        <a class="button button-outline w-50" href="{{ route('signup') }}">Register</a>
    </form>
</div>
@endsection
