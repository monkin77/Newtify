@php
if (!function_exists('calculateReputationLevel')) {
    function calculateReputationLevel($reputation)
    {
        $isPositive = $reputation >= 0;
        $absReputation = abs($reputation);

        if ($absReputation <= 90) {
            return 10 + $absReputation;
        }

        return 100;
    }
}

$guest = !Auth::check();
@endphp

<div class="d-flex flex-column justify-content-center" id="reputationBar">
    <div class="position-relative" id="bar">
        <div class="position-absolute h-100"
            style="background-color: {{ $user['reputation'] >= 0 ? 'green' : 'red' }}; width: {{ calculateReputationLevel($user['reputation']) . '%' }}; border-radius: 0.7em">
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-2">
        <h6 class="my-0 py-0 pt-2 ">Reputation: {{ $user['reputation'] }}</h6>
        @if (!$guest && !$isOwner)
            <button class="btn px-2 m-0 btn btn-transparent" onclick="toggleReportPopup()">
                <i class="fa fa-exclamation-circle fa-2x" id="reportIcon"></i>
            </button>
        @endif
    </div>

</div>
