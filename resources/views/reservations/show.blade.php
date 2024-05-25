{{-- reservation-details.blade.php --}}
<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-gray-700 border-b border-gray-200 text-white">
                <h2 class="font-semibold text-xl leading-tight mb-4">
                    Reservation Details #{{ $reservation->id }}
                </h2>
                <div>
                    <p><strong>Customer Name:</strong> {{ $reservation->name }}</p>
                    <p><strong>Phone:</strong> {{ $reservation->phone }}</p>
                    <p><strong>Time:</strong> {{ $reservation->slot->date_time }}</p>
                    <p><strong>Party size:</strong> {{ $reservation->party_size }}</p>

                    <div class="my-4">
                        <a href="{{ route('companies.slots.edit', [$reservation->slot->company, $reservation->slot]) }}"
                            class=" text-white font-bold py-2 rounded-md underline">Check slot</a>
                    </div>
                </div>
                @if ($reservation->canceled_at)
                    <div class="bg-red-700 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md">
                        <span>Canceled</span>
                    </div>
                @else
                    <form action="/reservations/cancel" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md"
                            type="submit">Cancel</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
