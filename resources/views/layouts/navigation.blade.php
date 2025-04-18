<nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="font-bold text-xl text-blue-600 dark:text-blue-400">
                        {{ config('app.name', 'Mail Dashboard') }}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:ml-10 sm:flex">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 
                             {{ request()->routeIs('dashboard') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }} 
                             text-sm font-medium">
                        Dashboard
                    </a>
                    <a href="{{ route('folders.index') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 
                             {{ request()->routeIs('folders.*') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }} 
                             text-sm font-medium">
                        Folders
                    </a>
                    <a href="{{ route('settings.index') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 
                             {{ request()->routeIs('settings.*') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }} 
                             text-sm font-medium">
                        Settings
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="relative">
                    <button type="button" onclick="toggleUserDropdown()" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <div class="text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                        <svg class="ml-2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- User dropdown menu -->
                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5">
                        <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Theme Toggle -->
            <div class="flex items-center ml-4">
                <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-sm p-2.5">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile menu button -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button onclick="toggleMobileMenu()" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path id="menu-icon" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 
                 {{ request()->routeIs('dashboard') ? 'border-blue-500 text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-gray-800' : 'border-transparent text-gray-600 dark:text-gray-400' }} 
                 text-base font-medium">
                Dashboard
            </a>
            <a href="{{ route('folders.index') }}" class="block pl-3 pr-4 py-2 border-l-4 
                 {{ request()->routeIs('folders.*') ? 'border-blue-500 text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-gray-800' : 'border-transparent text-gray-600 dark:text-gray-400' }} 
                 text-base font-medium">
                Folders
            </a>
            <a href="{{ route('settings.index') }}" class="block pl-3 pr-4 py-2 border-l-4 
                 {{ request()->routeIs('settings.*') ? 'border-blue-500 text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-gray-800' : 'border-transparent text-gray-600 dark:text-gray-400' }} 
                 text-base font-medium">
                Settings
            </a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center px-4">
                <div>
                    <div class="text-base font-medium leading-none text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium leading-none text-gray-500 dark:text-gray-400 mt-1">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Settings
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleUserDropdown() {
        document.getElementById('userDropdown').classList.toggle('hidden');
    }

    function toggleMobileMenu() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
        document.getElementById('menu-icon').classList.toggle('hidden');
        document.getElementById('menu-icon').classList.toggle('inline-flex');
        document.getElementById('close-icon').classList.toggle('hidden');
        document.getElementById('close-icon').classList.toggle('inline-flex');
    }
</script>