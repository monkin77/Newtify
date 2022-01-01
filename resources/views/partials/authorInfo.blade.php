@if (isset($author))

    <div class="d-flex flex-column h-25 py-1">
        <h2 class="py-0 my-0 h-25">Author</h2>

        <div class="d-flex flex-row pt-3 h-50">
            <div class="w-25 me-3">
                <img id="authorAvatar" class="h-100" src={{
                    isset($author['thumbnail']) ?
                    $author['thumbnail']
                    :
                    $userImgPHolder
                }}>
            </div>
            <div class="w-75 pb-0" id="author-header">
                <h4 class="mb-2"> {{ $author['name'] }} </h4>
                <p> @if (isset($author['city']))
                        {{ $author['city'] }}, {{ $author['country']->name }}
                    @else
                        {{ $author['country']->name }}
                    @endif
                </p>
            </div>
        </div>

        <div class="d-flex h-25 justify-content-center align-items-center">
            @include('partials.user.reputationBar', [
                'user' => $author,
                'guest' => !Auth::check(),
                'isOwner' => $isOwner,
            ])
        </div>
    </div>

    <div class="flex-row px-1 py-4" id="authorDescription">
        <p>{{ (isset($author['description']) ? $author['description'] : '') }}</p>
    </div>

    <div class="flex-row p-3" id="authorAreaExpertise">
        @include('partials.user.areasOfExpertiseGraph', ['topAreasExpertise' => $author['topAreasExpertise']])
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