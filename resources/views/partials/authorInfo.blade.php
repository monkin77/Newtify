<div class="flex-row mt-1">
    <h2>Author</h2>
</div>

@if (isset($author))
    <div class="d-flex flex-row mb-3">
        <div class="flex-col w-25" style="margin-right: 1em;">
            <img id="authorAvatar" class="h-100" src={{
                isset($author['thumbnail']) ?
                $author['thumbnail']
                :
                $userImgPHolder
            }}>
        </div>
        <div class="flex-col w-75" id="author-header" style="padding-bottom: 0;">
            <h4 class="mb-2"> {{ $author['name'] }} </h4>
            <p> @if (isset($author['city']))
                    {{ $author['city'] }}, {{ $author['country']->name }}
                @else
                    {{ $author['country']->name }}
                @endif
            </p>
        </div>
    </div>

    <div class="flex-row mb-1">
        <p class="text-secondary">Reputation: {{ $author['reputation'] }} </p>
    </div>

    <div class="flex-row my-3">
        <p>
            @if (isset($author['description']))
                {{ $author['description'] }}
            @endif
        </p>
    </div>

    <div class="flex-row my-5">
        <h3>Areas of Expertise </h3>
        @foreach ($author['topAreasExpertise'] as $areaExpertise) 
            <p>{{ $areaExpertise['tag_name'] }} </p>
        @endforeach
    </div>
@else
    <div class="d-flex flex-row mb-3">
        <div class="flex-col w-25" style="margin-right: 1em;">
            <img id="authorAvatar" class="h-100" src={{ $userImgPHolder }}>
        </div>
        <div class="flex-col w-75" id="author-header" style="padding-top: 1em;">
            <h4 class="mb-2"><i>Anonymous</i></h4>
        </div>
    </div>

    <div class="flex-row my-3" style="padding-top: 50%">
        <p>The author has deleted his account</p>
    </div>
@endif