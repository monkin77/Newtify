<div class="d-flex flex-row mx-0 my-3 p-0 w-75"> 
    {{-- {{ $comment['authorAvatar'] }} --}}
    <div class="flex-column h-100 commentHeader mx-5 my-0 p-0">
        <img src="https://i.pinimg.com/originals/e4/34/2a/e4342a4e0e968344b75cf50cf1936c09.jpg">
        {{ $comment['authorName'] }}
    </div>
    
    <div class="flex-column m-0 p-0 w-100">
        <textarea readonly style="resize: none;" class="flex-column m-0 px-3">{{ $comment['body'] }}</textarea>
        
        <i class="fa fa-thumbs-up "> {{ $comment['likes'] }}</i>
        <i class="fa fa-thumbs-down ps-3 pe-3"> {{ $comment['dislikes'] }}</i>

        <span class="px-3">Reply</span>
        @php
            $comment_published_at = date('F j, Y', /*, g:i a',*/ strtotime( $comment['published_at'] ) )   
        @endphp
        <span class="px-3">{{ $comment_published_at }} </span>
    </div>

</div>

