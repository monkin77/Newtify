@extends('layouts.app')

@section('content')
    <div class="container mb-3 bg-light" id="article-form">

        <div class="d-flex flex-row my-2 h-100">

            <div class="d-flex flex-column w-75 my-2 p-3 h-100">

                <form name="article-form" method="POST" action="{{ route('createArticle') }}" class="flex-row h-100">
                    {{ csrf_field() }}

                    <div class="flex-row">
                        <label for="title">Article's Title</label>
                        <h2 class="m-0"> 
                            <input type="text" onchange="formatTags()" autofocus required minlength="3" maxlength="100" class="h-100" id="title" name="title" placeholder="Insert Title">
                        </h2>
                        @if ($errors->has('title'))
                            <div class="alert alert-danger mt-2 mb-0 p-0 w-50 text-center" role="alert">
                                <p class="mb-0">{{ $errors->first('title') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex-row mt-3 mb-5"> 
                        <label for="tags">Article's Tags</label>
                        <select required id="tags" name="tags[]" multiple data-role="tagsinput">
                            <option></option>
                            <option></option>
                            <option></option>
                        </select>
                        @if ($errors->has('tags'))
                            <div class="alert alert-danger mt-2 mb-0 p-0 w-50 text-center" role="alert">
                                <p class="mb-0">{{ $errors->first('tags') }}</p>
                            </div>
                        @endif
                        {{--
                            <input class="px-3 mx-3" required value="" min-tags="1" minlength="1" maxlength="3" type="text" id="tags" name="tags[]" data-role="tagsinput" placeholder="Insert Tags">
                        --}}
                    </div>

                    {{--
                    <div class="flex-row">
                        <label for="thumbnail">Article's Thumbnail</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                    </div>
                    --}}
                                    
                    <div class="flex-row h-100">
                        <label for="body">Article's Body</label>
                        <textarea id="body" required name="body" minlength="10" rows="15" class="h-100" placeholder="Insert Body"></textarea>
                        @if ($errors->has('body'))
                            <div class="alert alert-danger mt-2 mb-0 p-0 w-50 text-center" role="alert">
                                <p class="mb-0">{{ $errors->first('body') }}</p>
                            </div>
                        @endif
                    </div>

                    <button type="submit" onclick="formatTags()" class="">Create Article</button>
                </form>

            </div>

            <div class="flex-col w-25 ms-5 p-3 text-dark" id="author-container">
                @include('partials.authorInfo', ['author' => $author])
            </div>

        </div>

    </div>

@endsection
