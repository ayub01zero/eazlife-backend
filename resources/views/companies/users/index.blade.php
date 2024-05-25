<x-app-layout>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-3">
                <div class="px-4 py-5 bg-gray-800 sm:p-6 shadow sm:rounded-md">
                    <div class="flex justify-between pb-4">
                        <h3 class="text-lg font-medium leading-6 text-white">Company Users</h3>
                        <a href="{{ route('companies.users.create', $company) }}"
                            class="text-primary-600 hover:text-primary-900">Add New User</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($users as $user)
                            <div class="bg-gray-700 rounded-md p-4">
                                <div class="text-sm font-medium text-gray-100">{{ $user->name }}</div>
                                <div class="text-sm text-gray-400">{{ $user->email }}</div>
                                <div class="text-sm text-gray-400">{{ $user->phone_number }}</div>
                                <div class="mt-4 flex-row justify-end">
                                    <a href="{{ route('companies.users.edit', ['company' => $company, 'user' => $user]) }}"
                                        class="text-sm text-primary-600 hover:text-primary-900 mr-2">Edit</a>
                                    <form
                                        action="{{ route('companies.users.destroy', ['company' => $company, 'user' => $user]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-sm text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
