<nav x-data="{ open: false }" class="bg-gray-800 border-b border-gray-500 fixed right-0 z-20 w-full">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 px-6 lg:px-8 relative">
        <div class="flex justify-between h-16 relative">
            <a href="/dashboard">
                <div class="flex h-6 mt-5">
                    <?xml version="1.0" encoding="UTF-8"?><svg id="a" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 123.69 30.49">
                        <path
                            d="m81.82,3.42c0-1.93,1.58-3.42,4.05-3.42s4.05,1.37,4.05,3.3c0,2.05-1.58,3.54-4.05,3.54s-4.05-1.49-4.05-3.42Z"
                            style="fill:#eb671c;" />
                        <path d="m17.31,19.13v5.29H0V3.6h16.93v5.29H6.9v2.44h8.8v5.06H6.9v2.74h10.41Z"
                            style="fill:#eb671c;" />
                        <path
                            d="m35.27,15.68v8.75h-6.25v-2.14c-.77,1.64-2.32,2.44-4.61,2.44-3.99,0-6.04-2.26-6.04-5.06,0-3.15,2.38-4.91,7.44-4.91h2.71c-.21-1.25-1.16-1.9-3.06-1.9-1.46,0-3.09.48-4.16,1.28l-2.14-4.43c1.9-1.19,4.79-1.84,7.32-1.84,5.59,0,8.8,2.41,8.8,7.82Zm-6.72,3.45v-1.13h-1.67c-1.43,0-2.05.48-2.05,1.37,0,.74.59,1.31,1.61,1.31.92,0,1.76-.48,2.11-1.55Z"
                            style="fill:#eb671c;" />
                        <path
                            d="m72.97,8.15l-6.69,16.6c-1.78,4.46-4.4,5.74-7.85,5.74-1.78,0-3.84-.57-4.94-1.46l2.23-4.61c.65.51,1.58.86,2.38.86s1.31-.24,1.67-.65l-6.84-16.48h6.9l3.36,8.72,3.42-8.72h6.37Z"
                            style="fill:#eb671c;" />
                        <path d="m74.04,2.35h6.72v22.07h-6.72V2.35Z" style="fill:#eb671c;" />
                        <rect x="82.51" y="8.15" width="6.72" height="16.27" style="fill:#eb671c;" />
                        <path
                            d="m99.72,9.04h3.54v4.91h-3.33v10.47h-6.72v-10.47h-2.23v-4.91h2.23v-.09c0-4.19,2.62-6.9,7.26-6.9,1.43,0,3,.27,3.99.8l-1.61,4.67c-.45-.21-.98-.39-1.52-.39-.95,0-1.61.57-1.61,1.87v.03Z"
                            style="fill:#eb671c;" />
                        <path
                            d="m123.6,17.85h-11.3c.48,1.25,1.61,1.9,3.27,1.9,1.49,0,2.32-.39,3.39-1.19l3.51,3.54c-1.58,1.73-3.87,2.62-7.11,2.62-5.98,0-9.85-3.6-9.85-8.45s3.96-8.42,9.22-8.42c4.82,0,8.95,2.92,8.95,8.42,0,.48-.06,1.1-.09,1.58Zm-11.42-3.24h5.24c-.27-1.37-1.25-2.17-2.62-2.17s-2.35.8-2.62,2.17Z"
                            style="fill:#eb671c;" />
                        <path
                            d="m51.86,21.99v2.43s-15.53,0-15.53,0v-3.87l6.72-7.5-6.48-2.45v-2.45h14.96v3.87l-6.72,7.5,7.05,2.47Z"
                            style="fill:#eb671c;" />
                    </svg>
                    {{-- <!-- Navigation Links -->
                <div class="hidden space-x-8 -my-px flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div> --}}
                </div>
            </a>

            {{-- <!-- Settings Dropdown -->
            <div class="hidden flex items-center ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-800 hover:text-gray-500 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div> --}}

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-gray-500 transition duration-150 ease-in-out">
                    <span class="mr-2">Menu</span>
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Responsive Navigation Menu -->
            <div :class="{ 'absolute': open, 'hidden': !open }"
                class="absolute fixed z-25 bg-gray-800 pb-8 pl-8 right-0 top-16">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-800">
                    <div class="px-4">
                        <div class="font-medium text-right text-base text-white">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-right text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
