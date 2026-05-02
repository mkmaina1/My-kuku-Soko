<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 kuku-header">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <i class="fas fa-egg text-white text-2xl mr-2"></i>
                        <span class="text-white font-bold text-xl">My-Kuku-Soko</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                @auth
                    @if(auth()->user()->hasRole())
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-gray-200">
                                <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                            </x-nav-link>

                            @if(auth()->user()->isFarmer())
                                <x-nav-link href="#" class="text-white hover:text-gray-200">
                                    <i class="fas fa-egg mr-2"></i> {{ __('My Listings') }}
                                </x-nav-link>
                                <x-nav-link href="#" class="text-white hover:text-gray-200">
                                    <i class="fas fa-shopping-cart mr-2"></i> {{ __('Orders') }}
                                </x-nav-link>
                            @endif

                            @if(auth()->user()->isClient())
                                <x-nav-link href="#" class="text-white hover:text-gray-200">
                                    <i class="fas fa-store mr-2"></i> {{ __('Marketplace') }}
                                </x-nav-link>
                                <x-nav-link href="#" class="text-white hover:text-gray-200">
                                    <i class="fas fa-history mr-2"></i> {{ __('My Orders') }}
                                </x-nav-link>
                            @endif

                            @if(auth()->user()->isAdmin())
                                <x-nav-link href="#" class="text-white hover:text-gray-200">
                                    <i class="fas fa-users mr-2"></i> {{ __('Users') }}
                                </x-nav-link>
                                <x-nav-link href="#" class="text-white hover:text-gray-200">
                                    <i class="fas fa-chart-bar mr-2"></i> {{ __('Analytics') }}
                                </x-nav-link>
                            @endif
                        </div>
                    @else
                        <!-- Show role selection prompt -->
                        <div class="hidden sm:flex sm:items-center sm:ml-10">
                            <a href="{{ route('select.role') }}" class="text-white hover:text-gray-200 bg-green-600 px-4 py-2 rounded-lg font-medium">
                                <i class="fas fa-user-tag mr-2"></i> Select Role
                            </a>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Settings Dropdown -->
            @auth
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-white/20 hover:bg-white/30 focus:outline-none transition ease-in-out duration-150">
                                <div class="flex items-center">
                                    <i class="fas fa-user mr-2"></i>
                                    <div>{{ Auth::user()->name }}</div>
                                    @if(auth()->user()->role)
                                        <span class="role-badge {{ auth()->user()->role }} ml-2">
                                            {{ ucfirst(auth()->user()->role) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                <i class="fas fa-user mr-2"></i> {{ __('Profile') }}
                            </x-dropdown-link>

                            @if(!auth()->user()->role)
                                <x-dropdown-link :href="route('select.role')">
                                    <i class="fas fa-user-tag mr-2"></i> {{ __('Select Role') }}
                                </x-dropdown-link>
                            @endif

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endauth

            <!-- Hamburger (for mobile) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-200 hover:bg-white/20 focus:outline-none focus:bg-white/20 focus:text-gray-200 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (for mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        @auth
            @if(auth()->user()->hasRole())
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                    </x-responsive-nav-link>

                    @if(auth()->user()->isFarmer())
                        <x-responsive-nav-link href="#">
                            <i class="fas fa-egg mr-2"></i> {{ __('My Listings') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link href="#">
                            <i class="fas fa-shopping-cart mr-2"></i> {{ __('Orders') }}
                        </x-responsive-nav-link>
                    @endif

                    @if(auth()->user()->isClient())
                        <x-responsive-nav-link href="#">
                            <i class="fas fa-store mr-2"></i> {{ __('Marketplace') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link href="#">
                            <i class="fas fa-history mr-2"></i> {{ __('My Orders') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @else
                <div class="pt-2 pb-3">
                    <x-responsive-nav-link :href="route('select.role')">
                        <i class="fas fa-user-tag mr-2"></i> {{ __('Select Role') }}
                    </x-responsive-nav-link>
                </div>
            @endif

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    @if(auth()->user()->role)
                        <div class="mt-2">
                            <span class="role-badge {{ auth()->user()->role }}">{{ ucfirst(auth()->user()->role) }}</span>
                        </div>
                    @endif
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        <i class="fas fa-user mr-2"></i> {{ __('Profile') }}
                    </x-responsive-nav-link>

                    @if(!auth()->user()->role)
                        <x-responsive-nav-link :href="route('select.role')">
                            <i class="fas fa-user-tag mr-2"></i> {{ __('Select Role') }}
                        </x-responsive-nav-link>
                    @endif

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <!-- Guest links -->
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('login')">
                    <i class="fas fa-sign-in-alt mr-2"></i> {{ __('Login') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">
                    <i class="fas fa-user-plus mr-2"></i> {{ __('Register') }}
                </x-responsive-nav-link>
            </div>
        @endauth
    </div>
</nav>
