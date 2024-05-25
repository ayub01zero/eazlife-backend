<x-app-layout>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-3 ">
                <div class="relative">
                    <form action="{{ route('update_banner', ['company' => $company]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="mt-5 relative">
                            <label for="banner" class="text-sm font-medium text-gray-100">{{ __('Banner') }}</label>
                            <img id="banner_preview" src="{{ $company->banner_path }}" alt=""
                                class="hidden rounded border-dashed border-2 border-gray-400 h-[500px] w-full object-cover object-center">
                            @if ($errors->has('banner'))
                                <span class="text-red-500 text-xs">{{ $errors->first('banner') }}</span>
                            @endif
                            <div class="mt-1 block w-full">
                                @if (!$company->banner_path)
                                    <div id="banner_placeholder"
                                        class="border-dashed border-2 border-gray-400 px-4 cursor-pointer rounded flex justify-center items-center">
                                        <input type="file" name="banner" id="banner"
                                            accept="image/png, image/jpeg"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                            onchange="previewBanner(event)">
                                        <span class="text-gray-400 font-bold text-xl">Click or drag to upload
                                            banner</span>
                                    </div>
                                @endif
                            </div>
                            <button id="banner_submit" type="submit"
                                class="hidden absolute bottom-1 right-1 mt-2 rounded-md border border-transparent bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">Save
                                Banner</button>
                        </div>
                    </form>
                    @if ($company->banner_path)
                        <form action="{{ route('delete_banner', ['company' => $company]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button id="banner_delete" type="submit"
                                class="absolute top-7 right-1 rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="h-6 w-6 inline-block">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m5-6a1 1 0 00-1-1h-4a1 1 0 00-1 1v1a1 1 0 001 1h4a1 1 0 001-1V7z">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        if (document.getElementById('banner_preview').getAttribute('src')) {
                            document.getElementById('banner_preview').classList.remove('hidden');
                            document.getElementById('banner_placeholder').classList.add('hidden');
                            document.getElementById('banner_submit').classList.remove('hidden');
                        }
                    });

                    function previewBanner(event) {
                        const bannerPreview = document.getElementById('banner_preview');
                        const bannerPlaceholder = document.getElementById('banner_placeholder');
                        const bannerSubmit = document.getElementById('banner_submit');

                        if (event.target.files && event.target.files[0]) {
                            bannerPreview.classList.remove('hidden');
                            bannerPlaceholder.classList.add('hidden');
                            bannerSubmit.classList.remove('hidden');
                            bannerPreview.src = URL.createObjectURL(event.target.files[0]);
                        } else {
                            bannerPreview.classList.add('hidden');
                            bannerPlaceholder.classList.remove('hidden');
                            bannerSubmit.classList.add('hidden');
                            bannerPreview.src = "";
                        }
                    }
                </script>
            @endpush
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-white">{{ __('Company information') }}</h3>
                    <p class="mt-1 text-sm text-gray-200">
                        {{ __('Fill out the information of the company.') }}</p>
                    </p>
                </div>
                <div class="relative">
                    <form action="{{ route('update_logo', ['company' => $company]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="mt-5 relative">
                            <label for="logo" class="text-sm font-medium text-gray-100">{{ __('Logo') }}</label>
                            <img id="logo_preview" src="{{$company->logo_path}}" alt="hello no logo"
                                class="hidden rounded border-dashed border-2 border-gray-400 w-full object-cover object-center">
                            @if ($errors->has('logo'))
                                <span class="text-red-500 text-xs">{{ $errors->first('logo') }}</span>
                            @endif
                            <div class="mt-1 w-full flex content-end">
                                @if (!$company->logo_path)
                                    <div id="logo_placeholder"
                                        class="border-dashed border-2 border-gray-400 w-full px-4 cursor-pointer rounded flex justify-center items-center">
                                        <input type="file" name="logo" id="logo"
                                            accept="image/png, image/jpeg"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                            onchange="previewLogo(event)">
                                        <span class="text-gray-400 font-bold text-xl">Click or drag to upload
                                            logo</span>
                                    </div>
                                @endif
                            </div>
                            <button id="logo_submit" type="submit"
                                class="hidden absolute bottom-2 right-1 mt-2 rounded-md border border-transparent bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">Save
                                Logo</button>
                        </div>
                    </form>
                    @if ($company->logo_path)
                        <form action="{{ route('delete_logo', ['company' => $company]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button id="logo_delete" type="submit"
                                class="absolute top-7 right-1 rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="h-6 w-6 inline-block">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m5-6a1 1 0 00-1-1h-4a1 1 0 00-1 1v1a1 1 0 001 1h4a1 1 0 001-1V7z">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>

            </div>
            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        if (document.getElementById('logo_preview').getAttribute('src')) {
                            document.getElementById('logo_preview').classList.remove('hidden');
                            document.getElementById('logo_placeholder').classList.add('hidden');
                            document.getElementById('logo_submit').classList.remove('hidden');
                        }
                    });

                    function previewLogo(event) {
                        const logoPreview = document.getElementById('logo_preview');
                        const logoPlaceholder = document.getElementById('logo_placeholder');
                        const logoSubmit = document.getElementById('logo_submit');

                        if (event.target.files && event.target.files[0]) {
                            logoPreview.classList.remove('hidden');
                            logoPlaceholder.classList.add('hidden');
                            logoSubmit.classList.remove('hidden');
                            logoPreview.src = URL.createObjectURL(event.target.files[0]);
                        } else {
                            logoPreview.classList.add('hidden');
                            logoPlaceholder.classList.remove('hidden');
                            logoSubmit.classList.add('hidden');
                            logoPreview.src = "";
                        }
                    }
                </script>
            @endpush
            <div class="mt-5 md:col-span-2 md:mt-0">
                <form action="{{ route('companies.update', ['company' => $company]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="overflow-hidden shadow sm:rounded-md">
                        <div class="bg-gray-800 px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12">
                                    <label for="name"
                                        class="text-sm font-medium text-gray-100">{{ __('Company name') }}</label>
                                    @if ($errors->has('name'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('name') }}</span>
                                    @endif
                                    @if ($duplicatedCompany && $duplicatedCompany->name != $company->name)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending Approval
                                        </span>
                                    @endif
                                    <input type="text" name="name" id="name" autocomplete="name"
                                        value="{{ $duplicatedCompany->name ?? old('name', $company->name) }}"
                                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>
                                {{-- <div class="col-span-6">
                                    <x-input-label for="company_type_id" :value="__('Company type')" />
                                    <x-select-input id="company_type_id" name="company_type_id" :options="$types->pluck('name', 'id')->toArray()"
                                        :selectedOptions="[old('company_type_id', $company->company_type_id)]" />
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
                                </div> --}}


                                <div class="col-span-6">
                                    <label for="phone"
                                        class="text-sm font-medium text-gray-100">{{ __('Phone') }}</label>
                                    @if ($errors->has('phone'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('phone') }}</span>
                                    @endif
                                    @if ($duplicatedCompany && $duplicatedCompany->phone != $company->phone)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending Approval
                                        </span>
                                    @endif
                                    <input type="text" name="phone" id="phone" autocomplete="phone"
                                        value="{{ $duplicatedCompany->phone ?? old('phone', $company->phone) }}"
                                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>
                                <div class="col-span-6">
                                    <label for="email"
                                        class="text-sm font-medium text-gray-100">{{ __('Email') }}</label>
                                    @if ($errors->has('email'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('email') }}</span>
                                    @endif
                                    @if ($duplicatedCompany && $duplicatedCompany->email != $company->email)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending Approval
                                        </span>
                                    @endif
                                    <input type="text" name="email" id="email" autocomplete="email"
                                        value="{{ $duplicatedCompany->email ?? old('email', $company->email) }}"
                                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>

                                <div class="col-span-6">
                                    <label for="address"
                                        class="text-sm font-medium text-gray-100">{{ __('Address') }}</label>
                                    @if ($errors->has('address'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('address') }}</span>
                                    @endif
                                    @if ($duplicatedCompany && $duplicatedCompany->address != $company->address)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending Approval
                                        </span>
                                    @endif
                                    <input type="text" name="address" id="address" autocomplete="address"
                                        value="{{ $duplicatedCompany->address ?? old('address', $company->address) }}"
                                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>

                                <div class="col-span-6">
                                    <label for="country"
                                        class="text-sm font-medium text-gray-100">{{ __('Country') }}</label>
                                    @if ($errors->has('country'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('country') }}</span>
                                    @endif
                                    @if ($duplicatedCompany && $duplicatedCompany->country != $company->country)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending Approval
                                        </span>
                                    @endif
                                    <select id="country" name="country" autocomplete="country"
                                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border border-gray-600 bg-gray-800 py-2 px-3 shadow-sm focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm">
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
                                    @if ($duplicatedCompany && $duplicatedCompany->city != $company->city)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending Approval
                                        </span>
                                    @endif
                                    <input type="text" name="city" id="city" autocomplete="city"
                                        value="{{ $duplicatedCompany->city ?? old('city', $company->city) }}"
                                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>

                                <div class="col-span-4">
                                    <label for="state"
                                        class="text-sm font-medium text-gray-100">{{ __('State') }}</label>
                                    @if ($errors->has('state'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('state') }}</span>
                                    @endif
                                    @if ($duplicatedCompany && $duplicatedCompany->state != $company->state)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending Approval
                                        </span>
                                    @endif
                                    <input type="text" name="state" id="state" autocomplete="state"
                                        value="{{ $duplicatedCompany->state ?? old('state', $company->state) }}"
                                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>
                                <div class="col-span-2">
                                    <label for="zip-code"
                                        class="text-sm font-medium text-gray-100">{{ __('Zip code') }}</label>
                                    @if ($errors->has('zip_code'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('zip_code') }}</span>
                                    @endif
                                    @if ($duplicatedCompany && $duplicatedCompany->zip_code != $company->zip_code)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending Approval
                                        </span>
                                    @endif
                                    <input type="text" name="zip_code" id="zip_code" autocomplete="zip-code"
                                        value="{{ $duplicatedCompany->zip_code ?? old('zip_code', $company->zip_code) }}"
                                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>
                                <div class="col-span-2">
                                    <label for="radius"
                                        class="text-sm font-medium text-gray-100">{{ __('Radius') }}</label>
                                    @if ($errors->has('radius'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('radius') }}</span>
                                    @endif
                                    @if ($duplicatedCompany && $duplicatedCompany->radius != $company->radius)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending Approval
                                        </span>
                                    @endif
                                    <input type="number" name="radius" id="radius" autocomplete="radius"
                                        value="{{ $duplicatedCompany->radius ?? old('radius', $company->radius) }}"
                                        class="mt-1 w-2/3 bg-gray-700 text-white rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                    <span class="text-gray-500 inline sm:text-sm">km</span>
                                </div>
                                <div class="col-span-8">
                                </div>
                                <div class="col-span-2 pt-10">
                                    <a href="{{ route('companies.users.index', ['company' => $company]) }}"
                                        class="text-primary-600 hover:text-primary-900">Employees<span
                                            class="sr-only">Add users to company</span></a>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-700 px-4 py-3 text-right sm:px-6">
                            <button type="submit"
                                class="inline-flex justify-center rounded-md border border-transparent bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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

                            if ("{{ implode(',', old('company_categories', $company->categories->pluck('id')->toArray()) ?? []) }}"
                                .split(',').includes(category.id
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
                            if ("{{ implode(',', old('company_fulfillment_types', $company->fulfillmentTypes->pluck('id')->toArray() ?? [])) }}"
                                .split(',').includes(
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
</x-app-layout>
