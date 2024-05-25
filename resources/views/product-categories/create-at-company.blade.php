<x-app-layout>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-white">Categorie toevoegen</h3>
                    <p class="mt-1 text-sm text-gray-200">Kies hier een productcategorie die u wilt toevoegen aan uw
                        bedrijf.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:col-span-2 md:mt-0">
                <form action="{{ route('product_categories.store-at-company', ['company' => $company]) }}" method="POST">
                    @csrf
                    <div class="overflow-hidden shadow sm:rounded-md">
                        <div class="bg-gray-800 px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <x-input-label for="product_category" :value="__('Product category')" />
                                    <x-select-input id="product_category" name="product_category" :options="$productCategories->pluck('name', 'id')->toArray()" />
                                    <x-input-error :messages="$errors->get('product_category')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-700 px-4 py-3 text-right sm:px-6">
                            <button type="submit"
                                class="inline-flex justify-center rounded-md border border-transparent bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">Opslaan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
