@if ($getRecord()->logo)
    <img class="h-10 w-10 object-contain" src="{{ Storage::url('logos/' . $getRecord()->id . '.png') }}" alt="">
@else
    <span>-</span>
@endif
