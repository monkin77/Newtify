@extends('layouts.app')

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src=" {{ asset('js/select2tags.js') }}"> </script>
@endsection

{{-- ------------------------------------------------------ --}}
@section('content')

    <div class="article-container container-fluid bg-light">

        <div class="d-flex flex-row my-2 h-100">

            <div class="articleInfoContainer d-flex flex-column mb-0 p-3 pe-5 h-100">

                <form name="article-form" method="POST" action="{{ route('editArticle', ['id' => $article['content_id']]) }}" class="flex-row h-100" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="flex-row">
                        <label for="title">{{ "Edit article's Title" }}</label>
                        <h2 class="m-0"> 
                            <input type="text" autofocus required minlength="3" maxlength="100" class="h-100" id="title"
                                name="title" value="{{ old('title') ? old('title') : $article['title'] }}">
                        </h2>
                        @if ($errors->has('title'))
                            <div class="alert alert-danger mt-2 mb-0 p-0 w-50 text-center" role="alert">
                                <p class="mb-0">{{ $errors->first('title') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex-row mt-3 mb-5 pe-3"> 
                        <label for="tags">{{ "Edit Article's Tags" }}</label>

                        <select required id="tags" name="tags[]" multiple>
                            @foreach($tags as $tag)
                                <option class="m-0"
                                @if ( old('tags') ?
                                in_array($tag['id'], old('tags'))
                                :
                                $articleTags->contains('name', $tag['name'])
                                )
                                    selected
                                @endif
                                value="{{$tag['id']}}">{{ $tag['name'] }}</option>
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

                    <div class="flex-row h-100">
                        <label for="body">{{ "Edit Article's Body" }}</label>
                        <textarea id="body" required name="body" minlength="10" rows="15" class="h-100">{{
                            old('body') ? old('body') : $article['body']
                        }}</textarea>
                        @if ($errors->has('body'))
                            <div class="alert alert-danger mt-2 mb-0 p-0 w-50 text-center" role="alert">
                                <p class="mb-0">{{ $errors->first('body') }}</p>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="">Edit Article</button>
                </form>

            </div>

            <div class="author-container flex-col p-3 text-dark">
                @include('partials.authorInfo', [
                    'author' => $author,
                    'isOwner' => true
                ])
            </div>

        </div>

    </div>

@endsection
