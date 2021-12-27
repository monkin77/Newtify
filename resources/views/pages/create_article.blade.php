@extends('layouts.app')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">

@section('content')

    <div class="container bg-light">

        <div class="d-flex flex-row my-2 h-100">

            <div class="d-flex flex-column w-75 p-3 mb-0 text-dark" >

                <div class="flex-row" >
                    <h2 class="m-0"> 
                        <input type="text" id="input-title" class="h-100" placeholder="Insert Title">
                        {{-- input para titulo do artigo--}}
                    </h2>
                </div>

                <div class="flex-row mt-3 mb-1"> 
                    {{-- inputs para 3 tags --}}
                    <input class="px-3 mx-3" type="text" id="input-tags" data-role="tagsinput">
                </div>
                
                <div class="flex-row h-50 mb-5">
                    {{-- input para $article['thumbnail']--}}
                </div>
                                
                <div class="flex-row h-75">
                    {{-- input para $article['body']--}}
                </div>

            </div>  

        </div>

    </div>

@endsection
