<x-filament-panels::page>
    {{-- @if ($this->hasInfolist())
        {{ $this->infolist }}
    @else
        {{ $this->form }}
    @endif --}}
    {{-- <div class="col-span-3">
        <div class="bg-gray-800 text-xl overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-white">
                <h2>{{ __('Revenue') }}</h2>
                <ul>
                    @foreach ($record->revenue->groupBy('year') as $year => $revenues)
                        <li>
                            <strong>{{ $year }}:</strong>
                            ${{ $revenues->sum('sum') }}
                        </li>
                    @endforeach
                </ul>
                <p>
                    <strong>{{ __('Total') }}:</strong>
                    ${{ $record->revenue->sum('sum') }}
                </p>
            </div>
        </div>
    </div> --}}

    <div class="col-span-9">
        <div class="grid grid-cols-3">
            <select id="year_selector"
                class="col-span-1 mt-1 block text-white rounded-md bg-gray-800 py-2 px-3 shadow-sm focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm">
                @foreach ($record->revenue->groupBy('year') as $year => $revenues)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
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
                    var revenuesByWeek = {}; // Modified: Group revenues by week instead of month
                    var mostRecentYear = 0;

                    @foreach ($record->revenue as $revenue)
                        var year = {{ $revenue->year }};
                        var week = {{ $revenue->week }}; // Assuming 'week' is a property in your revenue model
                        var sum = {{ $revenue->sum }};

                        if (year > mostRecentYear) {
                            mostRecentYear = year;
                        }

                        if (!revenuesByWeek[year]) {
                            revenuesByWeek[year] = Array(52).fill(0); // Assuming 52 weeks in a year
                        }

                        revenuesByWeek[year][week - 1] = sum; // Adjust for JavaScript's 0-indexed weeks
                    @endforeach

                    var selectedYear = mostRecentYear;

                    var myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: Array.from({
                                length: 52
                            }, (_, i) => (i + 1).toString()), // Week numbers
                            datasets: [{
                                label: '{{ __('Revenue') }}',
                                data: revenuesByWeek[selectedYear],
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
                                    max: 1000,
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 200,
                                        callback: function(value, index, values) {
                                            return '$' + value;
                                        }
                                    }
                                }
                            }
                        }
                    });

                    document.getElementById('year_selector').addEventListener('change', function(e) {
                        selectedYear = e.target.value;
                        myChart.data.datasets[0].data = revenuesByWeek[selectedYear];
                        myChart.options.plugins.title.text = 'Revenue for year ' + selectedYear;
                        myChart.update();
                    });
                });
            </script>
        @endpush()
    </div>

    <div class="col-span-9">

        @if (count($relationManagers = $this->getRelationManagers()))
            <x-filament-panels::resources.relation-managers :active-manager="$this->activeRelationManager" :managers="$relationManagers" :owner-record="$record"
                :page-class="static::class" />
        @endif
    </div>

</x-filament-panels::page>
