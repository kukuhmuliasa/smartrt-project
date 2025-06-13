<nav x-data="{ open: false }" class="bg-[#1E293B] border-b border-gray-200 dark:border-gray-700 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('storage/logo/SmartRT.png') }}" class="h-10 w-auto" alt="Logo" />
                    <span class="text-xl font-semibold text-white hidden sm:inline">Smart RT</span>
                </a>
            </div>
            <div class="hidden sm:flex sm:items-center space-x-4">
                <x-nav-link class="text-white" :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>
                @if(Auth::check() && Auth::user()->role == 'admin')
                    <x-nav-link class="text-white" :href="route('admin.rt.create')" :active="request()->routeIs('admin.rt.create')">
                        {{ __('Daftarkan RT') }}
                    </x-nav-link>
                    <x-nav-link class="text-white" :href="route('admin.rt.index')" :active="request()->routeIs('admin.rt.index')">
                        {{ __('Kelola RT Saya') }}
                    </x-nav-link>
                    @if(Auth::user()->rt_id)
                        <x-nav-link class="text-white" :href="route('admin.warga.index')" :active="request()->routeIs('admin.warga.*')">
                            {{ __('Kelola Data Warga') }}
                        </x-nav-link>
                        <x-nav-link class="text-white" :href="route('admin.iuran.index')" :active="request()->routeIs('admin.iuran.*')">
                            {{ __('Catat Iuran Warga') }}
                        </x-nav-link>
                        <x-nav-link class="text-white" :href="route('admin.surat.index')" :active="request()->routeIs('admin.surat.*')">
                            {{ __('Pengajuan Surat') }}
                        </x-nav-link>
                    @endif
                @endif
                @if(Auth::check() && Auth::user()->role == 'warga' && Auth::user()->rt_id)
                    <x-nav-link class="text-white" :href="route('warga.surat.index')" :active="request()->routeIs('warga.surat.*')">
                        {{ __('Pengajuan Surat Saya') }}
                    </x-nav-link>
                    <x-nav-link :href="route('chatbot.index')" :active="request()->routeIs('chatbot.index')">
                        {{ __('Chatbot') }}
                    </x-nav-link>

                @endif
            </div>
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-white hover:text-gray-200 focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            <div class="sm:hidden flex items-center">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 text-white hover:text-gray-300 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div :class="{'block': open, 'hidden': !open}" class="sm:hidden bg-[#1E293B] border-t border-gray-200 dark:border-gray-700">
        <div class="pt-4 pb-3 space-y-1">
            <x-responsive-nav-link class="text-white" :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @if(Auth::check() && Auth::user()->role == 'admin')
                <x-responsive-nav-link class="text-white" :href="route('admin.rt.create')" :active="request()->routeIs('admin.rt.create')">
                    {{ __('Daftarkan RT') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link class="text-white" :href="route('admin.rt.index')" :active="request()->routeIs('admin.rt.index')">
                    {{ __('Kelola RT Saya') }}
                </x-responsive-nav-link>
                @if(Auth::user()->rt_id)
                    <x-responsive-nav-link class="text-white" :href="route('admin.warga.index')" :active="request()->routeIs('admin.warga.*')">
                        {{ __('Kelola Data Warga') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link class="text-white" :href="route('admin.iuran.index')" :active="request()->routeIs('admin.iuran.*')">
                        {{ __('Catat Iuran Warga') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link class="text-white" :href="route('admin.surat.index')" :active="request()->routeIs('admin.surat.*')">
                        {{ __('Pengajuan Surat') }}
                    </x-responsive-nav-link>
                @endif
            @endif
            @if(Auth::check() && Auth::user()->role == 'warga' && Auth::user()->rt_id)
                <x-responsive-nav-link class="text-white" :href="route('warga.surat.index')" :active="request()->routeIs('warga.surat.*')">
                    {{ __('Pengajuan Surat Saya') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('chatbot.index')" :active="request()->routeIs('chatbot.index')">
                    {{ __('Chatbot') }}
                </x-responsive-nav-link>
            @endif
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 pb-1">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-200">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link class="text-white" :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link class="text-white" :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
