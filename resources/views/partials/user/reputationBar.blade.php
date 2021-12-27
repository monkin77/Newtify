@php
function calculateReputationLevel($reputation)
{
    $isPositive = $reputation >= 0;
    $absReputation = abs($reputation);

    if ($absReputation <= 90) {
        return 10 + $absReputation;
    }

    return 100;
}
@endphp

<div class="d-flex flex-column justify-content-center">
    <div class="position-relative" style="background-color: gray; width: 15em; height: 1.5em; border-radius: 0.7em">
        <div class="position-absolute"
            style="background-color: {{ $user['reputation'] >= 0 ? 'green' : 'red' }}; width: {{ calculateReputationLevel($user['reputation']) . '%' }}; height: 1.5em; border-radius: 0.7em">
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <h6 class="my-0 py-0 pt-2 ">Reputation: {{ $user['reputation'] }}</h6>
        <i class="fa fa-exclamation-circle fa-1x" id="reportIcon" onclick="console.log('cliked')"></i>
    </div>

</div>
