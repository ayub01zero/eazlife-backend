<x-app-layout>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-white">Add New User</h3>
                    <p class="mt-1 text-sm text-gray-200">Enter the details of the new user.</p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('companies.users.store', $company) }}" method="POST">
                    @csrf
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="px-4 py-5 bg-gray-800 space-y-6 sm:p-6">
                            <div class="grid grid-cols-3 gap-6">
                                <div class="col-span-3 sm:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-100">Name</label>
                                    @if ($errors->has('name'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('name') }}</span>
                                    @endif
                                    <input type="text" name="name" id="name" autocomplete="name"
                                        class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-700 text-white">
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-6">
                                <div class="col-span-3 sm:col-span-2">
                                    <label for="phone_number" class="block text-sm font-medium text-gray-100">Phone
                                        number</label>
                                    @if ($errors->has('phone_number'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('phone_number') }}</span>
                                    @endif
                                    <input type="text" name="phone_number" id="phone_number" autocomplete="email"
                                        class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-700 text-white">
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-6">
                                <div class="col-span-3 sm:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-100">Email</label>
                                    @if ($errors->has('email'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('email') }}</span>
                                    @endif
                                    <input type="email" name="email" id="email" autocomplete="email"
                                        class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-700 text-white">
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-6">
                                <div class="col-span-3 sm:col-span-2">
                                    <label for="password"
                                        class="block text-sm font-medium text-gray-100">Password</label>
                                    @if ($errors->has('password'))
                                        <span class="text-red-500 text-xs">{{ $errors->first('password') }}</span>
                                    @endif
                                    <input type="password" name="password" id="password" autocomplete="password"
                                        class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-700 text-white">
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
</x-app-layout>
