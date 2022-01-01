<section id="articles" class="container">

    <div class="card flex-row flex-wrap" >
        <div class="card-header border-0" style="width: 20%;">
            <img src= {{
                isset($article['thumbnail']) ?
                $article['thumbnail']
                :
                $articleImgPHolder
            }}
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
    
</section>
