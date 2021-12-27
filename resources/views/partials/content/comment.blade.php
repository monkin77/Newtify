<div class="d-flex flex-row mx-0 my-3 p-0 w-75"> 
    <div class="flex-column h-100 commentHeader mx-5 my-0 p-0">
        <img src={{
            isset($comment['authorAvatar']) ?
            $comment['authorAvatar']
            :
            "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png"
        }}>

        @if (isset($comment['author']))
            {{ $comment['author']['name'] }}
        @else
            <i>Anonymous</i>
        @endif
    </div>

    <div class="flex-column m-0 p-0 w-100">
        <textarea readonly style="resize: none;" class="flex-column m-0 px-3">{{ $comment['body'] }}</textarea>
        
        <i class="fa fa-thumbs-up "> {{ $comment['likes'] }}</i>
        <i class="fa fa-thumbs-down ps-3 pe-3"> {{ $comment['dislikes'] }}</i>

        @if (Auth::check())
            <span class="px-3">Reply</span>
        @endif

        @php
            $comment_published_at = date('F j, Y', /*, g:i a',*/ strtotime( $comment['published_at'] ) )   
        @endphp
        <span class="px-3">{{ $comment_published_at }} </span>
    </div>

</div>
