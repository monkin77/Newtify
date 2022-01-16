@foreach ($comments as $comment)
    @include('partials.content.comment', ['comment' => $comment, 'isReply' => false])

    @foreach ($comment['children'] as $child)

    <div class="d-flex justify-content-end w-75">
        <div class="child-comment">
            @include('partials.content.comment', ['comment' => $child, 'isReply' => true])
        </div>
    </div>
    @endforeach

@endforeach
