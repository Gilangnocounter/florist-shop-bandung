<nav x-data="{ open: false }" class="bg-white border-b-4 border-pink-500 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            
            <div class="flex items-center gap-4">
                <div class="shrink-0 flex items-center bg-pink-50 p-2 rounded-full border border-pink-100">
                    <a href="{{ route('dashboard') }}" class="text-2xl">🌹</a>
                </div>
                @if(Auth::user()->role !== 'admin')
                    <div class="hidden sm:block">
                        <a href="{{ route('orders.history') }}" class="text-xs font-bold text-gray-600 hover:text-pink-600 transition uppercase tracking-widest border-b-2 border-transparent hover:border-pink-500 pb-1">
                            Pesanan Saya 🛍️
                        </a>
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-center flex-1">
                <h1 class="text-2xl md:text-3xl font-serif font-bold text-gray-900 uppercase tracking-tighter">
                    FLORIST SHOP <span class="text-pink-600 italic">BANDUNG</span>
                </h1>
            </div>

            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-pink-200 text-sm font-bold rounded-full text-gray-700 bg-pink-50 hover:bg-pink-100 transition duration-150">
                            <div class="uppercase tracking-widest text-xs">{{ Auth::user()->name }}</div>
                            <div class="ms-1 text-pink-500">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
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
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-md text-gray-400 hover:text-pink-500 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-pink-100">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->role !== 'admin')
                <x-responsive-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.history')">
                    {{ __('Pesanan Saya 🛍️') }}
                </x-responsive-nav-link>
            @endif
        </div>
        <div class="pt-4 pb-1 border-t border-pink-50">
            <div class="px-4 mb-3">
                <div class="font-bold text-pink-600 uppercase">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="font-bold text-red-500">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>