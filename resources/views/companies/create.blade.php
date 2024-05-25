<x-app-layout>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-white">{{ __('Company information') }}</h3>
                    <p class="mt-1 text-sm text-gray-200">
                        {{ __('Fill out the information of the company.') }}</p>
                </div>
            </div>
            <div class="mt-5 md:col-span-2 md:mt-0">
                <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="overflow-hidden shadow sm:rounded-md">
                        <div class="bg-gray-800 px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12">
                                    <label for="name"
                                        class="text-sm font-medium text-gray-100">{{ __('Company name') }}</label>
                                    @if ($errors->has('name'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('name') }}</span>
                                    @endif
                                    <input type="text" name="name" id="name" autocomplete="name"
                                        value="{{ old('name') }}"
                                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>
                                <div class="col-span-6">
                                    <x-input-label for="company_type_id" :value="__('Company type')" />
                                    <x-select-input id="company_type_id" name="company_type_id" :options="$types->pluck('name', 'id')->toArray()"
                                        :selectedOptions="[old('company_type_id')]" />
                                    <x-input-error :messages="$errors->get('company_type_id')" class="mt-2" />
                                </div>
                                <div class="col-span-6">
                                    <x-input-label for="company_categories" :value="__('Categories')" />
                                    <x-select-input id="company_categories" name="company_categories[]"
                                        :options="$types[0]->categories->pluck('name', 'id')->toArray()" multiple size="5" />
                                    <x-input-error :messages="$errors->get('company_categories')" class="mt-2" />
                                </div>
                                <div class="col-span-12">
                                    <x-input-label for="company_fulfillment_types" :value="__('Fulfillment types')" />
                                    <div id="company_fulfillment_types"
                                        class="flex flex-wrap items-center space-x-8 text-gray-500">
                                    </div>
                                    <x-input-error :messages="$errors->get('company_fulfillment_types')" class="mt-2" />
                                </div>
                                @push('scripts')
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            initForm();
                                        });

                                        function initForm() {
                                            const companyTypeSelect = document.getElementById('company_type_id');
                                            const companyCategoriesSelect = document.getElementById('company_categories');
                                            const companyFulfillmentTypesDiv = document.getElementById('company_fulfillment_types');

                                            companyTypeSelect.addEventListener('change', function(event) {
                                                const selectedType = event.target.value;
                                                updateCompanyCategories(selectedType);
                                                updateCompanyFulfillmentTypes(selectedType);
                                            });

                                            function updateCompanyCategories(selectedType) {
                                                const types = @json($types);
                                                const selectedTypeObj = types.find(type => type.id == selectedType);

                                                if (selectedTypeObj) {
                                                    companyCategoriesSelect.innerHTML = '';

                                                    selectedTypeObj.categories.forEach(category => {
                                                        const option = document.createElement('option');
                                                        option.value = category.id;
                                                        option.text = category.name;
                                                        option.classList.add('select-multiple-option', 'py-1', 'px-3', '-ml-3', '-mr-3');

                                                        if ("{{ implode(',', old('company_categories') ?? []) }}".split(',').includes(category.id
                                                                .toString())) {
                                                            option.selected = true;
                                                            option.classList.add('bg-primary-500', 'text-white');
                                                        }

                                                        companyCategoriesSelect.add(option);
                                                    });
                                                } else {
                                                    companyCategoriesSelect.innerHTML = '';
                                                }
                                            }

                                            function updateCompanyFulfillmentTypes(selectedType) {
                                                const types = @json($types);
                                                const selectedTypeObj = types.find(type => type.id == selectedType);

                                                companyFulfillmentTypesDiv.innerHTML = '';

                                                if (selectedTypeObj) {
                                                    selectedTypeObj.fulfillment_types.forEach(fulfillmentType => {
                                                        const checkbox = document.createElement('input');
                                                        checkbox.type = 'checkbox';
                                                        checkbox.name = 'company_fulfillment_types[]';
                                                        checkbox.value = fulfillmentType.id;
                                                        checkbox.id = 'fulfillment_type_' + fulfillmentType.id;
                                                        checkbox.classList.add('h-4', 'w-4', 'text-primary-600', 'border-gray-300',
                                                            'rounded', 'bg-gray-700', 'shadow-sm', 'focus:ring-primary-500',
                                                            'dark:bg-gray-900', 'dark:border-gray-600', 'dark:text-gray-400');

                                                        // set the checked attribute if this fulfillment type was previously selected
                                                        if ("{{ implode(',', old('company_fulfillment_types') ?? []) }}".split(',').includes(
                                                                fulfillmentType.id.toString())) {
                                                            checkbox.checked = true;
                                                        }

                                                        const label = document.createElement('label');
                                                        label.htmlFor = 'fulfillment_type_' + fulfillmentType.id;
                                                        label.appendChild(document.createTextNode(fulfillmentType.name));

                                                        const div = document.createElement('div');
                                                        div.classList.add('flex', 'flex-wrap', 'items-center', 'space-x-2',
                                                            'text-gray-500', 'dark:text-gray-400');
                                                        div.appendChild(checkbox);
                                                        div.appendChild(label);

                                                        companyFulfillmentTypesDiv.appendChild(div);
                                                    });
                                                }
                                            }

                                            // call the updateCompanyCategories function on page load in case of editing an existing user or when old input values are being used
                                            updateCompanyCategories(companyTypeSelect.value);

                                            // call the updateCompanyFulfillmentTypes function on page load in case of editing an existing user or when old input values are being used
                                            updateCompanyFulfillmentTypes(companyTypeSelect.value);
                                        }
                                    </script>
                                @endpush
                                <div class="col-span-6">
                                    <label for="address"
                                        class="text-sm font-medium text-gray-100">{{ __('Address') }}</label>
                                    @if ($errors->has('address'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('address') }}</span>
                                    @endif
                                    <input type="text" name="address" id="address" autocomplete="address"
                                        value="{{ old('address') }}"
                                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>
                                <div class="col-span-6">
                                    <label for="country"
                                        class="text-sm font-medium text-gray-100">{{ __('Country') }}</label>
                                    @if ($errors->has('country'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('country') }}</span>
                                    @endif
                                    <select id="country" name="country" autocomplete="country"
                                        class="mt-1 block w-full text-white rounded-md border border-gray-300 bg-gray-800 py-2 px-3 shadow-sm focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm">
                                        <option>Iraq</option>
                                        <option>The Netherlands</option>
                                    </select>
                                </div>
                                <div class="col-span-6">
                                    <label for="city"
                                        class="text-sm font-medium text-gray-100">{{ __('City') }}</label>
                                    @if ($errors->has('city'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('city') }}</span>
                                    @endif
                                    <input type="text" name="city" id="city" autocomplete="city"
                                        value="{{ old('city') }}"
                                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>
                                <div class="col-span-4">
                                    <label for="state"
                                        class="text-sm font-medium text-gray-100">{{ __('State') }}</label>
                                    @if ($errors->has('state'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('state') }}</span>
                                    @endif
                                    <input type="text" name="state" id="state" autocomplete="state"
                                        value="{{ old('state') }}"
                                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>
                                <div class="col-span-2">
                                    <label for="zip-code"
                                        class="text-sm font-medium text-gray-100">{{ __('Zip code') }}</label>
                                    @if ($errors->has('zip_code'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('zip_code') }}</span>
                                    @endif
                                    <input type="text" name="zip_code" id="zip_code" autocomplete="zip-code"
                                        value="{{ old('zip_code') }}"
                                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>
                                <div class="col-span-2">
                                    <label for="radius"
                                        class="text-sm font-medium text-gray-100">{{ __('Delivery radius') }}</label>
                                    @if ($errors->has('radius'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('radius') }}</span>
                                    @endif
                                    <input type="number" name="radius" id="radius" autocomplete="radius"
                                        value="{{ old('radius', 10) }}"
                                        class="mt-1 w-2/3 bg-gray-700 text-white rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                    <span class="text-gray-500 inline sm:text-sm">km</span>
                                </div>
                                <div class="col-span-2">
                                    <label for="delivery_cost"
                                        class="text-sm font-medium text-gray-100">{{ __('Delivery cost') }}</label>
                                    @if ($errors->has('delivery_cost'))
                                        <span
                                            class="text-red-500 text-xs">{{ $errors->first('delivery_cost') }}</span>
                                    @endif
                                    <input type="number" name="delivery_cost" id="delivery_cost"
                                        autocomplete="delivery_cost" value="{{ old('delivery_cost', 0) }}"
                                        class="mt-1 w-2/3 bg-gray-700 text-white rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                    <span class="text-gray-500 inline sm:text-sm">$</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-700 px-4 py-3 text-right sm:px-6">
                            <button type="submit"
                                class="inline-flex justify-center rounded-md border border-transparent bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">{{ __('Save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
