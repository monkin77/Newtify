@php
function calculateExpertiseLevel($reputation)
{
    $step = 13;
    if ($reputation <= 0) {
        return 0;
    } elseif ($reputation < 5) {
        return $step * 1;
    } elseif ($reputation < 10) {
        return $step * 2;
    } elseif ($reputation < 20) {
        return $step * 3;
    } elseif ($reputation < 40) {
        return $step * 4;
    } else {
        return $step * 5;
    }
}
@endphp

<div class="h-100 text-white" id="graphContainer">
    <h4 class="text-center pt-3 pb-0 my-0">Areas of Expertise</h4>
    <div class="d-flex flex-column h-100 justify-content-evenly pb-5">
        @foreach ($topAreasExpertise as $area)
            <div class="d-flex align-items-center ms-3">
                <p class="my-0 py-0 pe-3 tagName">{{ $area['tag_name'] }}</p>
                <div class="tagBar" style="width: {{ calculateExpertiseLevel($area['reputation']) . '%' }}">
                </div>
            </div>
        @endforeach
    </div>
</div>
