<x-app-layout>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-white">Product information</h3>
                    <p class="mt-1 text-sm text-gray-200">Add information about the product.</p>
                </div>
            </div>
            <div class="mt-5 md:col-span-2 md:mt-0">
                <form action="{{ route('companies.products.update', ['company' => $company, 'product' => $product]) }}"
                    method="POST">
                    @method('PUT')
                    @csrf
                    <div class="overflow-hidden shadow sm:rounded-md">
                        <div class="bg-gray-800 px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-6 gap-6">

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="product_category"
                                        class="text-sm font-medium text-gray-100">Category</label>
                                    @if ($errors->has('product_category'))
                                        <span
                                            class="text-red-500 text-xs">{{ $errors->first('product_category') }}</span>
                                    @endif
                                    <select id="product_category" name="product_category"
                                        autocomplete="product_category"
                                        class="mt-1 block w-full rounded-md bg-gray-700 text-white border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                        @foreach ($productCategories as $productCategory)
                                            <option
                                                {{ $productCategory->id === $product->product_category_id ? 'selected' : '' }}>
                                                {{ $productCategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-span-12 sm:col-span-3">
                                    <label for="image_caption" class="text-sm font-medium text-gray-100">Image</label>
                                    @if ($errors->has('image_caption'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('image_caption') }}</span>
                                    @endif
                                    @if ($errors->has('image_id'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('image_id') }}</span>
                                    @endif
                                    <div class="relative mt-1">
                                        <input id="image_caption" type="text" name="image_caption"
                                            value="{{ $product->image->caption }}"
                                            class="w-full rounded-md border border-gray-600 bg-gray-700 text-white py-2 pl-3 pr-12 shadow-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500 sm:text-sm"
                                            role="combobox" aria-controls="options" aria-expanded="false">
                                        <input type="hidden" id="image_id" name="image_id"
                                            value="{{ $product->image->getItem()->id }}">
                                        <button type="button"
                                            class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <ul class="absolute overflow-auto z-10 mt-1 max-h-96 w-full rounded-md bg-gray-800 py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                            id="options" role="listbox" style="display: none;">
                                            @foreach ($images as $image)
                                                <li class="relative cursor-default select-none py-2 pl-3 pr-9 text-white"
                                                    role="option" tabindex="-1" id="{{ $image->getItem()->id }}">
                                                    <div class="flex items-center cursor-pointer"
                                                        onclick="updateInput(this)">
                                                        <img src="{{ $image->getItem()->getUrl() }}" alt=""
                                                            class="h-10 w-10 flex-shrink-0">
                                                        <span class="ml-3 truncate">{{ $image->caption }}</span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="name" class="text-sm font-medium text-gray-100">Productname</label>
                                    @if ($errors->has('name'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('name') }}</span>
                                    @endif
                                    @if ($duplicatedProduct && $duplicatedProduct->name != $product->name)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending Approval
                                        </span>
                                    @endif
                                    <input type="text" name="name" id="name" autocomplete="name"
                                        value="{{ $duplicatedProduct->name ?? old('name', $product->name) }}"
                                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>


                                <div class="col-span-6 sm:col-span-3">
                                    <label for="description" class="text-sm font-medium text-gray-100">Product description</label>
                                    @if ($errors->has('description'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('description') }}</span>
                                    @endif
                                    @if ($duplicatedProduct && $duplicatedProduct->description != $product->description)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending Approval
                                        </span>
                                    @endif
                                    <input type="text" name="description" id="description" autocomplete="description"
                                        value="{{ $duplicatedProduct->description ?? old('description', $product->description) }}"
                                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>

                                <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                                    <label for="price" class="text-sm font-medium text-gray-100">Prijs</label>
                                    @if ($errors->has('price'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('price') }}</span>
                                    @endif
                                    @if ($duplicatedProduct && $duplicatedProduct->price != $product->price)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending Approval
                                        </span>
                                    @endif
                                    <input type="number" name="price" id="price" autocomplete="price"
                                        min="0" step="0.01"
                                        value="{{ $duplicatedProduct->price ?? old('price', $product->price) }}"
                                        class="mt-1 block bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>

                                <div class="col-span-12 sm:col-span-6">
                                    <label for="extras" class="text-sm font-medium text-gray-100">Product
                                        Extras</label>
                                    @if ($errors->has('extras'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('extras') }}</span>
                                    @endif
                                    @if ($errors->has('extras.*.name'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('extras.*.name') }}</span>
                                    @endif
                                    @if ($errors->has('extras.*.price'))
                                        <span
                                            class="text-red-500 text-xs">{{ $errors->first('extras.*.price') }}</span>
                                    @endif
                                    @if ($errors->has('extras.*.max_quantity'))
                                        <span
                                            class="text-red-500 text-xs">{{ $errors->first('extras.*.max_quantity') }}</span>
                                    @endif
                                    <div class="mt-1">
                                        <div id="extras-container">
                                            {{-- @if (isset($extras))
                                                @foreach ($extras as $extraPair)
                                                    <div class="grid grid-cols-12 gap-6">
                                                        <div class="col-span-3">
                                                            <label for="extras[{{ $loop->index }}][name]"
                                                                class="text-sm font-medium text-gray-100">Name</label>
                                                            @if ($extraPair['edited'] && $extraPair['edited']->name != $extraPair['original']->name)
                                                                <span
                                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending
                                                                    Approval</span>
                                                            @endif
                                                            <input type="text"
                                                                name="extras[{{ $loop->index }}][name]"
                                                                id="extras[{{ $loop->index }}][name]"
                                                                value="{{ $extraPair['edited']->name ?? $extraPair['original']->name }}"
                                                                class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                                        </div>

                                                        <div class="col-span-2">
                                                            <label for="extras[{{ $loop->index }}][price]"
                                                                class="text-sm font-medium text-gray-100">Price</label>
                                                            @if ($extraPair['edited'] && $extraPair['edited']->price != $extraPair['original']->price)
                                                                <span
                                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending
                                                                    Approval</span>
                                                            @endif
                                                            <input type="number"
                                                                name="extras[{{ $loop->index }}][price]"
                                                                id="extras[{{ $loop->index }}][price]"
                                                                min="0" step="0.01"
                                                                value="{{ $extraPair['edited']->price ?? $extraPair['original']->price }}"
                                                                class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                                        </div>

                                                        <div class="col-span-2">
                                                            <label for="extras[{{ $loop->index }}][max_quantity]"
                                                                class="text-sm font-medium text-gray-100">Max.</label>
                                                            @if ($extraPair['edited'] && $extraPair['edited']->max_quantity != $extraPair['original']->max_quantity)
                                                                <span
                                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending
                                                                    Approval</span>
                                                            @endif
                                                            <input type="number"
                                                                name="extras[{{ $loop->index }}][max_quantity]"
                                                                id="extras[{{ $loop->index }}][max_quantity]"
                                                                min="1"
                                                                value="{{ $extraPair['edited']->max_quantity ?? $extraPair['original']->max_quantity }}"
                                                                class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif --}}
                                            @if (isset($product->extras))
                                                @foreach ($product->extras as $extra)
                                                    <div class="grid grid-cols-12 gap-6">
                                                        <div class="col-span-3">
                                                            <label for="extras[{{ $loop->index }}][name]"
                                                                class="text-sm font-medium text-gray-100">Naam</label>
                                                            <input type="text"
                                                                name="extras[{{ $loop->index }}][name]"
                                                                id="extras[{{ $loop->index }}][name]"
                                                                value="{{ $extra->name }}"
                                                                class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                                        </div>
                                                        <div class="col-span-2">
                                                            <label for="extras[{{ $loop->index }}][price]"
                                                                class="text-sm font-medium text-gray-100">Prijs</label>
                                                            <input type="number"
                                                                name="extras[{{ $loop->index }}][price]"
                                                                id="extras[{{ $loop->index }}][price]"
                                                                min="0" step="0.01"
                                                                value="{{ $extra->price }}"
                                                                class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                                        </div>
                                                        <div class="col-span-2">
                                                            <label for="extras[{{ $loop->index }}][max_quantity]"
                                                                class="text-sm font-medium text-gray-100">Max.
                                                            </label>
                                                            <input type="number"
                                                                name="extras[{{ $loop->index }}][max_quantity]"
                                                                id="extras[{{ $loop->index }}][max_quantity]"
                                                                min="1" value="{{ $extra->max_quantity }}"
                                                                class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <button type="button" id="add-extra"
                                            class="mt-2 text-primary-600 hover:text-primary-800 focus:outline-none sm:text-sm">
                                            + Add extra
                                        </button>
                                    </div>
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
            window.addEventListener('load', () => {
                const input = document.querySelector('#image_caption');
                const nameInput = document.querySelector('#name');
                const idInput = document.querySelector('#image_id');
                const optionsList = document.querySelector('#options');
                const options = optionsList.querySelectorAll('[role="option"]');
                const priceInput = document.querySelector('#price');
                const addExtraButton = document.querySelector("#add-extra");
                const extrasContainer = document.querySelector("#extras-container");

                let isOpen = false;
                let optionsToShow = 10;

                input.addEventListener('click', () => {
                    if (!isOpen) {
                        optionsList.style.display = 'block';
                        isOpen = true;
                        input.dispatchEvent(new Event("input"));
                    } else {
                        optionsList.style.display = 'none';
                        isOpen = false;
                    }
                });

                input.addEventListener('input', (event) => {
                    if (!isOpen) {
                        optionsList.style.display = 'block';
                        isOpen = true;
                    }

                    const value = event.target.value.toLowerCase();

                    if (value.length > 0) {
                        options.forEach((option) => {
                            const text = option.innerText.toLowerCase();
                            if (text.includes(value)) {
                                option.style.display = 'block';
                            } else {
                                option.style.display = 'none';
                            }
                        });
                    } else {
                        options.forEach((option) => {
                            option.style.display = 'block';
                        });
                    }
                });

                options.forEach((option) => {
                    option.addEventListener('click', (event) => {
                        input.value = event.target.closest("li").querySelector("span").textContent;
                        nameInput.value = event.target.closest("li").querySelector("span").textContent;
                        idInput.value = event.target.closest("li").id;
                        optionsList.style.display = 'none';
                        isOpen = false;
                    });
                });

                addExtraButton.addEventListener('click', () => {
                    const extrasContainer = document.getElementById('extras-container');
                    const index = extrasContainer.children.length;
                    const newRow = `
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-3">
                    <label for="extras[${index}][name]" class="text-sm font-medium text-gray-100">Naam</label>
                    <input type="text" name="extras[${index}][name]" id="extras[${index}][name]" value=""
                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                </div>
                <div class="col-span-2">
                    <label for="extras[${index}][price]" class="text-sm font-medium text-gray-100">Prijs</label>
                    <input type="number" name="extras[${index}][price]" id="extras[${index}][price]" min="0" step="0.01" value=""
                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                </div>
                <div class="col-span-2">
                    <label for="extras[${index}][max_quantity]" class="text-sm font-medium text-gray-100">Max.</label>
                    <input type="number" name="extras[${index}][max_quantity]" id="extras[${index}][max_quantity]" min="1" value="1"
                        class="mt-1 block w-full bg-gray-700 text-white rounded-md border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                </div>
            </div>
        `;
                    extrasContainer.insertAdjacentHTML('beforeend', newRow);
                });
            });
        </script>
    @endpush
</x-app-layout>
