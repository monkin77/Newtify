@extends('layouts.app')

@section('scripts')
    <script src=" {{ asset('js/select2tags.js') }}"> </script>
@endsection

{{-- ------------------------------------------------------ --}}
@section('content')

    <div class="article-container container-fluid bg-light">

        <div class="d-flex flex-row my-2 h-100">

            <div class="articleInfoContainer d-flex flex-column mb-0 p-3 pe-5 h-100">

                <form name="article-form" method="POST" action="{{ route('createArticle') }}" class="flex-row h-100" enctype="multipart/form-data">
                    @csrf

                    <div class="flex-row">
                        <label for="title">{{ "Article's Title" }}</label>
                        <h2 class="m-0"> 
                            <input type="text" required minlength="3" maxlength="100" class="h-100"
                                id="title" name="title" placeholder="Insert Title" value="{{ old('title') }}">
                        </h2>
                        @if ($errors->has('title'))
                            <div class="alert alert-danger mt-2 mb-0 p-0 w-50 text-center" role="alert">
                                <p class="mb-0">{{ $errors->first('title') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex-row mt-3 mb-5 pe-3"> 
                        <label for="tags">{{ "Article's Tags" }}</label>

                        <select required id="tags" name="tags[]" multiple>
                            @foreach($tags as $tag)
                                <option value="{{$tag['id']}}"
                                    @if (old('tags') && in_array($tag['id'], old('tags')))
                                        selected
                                    @endif
                                >
                                    {{ $tag['name'] }}
                                </option>
                            @endforeach
                        </select>

                        @if ($errors->has('tags'))
                            <div class="alert alert-danger mt-2 mb-0 p-0 w-50 text-center" role="alert">
                                <p class="mb-0">{{ $errors->first('tags') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex-row">
                        <label for="thumbnail">Article's Thumbnail</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                    </div>
                    @if ($errors->has('thumbnail'))
                        <div class="alert alert-danger mt-2 mb-0 p-0 w-50 text-center" role="alert">
                            <p class="mb-0">{{ $errors->first('thumbnail') }}</p>
                        </div>
                    @endif
      
                    <div class="flex-row h-100">
                        <label for="body">{{ "Article's Body" }}</label>
                        <textarea id="body" required name="body" minlength="10" rows="15" class="h-100"
                            placeholder="Insert Body">{{ old('body') }}</textarea>

                        @if ($errors->has('body'))
                            <div class="alert alert-danger mt-2 mb-0 p-0 w-50 text-center" role="alert">
                                <p class="mb-0">{{ $errors->first('body') }}</p>
                            </div>
                        @endif
                    </div>

                    <button type="submit">Create Article</button>
                </form>

            </div>

            <div class="author-container flex-col p-3 text-dark">
                @include('partials.authorInfo', [
                    'author' => $author,
                    'isOwner' => true,
                    ])
            </div>

        </div>

    </div>

@endsection
