<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Slots') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="p-6 bg-gray-800 rounded-md shadow-md">
                <div class="grid grid-cols-2">
                    <h2 class="mb-2 text-lg font-semibold text-white">Edit slot</h2>
                    @if ($slot->isReserved())
                        <span class="text-sm text-gray-400 text-right">Reserved! Can't edit.</span>
                    @endif
                </div>
                <form action="{{ route('companies.slots.update', [$company, $slot]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="date_time" class="block text-sm font-medium text-white">Date &amp; Time</label>
                            @if ($errors->has('date_time'))
                                <span class="text-red-500 text-xs">{{ $errors->first('date_time') }}</span>
                            @endif
                            <input type="datetime-local" name="date_time" id="date_time"
                                value="{{ old('date_time', $slot->date_time) }}"
                                class="block w-full mt-1 bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="duration" class="block text-sm font-medium text-white">Duration
                                (minutes)</label>
                            @if ($errors->has('duration'))
                                <span class="text-red-500 text-xs">{{ $errors->first('duration') }}</span>
                            @endif
                            <input type="number" name="duration" id="duration"
                                value="{{ old('duration', $slot->duration) }}"
                                class="block w-full mt-1 bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        </div>
                        @if ($company->typeIsGroup())
                            <div>
                                <label for="capacity" class="block text-sm font-medium text-white">Capacity
                                    (guests)</label>
                                @if ($errors->has('capacity'))
                                    <span class="text-red-500 text-xs">{{ $errors->first('capacity') }}</span>
                                @endif
                                <input type="number" name="capacity" id="capacity"
                                    value="{{ old('capacity', $slot->capacity) }}"
                                    class="block w-full mt-1 bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            </div>
                        @endif
                        <div>
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Add
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
