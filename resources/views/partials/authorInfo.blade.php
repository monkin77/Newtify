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
            <p> {{ (isset($author['city']) ? ($author['city'] . ', ') : '') . $author['country']['name'] }}
            </p>
        </div>
    </div>

    <div class="flex-row d-flex justify-content-center align-items-center">
        @include('partials.user.reputationBar', ['user' => $author])
    </div>

    <div class="flex-row my-3">
        <p>
            {{ (isset($author['description']) ? $author['description'] : '') }}
        </p>
    </div>

    <div class="flex-row my-5">
        <h3>Areas of Expertise </h3>
        <div class="col">
            @include('partials.user.areasOfExpertiseGraph', ['topAreasExpertise' => $author['topAreasExpertise']])
        </div>
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