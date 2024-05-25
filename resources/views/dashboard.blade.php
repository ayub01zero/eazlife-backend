<x-app-layout>
    @if (Session::has('error'))
        <div class="bg-red-500 text-white p-4 mb-4 rounded-md">
            <p>{{ Session::get('error') }}</p>
        </div>
    @endif
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-2">
            @if ($company->logo_path)
                <img src="{{ $company->logo_path }}" alt="logo" class="w-32 h-32">
            @else
                <div
                    class="bg-gray-600 w-32 h-32 overflow-hidden shadow-sm sm:rounded-2xl flex flex-col items-center justify-center">
                    <div class="h-6 w-8">
                        <svg fill="white" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 490.955 490.955"
                            xml:space="preserve">
                            <path id="XMLID_448_" d="M445.767,308.42l-53.374-76.49v-20.656v-11.366V97.241c0-6.669-2.604-12.94-7.318-17.645L312.787,7.301
 C308.073,2.588,301.796,0,295.149,0H77.597C54.161,0,35.103,19.066,35.103,42.494V425.68c0,23.427,19.059,42.494,42.494,42.494
 h159.307h39.714c1.902,2.54,3.915,5,6.232,7.205c10.033,9.593,23.547,15.576,38.501,15.576c26.935,0-1.247,0,34.363,0
 c14.936,0,28.483-5.982,38.517-15.576c11.693-11.159,17.348-25.825,17.348-40.29v-40.06c16.216-3.418,30.114-13.866,37.91-28.811
 C459.151,347.704,457.731,325.554,445.767,308.42z M170.095,414.872H87.422V53.302h175.681v46.752
 c0,16.655,13.547,30.209,30.209,30.209h46.76v66.377h-0.255v0.039c-17.685-0.415-35.529,7.285-46.934,23.46l-61.586,88.28
 c-11.965,17.134-13.387,39.284-3.722,57.799c7.795,14.945,21.692,25.393,37.91,28.811v19.842h-10.29H170.095z M410.316,345.771
 c-2.03,3.866-5.99,6.271-10.337,6.271h-0.016h-32.575v83.048c0,6.437-5.239,11.662-11.659,11.662h-0.017H321.35h-0.017
 c-6.423,0-11.662-5.225-11.662-11.662v-83.048h-32.574h-0.016c-4.346,0-8.308-2.405-10.336-6.271
 c-2.012-3.866-1.725-8.49,0.783-12.07l61.424-88.064c2.189-3.123,5.769-4.984,9.57-4.984h0.017c3.802,0,7.38,1.861,9.568,4.984
 l61.427,88.064C412.04,337.28,412.328,341.905,410.316,345.771z" />
                        </svg>
                    </div>
                    <a href="{{ route('companies.edit', ['company' => $company]) }}">
                        <span class="mt-2 text-white text-sm font-medium">{{ __('Upload your logo') }}</span>
                    </a>
                </div>
            @endif
        </div>
        <div class="col-span-10 flex items-center relative">
            {{-- <div class="absolute right-0 top-0">
                <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                    fill="currentColor">
                    <path
                        d="M12 22c1.1 0 2-.9 2-2H10c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4a1.5 1.5 0 00-3 0v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z" />
                </svg>
                <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full"></span>
            </div> --}}
            <div class="text-white">
                <h2 class="text-2xl">{{ __('Welcome') }}, {{ Auth::user()->name }}</h2>
                <form action="{{ route('update_status', ['company' => $company]) }}" method="POST">
                    @csrf
                    <label class="relative inline-flex items-center cursor-pointer mt-2">
                        <input type="checkbox" name="online" value="" class="sr-only peer"
                            data-company-online="{{ $company->online }}">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-500">
                        </div>
                        <span class="ml-3 font-medium text-white">{{ __('Online') }}</span>
                    </label>
                </form>
                @push('scripts')
                    <script>
                        window.addEventListener('DOMContentLoaded', function() {
                            const toggleSwitches = document.querySelectorAll('.relative input[type="checkbox"]');
                            toggleSwitches.forEach(function(toggleSwitch) {
                                const handle = toggleSwitch.nextElementSibling;
                                const companyOnline = toggleSwitch.dataset.companyOnline;
                                if (companyOnline === '1') {
                                    toggleSwitch.checked = true;
                                }
                                toggleSwitch.addEventListener('click', function() {
                                    const form = this.closest('form');
                                    const input = form.querySelector('input[name="online"]');
                                    if (this.checked) {
                                        input.value = '1';
                                    } else {
                                        input.value = '0';
                                    }
                                    form.submit();
                                });
                            });
                        });
                    </script>
                @endpush
            </div>
        </div>
        <div class="col-span-3">
            <div class="bg-gray-800 text-xl overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    @if (Auth::user()->id === $company->user_id)
                        <h2>{{ __('Revenue') }}</h2>
                        <ul>
                            @foreach ($company->revenue->groupBy('year') as $year => $revenues)
                                <li>
                                    <strong>{{ $year }}:</strong>
                                    ${{ $revenues->sum('sum') }}
                                </li>
                            @endforeach
                        </ul>
                        <p>
                            <strong>{{ __('Total') }}:</strong>
                            ${{ $company->revenue->sum('sum') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-span-3">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-xl text-white">
                    {{ __('Orders') }}
                </div>
                <ul class="divide-y-[0.5px] divide-gray-600 overflow-scroll max-h-[300px]">
                    @foreach ($company->orders as $order)
                        <li class="text-white flex flex-row py-1">
                            <a href="/orders/{{ $order->id }}">Order #{{ $order->id }}
                                (${{ $order->price }})
                                <span
                                    class="block text-xs">{{ $order->sent_at ? $order->sent_at : $order->pickup_at }}</span>
                            </a>
                            <div class="ml-auto text-sm"> <!-- Add ml-auto class to move the div to the right -->
                                @if ($order->sent_at && !$order->paid)
                                    <form action="/orders/pay" method="POST">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <button
                                            class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-md"
                                            type="submit">Pay</button>
                                    </form>
                                @elseif($order->sent_at === null && $order->pickup_at === null && $order->canceled_at === null)
                                    <div class="flex flex-row">
                                        <form action="/orders/send" method="POST">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <button
                                                class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-md"
                                                type="submit">Send</button>
                                        </form>
                                        <form action="/orders/cancel" method="POST">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <button class="text-red-800 font-bold py-2 px-4 rounded-md"
                                                type="submit">Cancel</button>
                                        </form>
                                    </div>
                                @else
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-span-3">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-xl text-white">
                    {{ __('Pickups') }}
                </div>
                <ul class="divide-y-[0.5px] divide-gray-600 overflow-scroll max-h-[300px]">
                    @foreach ($company->orders->whereNotNull('pickup_at') as $pickup)
                        <li class="text-white flex flex-row py-1">
                            <a href="/orders/{{ $pickup->id }}">Order #{{ $pickup->id }}
                                (${{ $pickup->price }})
                                <span class="block text-xs">{{ $pickup->pickup_at }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-span-3">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-xl text-white">
                    {{ __('Reservations') }}
                </div>
                <ul class="divide-y-[0.5px] divide-gray-600 overflow-scroll max-h-[300px]">
                    @foreach ($company->reservations() as $reservation)
                        <li class="text-white flex flex-row py-1">
                            <a href="/reservations/{{ $reservation->id }}">
                                ({{ $reservation->slot->date_time }})
                                <span class="block text-xs">{{ $reservation->name }}
                                    @if ($reservation->party_size)
                                        with {{ $reservation->party_size }} people
                                </span>
                    @endif
                    </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-span-6">
            @if (Auth::user()->id === $company->user_id)

                <div class="grid grid-cols-3">
                    <select id="year_selector"
                        class="col-span-1 mt-1 block text-white rounded-md bg-gray-800 py-2 px-3 shadow-sm focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm">
                        @foreach ($company->revenue->groupBy('year') as $year => $revenues)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                    {{-- <div class="col-span-2 text-white flex items-center justify-end">
                    <p>Total: $<span id="total_revenue"></span></p>
                </div> --}}
                </div>
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-white">
                        <canvas id="price_chart" class="h-[274px]"></canvas>
                    </div>
                </div>

                @push('scripts')
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var ctx = document.getElementById('price_chart');
                            var totalRevenue = document.getElementById('total_revenue');
                            var revenuesByYear = {}; // This will store revenues grouped by year and then by month.

                            var mostRecentYear = 0; // This will store the most recent year in your data.

                            @foreach ($company->revenue as $revenue)
                                var year = {{ $revenue->year }};
                                var month = {{ $revenue->month }} - 1; // Adjust for JavaScript's 0-indexed months
                                var sum = {{ $revenue->sum }};

                                if (year > mostRecentYear) {
                                    mostRecentYear = year; // Update mostRecentYear if this year is more recent
                                }

                                if (!revenuesByYear[year]) {
                                    revenuesByYear[year] = Array(12).fill(0); // Initialize an array with 12 months
                                }

                                revenuesByYear[year][month] = sum;
                            @endforeach

                            var selectedYear = mostRecentYear; // Default to the most recent year

                            var myChart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: [
                                        '{{ __('January') }}',
                                        '{{ __('February') }}',
                                        '{{ __('March') }}',
                                        '{{ __('April') }}',
                                        '{{ __('May') }}',
                                        '{{ __('June') }}',
                                        '{{ __('July') }}',
                                        '{{ __('August') }}',
                                        '{{ __('September') }}',
                                        '{{ __('October') }}',
                                        '{{ __('November') }}',
                                        '{{ __('December') }}'
                                    ],
                                    datasets: [{
                                        label: '{{ __('Revenue') }}',
                                        data: revenuesByYear[selectedYear],
                                        borderColor: 'rgba(255, 103, 0, 1)',
                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        title: {
                                            display: true,
                                            text: selectedYear !== 0 ? 'Revenue for year ' + selectedYear : 'No revenue yet'
                                        }
                                    },
                                    scales: {
                                        y: {
                                            min: 0,
                                            max: 100000,
                                            beginAtZero: true,
                                            ticks: {
                                                stepSize: 20000,
                                                callback: function(value, index, values) {
                                                    return '$' + value;
                                                }
                                            }
                                        }
                                    }
                                }
                            });

                            // totalRevenue.innerText = revenuesByYear[selectedYear].reduce((a, b) => a + b, 0);

                            document.getElementById('year_selector').addEventListener('change', function(e) {
                                selectedYear = e.target.value;
                                myChart.data.datasets[0].data = revenuesByYear[selectedYear];
                                myChart.options.plugins.title.text = 'Revenue for year ' + selectedYear;
                                myChart.update();

                                // totalRevenue.innerText = revenuesByYear[selectedYear].reduce((a, b) => a + b, 0);
                            });
                        });
                    </script>
                @endpush()
            @endif
        </div>
        <div class="col-span-5">
            <div class=" text-white">
                <form method="POST" action="{{ route('update_opening_times', ['company' => $company]) }}">
                    @csrf
                    <div class="flex justify-between h-7">
                        <div class="w-5">{{ __('M') }}</div>
                        <div class="w-40 text-center">
                            <div
                                class="{{ old('monday_open', isset($company->openingTimes[0]) && $company->openingTimes[0]->opening_time) || old('monday_close', isset($company->openingTimes[0]) && $company->openingTimes[0]->closing_time) ? '' : 'hidden' }} flex gap-4 start-end-times">
                                <x-text-input id="monday_open" class="text-center p-0" name="monday_open"
                                    :value="old(
                                        'monday_open',
                                        isset($company->openingTimes[0]) ? $company->openingTimes[0]->opening_time : '',
                                    )" placeholder="16:00" />
                                <x-input-error :messages="$errors->get('monday_open')" class="mt-2" />
                                <x-text-input id="monday_close" class="text-center p-0" name="monday_close"
                                    :value="old(
                                        'monday_close',
                                        isset($company->openingTimes[0]) ? $company->openingTimes[0]->closing_time : '',
                                    )" placeholder="23:00" />
                                <x-input-error :messages="$errors->get('monday_close')" class="mt-2" />
                            </div>
                            <button
                                class="{{ (isset($company->openingTimes[0]) && $company->openingTimes[0]->opening_time !== null && $company->openingTimes[0]->closing_time !== null) || (isset($company->openingTimes[0]) && $company->openingTimes[0]->opening_time == null && $company->openingTimes[0]->closing_time == null) ? 'hidden' : '' }} text-primary-500 text-sm px-2"
                                onclick="addTimes(this)">{{ __('Add times') }} +</button>
                            <span
                                class="{{ isset($company->openingTimes[0]) && $company->openingTimes[0]->opening_time == null && $company->openingTimes[0]->closing_time == null ? '' : 'hidden' }} text-primary-500 text-sm px-2 py-1">{{ __('Closed') }}</span>
                        </div>
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" id="monday_closed" name="monday_closed" value="1"
                                    {{ isset($company->openingTimes[0]) && ($company->openingTimes[0]->opening_time == null && $company->openingTimes[0]->closing_time == null) ? 'checked' : '' }}
                                    class="form-checkbox h-4 w-4 rounded bg-gray-800 text-primary-500 focus:ring-primary-500"
                                    onchange="toggleClosed(this)">

                                <label for="monday_closed" class="text-sm ml-1">{{ __('Closed') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between h-7">
                        <div class="w-5">{{ __('T') }}</div>
                        <div class="w-40 text-center">
                            <div
                                class="{{ old('tuesday_open', isset($company->openingTimes[1]) && $company->openingTimes[1]->opening_time) || old('tuesday_close', isset($company->openingTimes[1]) && $company->openingTimes[1]->closing_time) ? '' : 'hidden' }} flex gap-4 start-end-times">
                                <x-text-input id="tuesday_open" class="text-center p-0" name="tuesday_open"
                                    :value="old(
                                        'tuesday_open',
                                        isset($company->openingTimes[1]) ? $company->openingTimes[1]->opening_time : '',
                                    )" placeholder="16:00" />
                                <x-input-error :messages="$errors->get('tuesday_open')" class="mt-2" />
                                <x-text-input id="tuesday_close" class="text-center p-0" name="tuesday_close"
                                    :value="old(
                                        'tuesday_close',
                                        isset($company->openingTimes[1]) ? $company->openingTimes[1]->closing_time : '',
                                    )" placeholder="23:00" />
                                <x-input-error :messages="$errors->get('tuesday_close')" class="mt-2" />
                            </div>
                            <button
                                class="{{ (isset($company->openingTimes[1]) && $company->openingTimes[1]->opening_time !== null && $company->openingTimes[1]->closing_time !== null) || (isset($company->openingTimes[1]) && $company->openingTimes[1]->opening_time == null && $company->openingTimes[1]->closing_time == null) ? 'hidden' : '' }} text-primary-500 text-sm px-2"
                                onclick="addTimes(this)">{{ __('Add times') }} +</button>
                            <span
                                class="{{ isset($company->openingTimes[1]) && $company->openingTimes[1]->opening_time == null && $company->openingTimes[1]->closing_time == null ? '' : 'hidden' }} text-primary-500 text-sm px-2 py-1">{{ __('Closed') }}</span>
                        </div>
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" id="tuesday_closed" name="tuesday_closed" value="1"
                                    {{ isset($company->openingTimes[1]) && ($company->openingTimes[1]->opening_time == null && $company->openingTimes[1]->closing_time == null) ? 'checked' : '' }}
                                    class="form-checkbox h-4 w-4 rounded bg-gray-800 text-primary-500 focus:ring-primary-500"
                                    onchange="toggleClosed(this)">

                                <label for="tuesday_closed" class="text-sm ml-1">{{ __('Closed') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between h-7">
                        <div class="w-5">{{ __('W') }}</div>
                        <div class="w-40 text-center">
                            <div
                                class="{{ old('wednesday_open', isset($company->openingTimes[2]) && $company->openingTimes[2]->opening_time) || old('wednesday_close', isset($company->openingTimes[2]) && $company->openingTimes[2]->closing_time) ? '' : 'hidden' }} flex gap-4 start-end-times">
                                <x-text-input id="wednesday_open" class="text-center p-0" name="wednesday_open"
                                    :value="old(
                                        'wednesday_open',
                                        isset($company->openingTimes[2]) ? $company->openingTimes[2]->opening_time : '',
                                    )" placeholder="16:00" />
                                <x-input-error :messages="$errors->get('wednesday_open')" class="mt-2" />
                                <x-text-input id="wednesday_close" class="text-center p-0" name="wednesday_close"
                                    :value="old(
                                        'wednesday_close',
                                        isset($company->openingTimes[2]) ? $company->openingTimes[2]->closing_time : '',
                                    )" placeholder="23:00" />
                                <x-input-error :messages="$errors->get('wednesday_close')" class="mt-2" />
                            </div>
                            <button
                                class="{{ (isset($company->openingTimes[2]) && $company->openingTimes[2]->opening_time !== null && $company->openingTimes[2]->closing_time !== null) || (isset($company->openingTimes[2]) && $company->openingTimes[2]->opening_time == null && $company->openingTimes[2]->closing_time == null) ? 'hidden' : '' }} text-primary-500 text-sm px-2"
                                onclick="addTimes(this)">{{ __('Add times') }} +</button>
                            <span
                                class="{{ isset($company->openingTimes[2]) && $company->openingTimes[2]->opening_time == null && $company->openingTimes[2]->closing_time == null ? '' : 'hidden' }} text-primary-500 text-sm px-2 py-1">{{ __('Closed') }}</span>
                        </div>
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" id="wednesday_closed" name="wednesday_closed" value="1"
                                    {{ isset($company->openingTimes[2]) && ($company->openingTimes[2]->opening_time == null && $company->openingTimes[2]->closing_time == null) ? 'checked' : '' }}
                                    class="form-checkbox h-4 w-4 rounded bg-gray-800 text-primary-500 focus:ring-primary-500"
                                    onchange="toggleClosed(this)">

                                <label for="wednesday_closed" class="text-sm ml-1">{{ __('Closed') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between h-7">
                        <div class="w-5">{{ __('T') }}</div>
                        <div class="w-40 text-center">
                            <div
                                class="{{ old('thursday_open', isset($company->openingTimes[3]) && $company->openingTimes[3]->opening_time) || old('thursday_close', isset($company->openingTimes[3]) && $company->openingTimes[3]->closing_time) ? '' : 'hidden' }} flex gap-4 start-end-times">
                                <x-text-input id="thursday_open" class="text-center p-0" name="thursday_open"
                                    :value="old(
                                        'thursday_open',
                                        isset($company->openingTimes[3]) ? $company->openingTimes[3]->opening_time : '',
                                    )" placeholder="16:00" />
                                <x-input-error :messages="$errors->get('thursday_open')" class="mt-2" />
                                <x-text-input id="thursday_close" class="text-center p-0" name="thursday_close"
                                    :value="old(
                                        'thursday_close',
                                        isset($company->openingTimes[3]) ? $company->openingTimes[3]->closing_time : '',
                                    )" placeholder="23:00" />
                                <x-input-error :messages="$errors->get('thursday_close')" class="mt-2" />
                            </div>
                            <button
                                class="{{ (isset($company->openingTimes[3]) && $company->openingTimes[3]->opening_time !== null && $company->openingTimes[3]->closing_time !== null) || (isset($company->openingTimes[3]) && $company->openingTimes[3]->opening_time == null && $company->openingTimes[3]->closing_time == null) ? 'hidden' : '' }} text-primary-500 text-sm px-2"
                                onclick="addTimes(this)">{{ __('Add times') }} +</button>
                            <span
                                class="{{ isset($company->openingTimes[3]) && $company->openingTimes[3]->opening_time == null && $company->openingTimes[3]->closing_time == null ? '' : 'hidden' }} text-primary-500 text-sm px-2 py-1">{{ __('Closed') }}</span>
                        </div>
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" id="thursday_closed" name="thursday_closed" value="1"
                                    {{ isset($company->openingTimes[3]) && ($company->openingTimes[3]->opening_time == null && $company->openingTimes[3]->closing_time == null) ? 'checked' : '' }}
                                    class="form-checkbox h-4 w-4 rounded bg-gray-800 text-primary-500 focus:ring-primary-500"
                                    onchange="toggleClosed(this)">

                                <label for="thursday_closed" class="text-sm ml-1">{{ __('Closed') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between h-7">
                        <div class="w-5">{{ __('F') }}</div>
                        <div class="w-40 text-center">
                            <div
                                class="{{ old('friday_open', isset($company->openingTimes[4]) && $company->openingTimes[4]->opening_time) || old('friday_close', isset($company->openingTimes[4]) && $company->openingTimes[4]->closing_time) ? '' : 'hidden' }} flex gap-4 start-end-times">
                                <x-text-input id="friday_open" class="text-center p-0" name="friday_open"
                                    :value="old(
                                        'friday_open',
                                        isset($company->openingTimes[4]) ? $company->openingTimes[4]->opening_time : '',
                                    )" placeholder="16:00" />
                                <x-input-error :messages="$errors->get('friday_open')" class="mt-2" />
                                <x-text-input id="friday_close" class="text-center p-0" name="friday_close"
                                    :value="old(
                                        'friday_close',
                                        isset($company->openingTimes[4]) ? $company->openingTimes[4]->closing_time : '',
                                    )" placeholder="23:00" />
                                <x-input-error :messages="$errors->get('friday_close')" class="mt-2" />
                            </div>
                            <button
                                class="{{ (isset($company->openingTimes[4]) && $company->openingTimes[4]->opening_time !== null && $company->openingTimes[4]->closing_time !== null) || (isset($company->openingTimes[4]) && $company->openingTimes[4]->opening_time == null && $company->openingTimes[4]->closing_time == null) ? 'hidden' : '' }} text-primary-500 text-sm px-2"
                                onclick="addTimes(this)">{{ __('Add times') }} +</button>
                            <span
                                class="{{ isset($company->openingTimes[4]) && $company->openingTimes[4]->opening_time == null && $company->openingTimes[4]->closing_time == null ? '' : 'hidden' }} text-primary-500 text-sm px-2 py-1">{{ __('Closed') }}</span>
                        </div>
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" id="friday_closed" name="friday_closed" value="1"
                                    {{ isset($company->openingTimes[4]) && ($company->openingTimes[4]->opening_time == null && $company->openingTimes[4]->closing_time == null) ? 'checked' : '' }}
                                    class="form-checkbox h-4 w-4 rounded bg-gray-800 text-primary-500 focus:ring-primary-500"
                                    onchange="toggleClosed(this)">

                                <label for="friday_closed" class="text-sm ml-1">{{ __('Closed') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between h-7">
                        <div class="w-5">{{ __('S') }}</div>
                        <div class="w-40 text-center">
                            <div
                                class="{{ old('saturday_open', isset($company->openingTimes[5]) && $company->openingTimes[5]->opening_time) || old('saturday_close', isset($company->openingTimes[5]) && $company->openingTimes[5]->closing_time) ? '' : 'hidden' }} flex gap-4 start-end-times">
                                <x-text-input id="saturday_open" class="text-center p-0" name="saturday_open"
                                    :value="old(
                                        'saturday_open',
                                        isset($company->openingTimes[5]) ? $company->openingTimes[5]->opening_time : '',
                                    )" placeholder="16:00" />
                                <x-input-error :messages="$errors->get('saturday_open')" class="mt-2" />
                                <x-text-input id="saturday_close" class="text-center p-0" name="saturday_close"
                                    :value="old(
                                        'saturday_close',
                                        isset($company->openingTimes[5]) ? $company->openingTimes[5]->closing_time : '',
                                    )" placeholder="23:00" />
                                <x-input-error :messages="$errors->get('saturday_close')" class="mt-2" />
                            </div>
                            <button
                                class="{{ (isset($company->openingTimes[5]) && $company->openingTimes[5]->opening_time !== null && $company->openingTimes[5]->closing_time !== null) || (isset($company->openingTimes[5]) && $company->openingTimes[5]->opening_time == null && $company->openingTimes[5]->closing_time == null) ? 'hidden' : '' }} text-primary-500 text-sm px-2"
                                onclick="addTimes(this)">{{ __('Add times') }} +</button>
                            <span
                                class="{{ isset($company->openingTimes[5]) && $company->openingTimes[5]->opening_time == null && $company->openingTimes[5]->closing_time == null ? '' : 'hidden' }} text-primary-500 text-sm px-2 py-1">{{ __('Closed') }}</span>
                        </div>
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" id="saturday_closed" name="saturday_closed" value="1"
                                    {{ isset($company->openingTimes[5]) && ($company->openingTimes[5]->opening_time == null && $company->openingTimes[5]->closing_time == null) ? 'checked' : '' }}
                                    class="form-checkbox h-4 w-4 rounded bg-gray-800 text-primary-500 focus:ring-primary-500"
                                    onchange="toggleClosed(this)">

                                <label for="saturday_closed" class="text-sm ml-1">{{ __('Closed') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between h-7">
                        <div class="w-5">{{ __('S') }}</div>
                        <div class="w-40 text-center">
                            <div
                                class="{{ old('sunday_open', isset($company->openingTimes[6]) && $company->openingTimes[6]->opening_time) || old('sunday_close', isset($company->openingTimes[6]) && $company->openingTimes[6]->closing_time) ? '' : 'hidden' }} flex gap-4 start-end-times">
                                <x-text-input id="sunday_open" class="text-center p-0" name="sunday_open"
                                    :value="old(
                                        'sunday_open',
                                        isset($company->openingTimes[6]) ? $company->openingTimes[6]->opening_time : '',
                                    )" placeholder="16:00" />
                                <x-input-error :messages="$errors->get('sunday_open')" class="mt-2" />
                                <x-text-input id="sunday_close" class="text-center p-0" name="sunday_close"
                                    :value="old(
                                        'sunday_close',
                                        isset($company->openingTimes[6]) ? $company->openingTimes[6]->closing_time : '',
                                    )" placeholder="23:00" />
                                <x-input-error :messages="$errors->get('sunday_close')" class="mt-2" />
                            </div>
                            <button
                                class="{{ (isset($company->openingTimes[6]) && $company->openingTimes[6]->opening_time !== null && $company->openingTimes[6]->closing_time !== null) || (isset($company->openingTimes[6]) && $company->openingTimes[6]->opening_time == null && $company->openingTimes[6]->closing_time == null) ? 'hidden' : '' }} text-primary-500 text-sm px-2"
                                onclick="addTimes(this)">{{ __('Add times') }} +</button>
                            <span
                                class="{{ isset($company->openingTimes[6]) && $company->openingTimes[6]->opening_time == null && $company->openingTimes[6]->closing_time == null ? '' : 'hidden' }} text-primary-500 text-sm px-2 py-1">{{ __('Closed') }}</span>
                        </div>
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" id="sunday_closed" name="sunday_closed" value="1"
                                    {{ isset($company->openingTimes[6]) && ($company->openingTimes[6]->opening_time == null && $company->openingTimes[6]->closing_time == null) ? 'checked' : '' }}
                                    class="form-checkbox h-4 w-4 rounded bg-gray-800 text-primary-500 focus:ring-primary-500"
                                    onchange="toggleClosed(this)">

                                <label for="sunday_closed" class="text-sm ml-1">{{ __('Closed') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="w-full flex justify-between mt-4">
                        <span
                            class="text-gray-400 text-xs">{{ __('Are you opened 24 hours? Fill in 0:00 to 0:00.') }}</span>
                        <input type="submit" value="{{ __('Save hours') }}"
                            class="bg-primary-500 text-white text-sm px-2 py-1 rounded-md cursor-pointer">
                    </div>
                </form>
            </div>
        </div>
        @push('scripts')
            <script>
                function addTimes(button) {
                    event.preventDefault();
                    const startEndTimes = button.parentElement.querySelector('.start-end-times');
                    startEndTimes.classList.remove('hidden');
                    button.classList.add('hidden');
                }

                function toggleClosed(checkbox) {
                    console.log(checkbox);
                    const startEndTimes = checkbox.parentElement.parentElement.parentElement.querySelector('div .start-end-times');
                    const startTime = checkbox.parentElement.parentElement.parentElement.querySelector(
                        'div .start-end-times input:first-child');
                    const endTime = checkbox.parentElement.parentElement.parentElement.querySelector(
                        'div .start-end-times input:last-child');
                    const button = checkbox.parentElement.parentElement.parentElement.querySelector('div button');
                    const text = checkbox.parentElement.parentElement.parentElement.querySelector('div span');
                    if (checkbox.checked) {
                        startEndTimes.classList.add('hidden');
                        startTime.value = '';
                        endTime.value = '';
                        button.classList.add('hidden');
                        text.classList.remove('hidden');
                    } else {
                        //check if the inputs are filled in
                        if (startTime.value && endTime.value) {
                            startEndTimes.classList.remove('hidden');
                            button.classList.add('hidden');
                            text.classList.add('hidden');
                        } else {
                            startEndTimes.classList.add('hidden');
                            button.classList.remove('hidden');
                            text.classList.add('hidden');
                        }
                        button.classList.remove('hidden');
                        text.classList.add('hidden');
                    }
                }
            </script>
        @endpush()

        <div class="col-span-12 bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-white">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-xl font-semibold text-white">Companies</h1>
                        <p class="mt-2 text-sm text-gray-100">All companies.</p>
                    </div>
                    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                        @if (Auth::user()->id === $company->user_id)
                            <a href="{{ route('companies.create') }}"
                                class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto">New
                                company</a>
                        @endif
                    </div>
                </div>
                <div class="mt-8 flex flex-col">
                    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-700">
                                    <thead class="bg-gray-500/5">
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th scope="col"
                                                class="py-3.5 pl-3 pr-3 text-left text-sm font-semibold text-white">
                                                Name</th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                                Address</th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                                <span>Edit</span>
                                                <span class="sr-only">Edit</span>
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                                <span>Products</span>
                                                <span class="sr-only">Products</span>
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                                <span>Reservations</span>
                                                <span class="sr-only">Reservations</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700 bg-gray-500/10">
                                        @foreach ($companies as $company)
                                            @if (
                                                !$company->inventory_path &&
                                                    (in_array('pickup', $company->fulfillmentTypes) || in_array('delivery', $company->fulfillmentTypes)))
                                                <div class="fixed z-10 inset-0 overflow-y-auto bg-gray-900 bg-opacity-50"
                                                    x-show="showModal" x-cloak>
                                                    <div class="flex items-center justify-center min-h-screen">
                                                        <div class="bg-gray-700 rounded-lg shadow-xl p-10">
                                                            <div class="flex flex-col items-center">
                                                                <h2 class="text-2xl font-bold mb-4">Upload Inventory
                                                                </h2>
                                                                <p class="max-w-sm text-center mb-4">Before you can
                                                                    proceed, we kindly ask you to upload a PDF of
                                                                    {{ $company->name }}'s menu or catalog</p>
                                                                <form
                                                                    action="{{ route('upload_inventory', ['company' => $company]) }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @if ($errors->any())
                                                                        <div
                                                                            class="bg-red-500 text-white p-4 mb-4 rounded-md">
                                                                            <ul>
                                                                                @foreach ($errors->all() as $error)
                                                                                    <li>{{ $error }}</li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                    @endif
                                                                    <div class="mb-4">
                                                                        <input type="file" name="inventory_file"
                                                                            accept=".pdf"
                                                                            class="border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 rounded-md p-2 w-full">
                                                                    </div>
                                                                    <div class="flex justify-end">
                                                                        <button type="submit"
                                                                            class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-md">
                                                                            Upload
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @push('scripts')
                                                    <script>
                                                        const showModal = @json(!$company->inventory_path);

                                                        if (showModal) {
                                                            const modal = document.querySelector('.modal');
                                                            const backdrop = document.querySelector('.backdrop');

                                                            modal.classList.add('opacity-100', 'pointer-events-auto');
                                                            backdrop.classList.add('opacity-50');
                                                        }

                                                        function hideModal() {
                                                            const modal = document.querySelector('.modal');
                                                            const backdrop = document.querySelector('.backdrop');

                                                            modal.classList.remove('opacity-100', 'pointer-events-auto');
                                                            backdrop.classList.remove('opacity-50');
                                                        }
                                                    </script>
                                                @endpush
                                            @endif
                                            <tr>
                                                <td class="whitespace-nowrap px-3 py-4">
                                                    <a href="{{ route('dashboard', ['company' => $company]) }}"
                                                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto">Dashboard</a>
                                                </td>
                                                <td>
                                                    @if ($company->logo_path)
                                                        <img class="h-10 w-10 object-contain"
                                                            src="{{ $company->logo_path }}" alt="">
                                                    @endif
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                                    {{ $company->name }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                                    {{ $company->address }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-white">

                                                    <a href="{{ route('companies.edit', ['company' => $company]) }}"
                                                        class="text-primary-600 hover:text-primary-900">Edit company
                                                        <span class="sr-only"> Edit company</span></a>
                                                </td>
                                                @if (in_array('pickup', $company->fulfillmentTypes) || in_array('delivery', $company->fulfillmentTypes))
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-white">

                                                        <a href="{{ route('companies.products.index', ['company' => $company]) }}"
                                                            class="text-primary-600 hover:text-primary-900">Products
                                                            <span class="sr-only">Products</span></a>
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
                                                @if (in_array('reservation', $company->fulfillmentTypes))
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-white">

                                                        <a href="{{ route('companies.slots.index', ['company' => $company]) }}"
                                                            class="text-primary-600 hover:text-primary-900">Slots
                                                            <span class="sr-only">Slots</span></a>
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
