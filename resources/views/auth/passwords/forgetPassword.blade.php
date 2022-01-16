@extends('layouts.app')

@section('content')

<div class="border text-center w-25 bg-light container mt-3">
    <h2 class="modal-titlemx-auto text-center fw-bold mt-4" id="exampleModalLabel">Forgot Password</h2>
    
    @if (Session::has('status')) 
        <div class="alert alert-success" role="alert">
            {{ Session::get('status') }}
        </div>
    @endif

    @if ($errors->has('email'))
        <p class="error text-danger">
            {{ $errors->first('email') }}
        </p>
    @endif
    
    <form method="POST" action="{{ route('sendLink') }}">
        @csrf  

        <label for="email" class="sr-only">Email address</label>
        <input name ="email" type="email" id="email" class="bg-white" value="{{ old('email') }}" placeholder="Email address" required autofocus>
        

        <button type="submit" class="button w-50 fw-bold">
            Send Email
        </button>

    </form>

</div>
@endsection
