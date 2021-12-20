{{-- page of an article --}}
<h1> {{ $article['title'] }} </h1>

<p> {{ $article['body'] }} </p>

@foreach ($comments as $comment)
    <p> Comentario: {{ $comment['body'] }} </p>
@endforeach