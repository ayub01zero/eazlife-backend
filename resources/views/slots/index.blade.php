<x-app-layout>
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li class="text-xs">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-100 grid grid-cols-2 gap-8">
            <div class="mt-6">
                <h1 class="text-xl font-semibold text-white">Slots Management for {{ $company->name }}</h1>
                @if ($company->typeIsGroup())
                    <p class="mt-2 text-sm text-gray-100">
                        Slots represent the available time intervals for reservations at your company. Each slot has a
                        specific time, duration, and capacity. The capacity indicates the maximum number of guests that
                        can
                        be
                        accommodated within a given slot. You can create, edit, or delete slots as needed. </p>
                @else
                    <p class="mt-2 text-sm text-gray-100">
                        Slots represent the available time intervals for reservations at your company. Each slot has a
                        specific time and duration. You can create, edit, or delete slots as needed. </p>
                @endif
            </div>
            <div class="mt-6">
                <button id="openCreateSlotsModalBtn" class="w-full px-4 py-2 bg-gray-600 text-white rounded-md">
                    Create slots for a whole week
                    <span class="text-xs text-gray-300 block">Create slots according to opening
                        hours</span>
                </button>
                <button id="openAddSlotModalBtn" class="w-full px-4 py-2 mt-4 bg-gray-600 text-white rounded-md">
                    Create single slot
                    <span class="text-xs text-gray-300 block">Create slot for a specific date and time</span>
                </button>
            </div>
        </div>

        <!-- The Create Slots Modal -->
        <div id="createSlotsModal"
            class="fixed top-0 left-0 w-full h-full flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
            <div class="bg-gray-900 p-6 rounded-md w-1/2">
                <span class="float-right cursor-pointer text-white" id="closeCreateSlotsModal">&times;</span>
                <h2 class="text-lg font-semibold text-white mb-2">Create Slots for Selected Week</h2>
                <!-- Form for Creating Slots -->
                <form action="{{ route('companies.slots.create_for_week', $company) }}" method="POST"
                    class="space-y-4">
                    @csrf
                    <div>
                        <label for="week" class="block text-sm font-medium text-white">Select Week</label>
                        @if ($errors->has('week'))
                            <span class="text-red-500 text-xs">{{ $errors->first('week') }}</span>
                        @endif
                        <input type="week" id="week" name="week"
                            value="{{ request()->input('week', now()->format('Y-\WW')) }}" required
                            class="block w-full mt-1 bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="duration" class="block text-sm font-medium text-white">Slot Duration
                            (minutes)</label>
                        @if ($errors->has('duration'))
                            <span class="text-red-500 text-xs">{{ $errors->first('duration') }}</span>
                        @endif
                        <input type="number" id="duration" name="duration" min="1" required
                            class="block w-full mt-1 bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    </div>
                    @if ($company->typeIsGroup())
                        <div>
                            <label for="capacity" class="block text-sm font-medium text-white">Capacity (guests)</label>
                            @if ($errors->has('capacity'))
                                <span class="text-red-500 text-xs">{{ $errors->first('capacity') }}</span>
                            @endif
                            <input type="number" id="capacity" name="capacity" min="1" required
                                class="block w-full mt-1 bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        </div>
                    @endif
                    <div class="flex items-center">
                        <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">Create
                            Slots</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- The Add Slot Modal -->
        <div id="addSlotModal"
            class="fixed top-0 left-0 w-full h-full flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
            <div class="bg-gray-900 p-6 rounded-md w-1/2">
                <span class="float-right cursor-pointer text-white" id="closeAddSlotModal">&times;</span>
                <h2 class="text-lg font-semibold text-white mb-2">Add One Slot</h2>
                <!-- Form for Adding a Slot -->
                <form action="{{ route('companies.slots.store', $company) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="date_time" class="block text-sm font-medium text-white">Date and Time</label>
                        @if ($errors->has('date_time'))
                            <span class="text-red-500 text-xs">{{ $errors->first('date_time') }}</span>
                        @endif
                        <input type="datetime-local" name="date_time" id="date_time"
                            class="block w-full mt-1 bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="duration" class="block text-sm font-medium text-white">Duration
                            (minutes)</label>
                        @if ($errors->has('duration'))
                            <span class="text-red-500 text-xs">{{ $errors->first('duration') }}</span>
                        @endif
                        <input type="number" name="duration" id="duration"
                            class="block w-full mt-1 bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    </div>
                    @if ($company->typeIsGroup())
                        <div>
                            <label for="capacity" class="block text-sm font-medium text-white">Capacity (guests)</label>
                            @if ($errors->has('capacity'))
                                <span class="text-red-500 text-xs">{{ $errors->first('capacity') }}</span>
                            @endif
                            <input type="number" name="capacity" id="capacity"
                                class="block w-full mt-1 bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        </div>
                    @endif
                    <div class="flex items-end">
                        <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">Add
                            Slot</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="p-6 text-gray-100 grid grid-cols-2 gap-8">
            @foreach ($slots->groupBy(function ($slot) {
        return \Carbon\Carbon::parse($slot->date_time)->format('Y-m-d');
    }) as $date => $dailySlots)
                <div class="mt-6">
                    <h2 class="mb-2 text-lg font-semibold text-white">
                        {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</h2>
                    <div class="flex flex-wrap items-center">
                        @foreach ($dailySlots as $timeSlot)
                            <div class="w-1/3 p-2">
                                <a href="{{ route('companies.slots.edit', [$company, $timeSlot]) }}"
                                    {{ $timeSlot->isReserved() ? 'disabled' : '' }}
                                    class="block w-full px-4 py-2 font-medium {{ $timeSlot->isReserved() ? 'bg-gray-800 text-gray-600' : 'text-white bg-gray-700 hover:bg-gray-600' }} rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                    {{ \Carbon\Carbon::parse($timeSlot->date_time)->format('H:i') }} -
                                    {{ $timeSlot->duration }} min
                                    @if ($company->typeIsGroup())
                                        ({{ $timeSlot->capacity }})
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get the modals, buttons, and close elements
            var createSlotsModal = document.getElementById('createSlotsModal');
            var openCreateSlotsModalBtn = document.getElementById('openCreateSlotsModalBtn');
            var closeCreateSlotsModal = document.getElementById('closeCreateSlotsModal');

            var addSlotModal = document.getElementById('addSlotModal');
            var openAddSlotModalBtn = document.getElementById('openAddSlotModalBtn');
            var closeAddSlotModal = document.getElementById('closeAddSlotModal');

            // Open the modals
            openCreateSlotsModalBtn.onclick = function() {
                createSlotsModal.classList.remove('hidden');
            }
            openAddSlotModalBtn.onclick = function() {
                addSlotModal.classList.remove('hidden');
            }

            // Close the modals
            closeCreateSlotsModal.onclick = function() {
                createSlotsModal.classList.add('hidden');
            }
            closeAddSlotModal.onclick = function() {
                addSlotModal.classList.add('hidden');
            }

            // Close if clicked anywhere outside the modal
            window.onclick = function(event) {
                if (event.target == createSlotsModal || event.target == addSlotModal) {
                    createSlotsModal.classList.add('hidden');
                    addSlotModal.classList.add('hidden');
                }
            }
        });
    </script>
</x-app-layout>
