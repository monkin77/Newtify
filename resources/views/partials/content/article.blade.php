<div class="card flex-row flex-wrap" >
    <div class="card-header border-0" style="width: 20%;">
        <img src=
        @if (isset($article['thumbnail']))
            {{ $article['thumbnail'] }}
        @else
            "https://i.pinimg.com/originals/e4/34/2a/e4342a4e0e968344b75cf50cf1936c09.jpg"
        @endif
        style="width: 100%;">
    </div>

    <a href="/article/{{ $article['id'] }}">
        <div class="card-block d-flex flex-column px-2">
            <h4 class="card-title">
                {{ $article['title'] }} 
                <i class="fa fa-thumbs-up"> {{ $article['likes'] }}</i>
                <i class="fa fa-thumbs-down"> {{ $article['dislikes'] }}</i>
            </h4>

            <p class="card-text"> 
                <i class="far fa-clock pe-2"> 
                    @php
                        $time = date('F j, Y', /*, g:i a',*/ strtotime( $article['published_at'] ) )
                    @endphp
                    {{ $time }}
                </i>
            </p>

            <p class="card-text">{{ mb_strimwidth($article['body'], 0, 150, "...") }} </p>
        </div>
    </a>
    
</div>
