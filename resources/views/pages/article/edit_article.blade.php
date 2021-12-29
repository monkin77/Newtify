@extends('layouts.app')

{{-- ------------------------------------------------------------------ --}}

@section('article-info')
        
    <div class="d-flex flex-column w-100 my-2 p-3 h-100">

        <form class="flex-row w-75 h-100">
            <div class="flex-row">
                <label for="input-title">Edit article's Title</label>
                <h2 class="m-0"> 
                    <input type="text" 
                        class="h-100" id="input-title" name="input-title" 
                        placeholder="{{ $article['title'] }}">
                </h2>
            </div>

            <div class="flex-row mt-3 mb-5"> 
                <label for="input-tags">New article's Tags</label>
                <input type="text" 
                    class="px-3 mx-3" id="input-tags" name="input-tags" 
                    value="
                        @foreach($tags as $tag)
                            {{ $tag['name'] }},
                        @endforeach
                    "
                    data-role="tagsinput" placeholder="Add Tags">
            </div>
            
            <div class="flex-row">
                <label for="input-thumbnail">New article's thumbnail</label>
                <input type="file" id="input-thumbnail" name="input-thumbnail" accept="image/png, image/jpeg, image/jpg">
            </div>
                            
            <div class="flex-row h-100">
                <label for="input-body">New article's Body</label>
                <textarea 
                    id="input-body" name="input-body" class="h-100"
                    rows="15" 
                    placeholder=" {{ $article['body'] }}"></textarea>
            </div>

            <button type="button" class="">Update Article</button>
        </form>

    </div>

@endsection

{{-- ------------------------------------------------------------------ --}}


@section('content')

    <div class="container mb-3 bg-light" id="article-form">

        <div class="d-flex flex-row my-2 h-100">

            @yield('article-info')
            
        </div>

    </div>

@endsection
