@extends('layouts.app')

@section('content')

    <div class="container mb-3 bg-light" id="article-form">

        <div class="d-flex flex-row my-2 h-100">

            <div class="d-flex flex-column w-100 my-2 p-3 h-100">

                <form class="flex-row w-75 h-100">
                    <div class="flex-row">
                        <h2 class="m-0"> 
                            <input type="text" class="h-100" id="input-title" name="input-title" placeholder="Insert Title">
                            {{-- input para titulo do artigo--}}
                        </h2>
                    </div>

                    <div class="flex-row mt-3 mb-5"> 
                        {{-- inputs para 3 tags --}}
                        <input class="px-3 mx-3" type="text" id="input-tags" name="input-tags" data-role="tagsinput" placeholder="Insert Tags">
                    </div>
                    
                    <div class="flex-row">
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/png, image/jpeg, image/jpg">
                        {{-- input para $article['thumbnail']--}}
                    </div>
                                    
                    <div class="flex-row h-100">
                        {{-- input para $article['body']--}}
                        <textarea id="input-body" name="input-body" rows="15" class="h-100" placeholder="Insert Body"></textarea>
                    </div>

                    <button type="button" class="">Create Article</button>
                </form>

            </div>

        </div>

    </div>

@endsection
