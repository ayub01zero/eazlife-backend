<x-app-layout>
    <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-100">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-xl font-semibold text-white">Producten</h1>
                    <p class="mt-2 text-sm text-gray-100">Voeg hier producten toe.</p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <a href="{{ route('companies.products.create', ['company' => $company]) . ($productCategoryId ? '?product_category=' . $productCategoryId : '') }}"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto">Voeg
                        product toe</a>
                </div>
            </div>
            <div class="mt-8 flex flex-col">
                <div>
                    {{-- <div class="sm:hidden">
                        <label for="tabs" class="sr-only">Select a tab</label>
                        <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                        <select id="tabs" name="tabs"
                            class="block w-full rounded-md border-gray-300 focus:border-primary-500 focus:ring-primary-500">

                            @foreach ($productCategories as $productCategory)
                                <option>{{ $productCategory->name }}</option>
                            @endforeach


                            <option selected>Selected</option>

                        </select>
                    </div> --}}
                    <div class="block">
                        <nav class="flex space-x-4 overflow-scroll pb-4" aria-label="Tabs">
                            <a href="{{ route('companies.products.index', ['company' => $company]) }}"
                                class="{{ !$productCategoryId ? 'text-gray-100 bg-gray-700' : 'text-gray-500 hover:text-gray-100' }} px-3 py-2 font-medium text-sm rounded-md"
                                aria-current="page">Alle categorieën</a>
                            @foreach ($productCategories as $productCategory)
                                <a href="{{ route('companies.products.index', ['company' => $company]) }}?product_category={{ $productCategory->id }}"
                                    class="{{ $productCategoryId == $productCategory->id ? 'text-gray-100 bg-gray-700' : 'text-gray-500 hover:text-gray-100' }} px-3 py-2 font-medium text-sm rounded-md">{{ $productCategory->name }}</a>
                            @endforeach
                            <a href="{{ route('product_categories.create-at-company', ['company' => $company]) }}"
                                class="text-gray-500 hover:text-gray-100 px-3 pt-1 font-medium text-xl rounded-md"
                                aria-current="page">+</a>
                        </nav>
                    </div>
                </div>
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-500/5">
                                    <tr>
                                        <th scope="col"
                                            class="py-3.5 pl-3 pr-3 text-left text-sm font-semibold text-white">
                                            Naam</th>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                            Prijs</th>
                                        {{-- <th scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                                    Status</th>
                                                <th scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                                    Role</th> --}}
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Producten toevoegen</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700 bg-gray-500/10">
                                    @foreach ($products as $product)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                                <div class="flex items-center">
                                                    <div class="h-10 w-10 flex-shrink-0">
                                                        <img class="h-10 w-10 rounded-full"
                                                            src="{{ $product->image->getItem()->getUrl() }}"
                                                            alt="">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="font-medium text-white">{{ $product->name }}
                                                        </div>
                                                        <div class="text-gray-500">
                                                            {{ $product->productCategory->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                                {{ $product->price }}
                                            </td>
                                            @if ($product->is_approved == 0)
                                                <td
                                                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Not approved yet
                                                    </span>
                                                </td>
                                            @else
                                                <td
                                                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                    <a href="{{ route('companies.products.edit', ['company' => $company, 'product' => $product]) }}"
                                                        class="text-primary-600 hover:text-primary-900">Edit
                                                        product<span class="sr-only">Edit product</span></a>
                                                </td>
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
</x-app-layout>
