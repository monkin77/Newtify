@foreach ($comments as $comment)
    @include('partials.content.comment', ['comment' => $comment])
@endforeach
