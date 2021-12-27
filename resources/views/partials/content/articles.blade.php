@section('articles')
<section id="articles">

    <div class="container">
        @foreach($articles as $article)
            @include('partials.content.article', ['article' => $article])
        @endforeach
    </div>

</section>
@endsection