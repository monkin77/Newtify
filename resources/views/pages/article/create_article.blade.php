@extends('layouts.app')

@section('content')

    <div class="container mb-3 bg-light" id="article-form">

        <div class="d-flex flex-row my-2 h-100">

            <div class="d-flex flex-column w-75 my-2 p-3 h-100">

                <form method="POST" action="{{ route('createArticle') }}" class="flex-row h-100">
                    <div class="flex-row">
                        <label for="title">Article's Title</label>
                        <h2 class="m-0"> 
                            <input type="text" class="h-100" id="title" name="title" placeholder="Insert Title">
                        </h2>
                    </div>
                    
                    <div class="flex-row mt-3 mb-5"> 
                        <label for="tags">Article's Tags</label>
                        <input class="px-3 mx-3" type="text" id="tags" name="tags[]" data-role="tagsinput" placeholder="Insert Tags">
                    </div>
                    {{--
                    <div class="flex-row">
                        <label for="thumbnail">Article's Thumbnail</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/png, image/jpeg, image/jpg">
                    </div>
                    --}}
                                    
                    <div class="flex-row h-100">
                        <label for="body">Article's Body</label>
                        <textarea id="body" name="body" rows="15" class="h-100" placeholder="Insert Body"></textarea>
                    </div>

                    <button type="submit" class="">Create Article</button>
                </form>

            </div>

            <div class="flex-col w-25 ms-5 p-3 text-dark" id="author-container">
                @include('partials.authorInfo', ['author' => $author])
            </div>

        </div>

    </div>

@endsection
