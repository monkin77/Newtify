<div class="d-flex flex-row mx-0 my-3 p-0 w-75"> 
    <div class="flex-column h-100 commentHeader mx-5 my-0 p-0">
        <img src="{{
            (isset($comment['author']) && isset($comment['author']['avatar'])) ?
            asset('storage/avatars/'.$comment['author']['avatar']) : $userImgPHolder 
        }}" onerror="this.src='{{ $userImgPHolder }}'" />

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

        <span class="px-3">{{ $comment['published_at'] }} </span>
    </div>

</div>
