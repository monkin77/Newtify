@extends('layouts.app')

@section('content')

    <div class="container mb-3 bg-light" id="article-form">

        <div class="d-flex flex-row my-2 h-100">

            <div class="d-flex flex-column w-100 my-2 p-3 h-100">

                <form class="flex-row w-75 h-100">
                    <div class="flex-row">
                        <label for="input-title">Article's Title</label>
                        <h2 class="m-0"> 
                            <input type="text" class="h-100" id="input-title" name="input-title" placeholder="Insert Title">
                        </h2>
                    </div>

                    <div class="flex-row mt-3 mb-5"> 
                        <label for="input-tags">Article's Tags</label>
                        <input class="px-3 mx-3" type="text" id="input-tags" name="input-tags" data-role="tagsinput" placeholder="Insert Tags">
                    </div>
                    
                    <div class="flex-row">
                        <label for="input-thumbnail">Article's Thumbnail</label>
                        <input type="file" id="input-thumbnail" name="input-thumbnail" accept="image/png, image/jpeg, image/jpg">
                    </div>
                                    
                    <div class="flex-row h-100">
                        <label for="input-body">Article's Body</label>
                        <textarea id="input-body" name="input-body" rows="15" class="h-100" placeholder="Insert Body"></textarea>
                    </div>

                    <button type="button" class="">Create Article</button>
                </form>

            </div>

        </div>

    </div>

@endsection
