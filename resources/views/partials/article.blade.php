<div class="card flex-row flex-wrap" >
    <div class="card-header border-0" style="width: 20%;">
        <img src="{{ $article['thumbnail'] }}" style="width: 100%;">
    </div>
    
    <div class="card-block d-flex flex-column px-2" style="width: 80%;" >
        <h4 class="card-title">
            {{ $article['title'] }} 
            <i class="fa fa-thumbs-up"> {{ $article['likes'] }}</i>
            <i class="fa fa-thumbs-down"> {{ $article['dislikes'] }}</i>
        </h4>

        <p class="card-text">{{ $article['body'] }} </p>
    </div>
    
</div>
