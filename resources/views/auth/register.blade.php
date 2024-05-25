<x-guest-layout>
    @if (Auth::user() && Auth::user()->isAdmin())
        <div class="mb-4 flex items-center justify-between">
            <a href="/admin" class="flex items-center text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="mx-1 text-sm">Admin panel</span>
            </a>
            <div>
                <div class="mr-2 font-medium text-sm text-primary-500">
                    {{ __('Creating user as ADMIN') }}
                </div>
            </div>
        </div>
    @endif

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12">
                <x-input-label for="company_name" :value="__('Company name')" />
                <x-text-input id="company_name" class="" type="text" name="company_name" :value="old('company_name')"
                    required autofocus />
                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
            </div>
            <div class="col-span-6">
                <x-input-label for="first_name" :value="__('First name')" />
                <x-text-input id="first_name" class="" type="text" name="first_name" :value="old('first_name')" required
                    autofocus />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>
            <div class="col-span-6">
                <x-input-label for="last_name" :value="__('Last name')" />
                <x-text-input id="last_name" type="text" name="last_name" :value="old('last_name')" required autofocus />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
            <div class="col-span-12">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="col-span-8">
                <x-input-label for="phone_number" :value="__('Phone number')" />
                <x-text-input id="phone_number" type="text" name="phone_number" :value="old('phone_number')" required />
                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
            </div>
            <div class="col-span-6">
                <x-input-label for="company_type_id" :value="__('Company type')" />
                @if (count($types) == 1)
                    <x-select-input id="company_type_id" name="company_type_id" :options="$types->pluck('name', 'id')->toArray()" disabled />
                @else
                    <x-select-input id="company_type_id" name="company_type_id" :options="$types->pluck('name', 'id')->toArray()" :selectedOptions="[old('company_type_id')]" />
                @endif
                <x-input-error :messages="$errors->get('company_type_id')" class="mt-2" />
            </div>
            <div class="col-span-6">
                <x-input-label for="company_categories" :value="__('Categories')" />
                <x-select-input id="company_categories" name="company_categories[]" :options="$types[0]->categories->pluck('name', 'id')->toArray()" multiple
                    size="5" />
                <x-input-error :messages="$errors->get('company_categories')" class="mt-2" />
            </div>
            <div class="col-span-12">
                <x-input-label for="company_fulfillment_types" :value="__('Flows')" />
                <div id="company_fulfillment_types" class="flex flex-wrap items-center space-x-8 text-gray-500">
                </div>
                <x-input-error :messages="$errors->get('company_fulfillment_types')" class="mt-2" />
            </div>
            @push('guest-scripts')
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

                        updateCompanyCategories(companyTypeSelect.value);
                        updateCompanyFulfillmentTypes(companyTypeSelect.value);
                    }
                </script>
            @endpush
            <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                <div class="col-md-6">
                    {!! RecaptchaV3::field('register') !!}
                    @if ($errors->has('g-recaptcha-response'))
                        <span class="help-block">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-span-12">
                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-200 hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                        href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <x-primary-button class="ml-4">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </div>
        </div>
    </form>
</x-guest-layout>