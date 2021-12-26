@extends('layouts.app')

@section('content')

<div class="border text-center w-25 bg-light container">
    <h2 class="modal-titlemx-auto text-center fw-bold" id="exampleModalLabel">Sign In</h2>
    <form method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}
        
        <label for="email" class="sr-only">Email address</label>
        <input name ="email" type="email" id="email" class="bg-white" value="{{ old('email') }}" placeholder="Email address" required autofocus>
        @if ($errors->has('email'))
            <span class="error">
                {{ $errors->first('email') }}
            </span>
        @endif
        
        <label for="password" class="sr-only">Password</label>
        <input name="password" type="password" id="password" class="bg-white" placeholder="Password" required="">
        @if ($errors->has('password'))
            <span class="error">
                {{ $errors->first('password') }}
            </span>
        @endif
        
        <div class="checkbox mb-3">
            <label>
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
            </label>
        </div>
        <button type="submit" class="btn btn-secondary w-50 fw-bold">
        Login
        </button>
        <a class="button button-outline w-50" href="{{ route('register') }}">Register</a>
    </form>
</div>
@endsection
