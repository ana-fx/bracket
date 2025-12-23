<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr" crossorigin="anonymous"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="antialiased font-sans bg-gray-100" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 bg-gray-900 text-white transition-all duration-300 transform shadow-2xl overflow-y-auto"
            :class="sidebarOpen ? 'translate-x-0 w-64 lg:static lg:inset-auto lg:translate-x-0' : '-translate-x-full w-64 lg:static lg:w-0 lg:-translate-x-0 lg:overflow-hidden'">

            <div class="flex items-center justify-center h-16 bg-gray-800 border-b border-gray-700 min-w-[16rem]">
                <span class="text-2xl font-bold tracking-wider uppercase text-primary">Admin Panel</span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 min-w-[16rem]">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-3 text-gray-300 transition-colors rounded-xl hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('tournaments.create') }}"
                    class="flex items-center px-4 py-3 text-gray-300 transition-colors rounded-xl hover:bg-gray-800 hover:text-white {{ request()->routeIs('tournaments.create') ? 'bg-gray-800 text-white' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Tournament
                </a>

                <a href="/"
                    class="flex items-center px-4 py-3 text-gray-300 transition-colors rounded-xl hover:bg-gray-800 hover:text-white mt-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Site
                </a>
            </nav>

            <div class="px-4 py-4 border-t border-gray-700 min-w-[16rem]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black opacity-50 lg:hidden"
            style="display: none;"></div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Topbar (Visible on all screens now to allow toggling on desktop) -->
            <header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-200">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="text-gray-500 focus:outline-none hover:text-gray-900 transition-colors">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>

                <div class="flex items-center gap-4">
                    <div class="font-bold text-gray-800">Admin</div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 relative">

                {{-- Toast Notification Container --}}
                <div x-data="toastHandler()"
                    class="fixed top-4 right-4 z-[9999] flex flex-col gap-2 w-full max-w-sm pointer-events-none"
                    @notify.window="add($event.detail.message, $event.detail.type, $event.detail.description)">

                    <template x-for="toast in toasts" :key="toast.id">
                        <div x-show="toast.show" x-transition:enter="transform ease-out duration-300 transition"
                            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="pointer-events-auto w-full bg-white rounded-xl shadow-lg p-4 flex items-start gap-4 transform transition-all">

                            <div class="flex-shrink-0">
                                <template x-if="toast.type === 'success'">
                                    <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </template>
                                <template x-if="toast.type === 'error'">
                                    <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </template>
                            </div>

                            <div class="flex-1 pt-0.5">
                                <p class="text-sm font-medium text-gray-900" x-text="toast.message"></p>
                                <template x-if="toast.description">
                                    <p class="mt-1 text-sm text-gray-500" x-text="toast.description"></p>
                                </template>
                            </div>

                            <div class="flex-shrink-0 flex">
                                <button @click="remove(toast.id)"
                                    class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                @yield('content')
            </main>

            <script>
                function toastHandler() {
                    return {
                        toasts: [],
                        init() {
                            @if(session('success'))
                                this.add("{{ session('success') }}", 'success');
                            @endif
                            @if(session('error'))
                                this.add("{{ session('error') }}", 'error');
                            @endif
                        },
                        add(message, type = 'success', description = null) {
                            const id = Date.now();
                            this.toasts.push({ id, message, type, description, show: true });
                            setTimeout(() => this.remove(id), 8000); // Increased time to read error
                        },
                        remove(id) {
                            const index = this.toasts.findIndex(t => t.id === id);
                            if (index > -1) {
                                this.toasts[index].show = false;
                                setTimeout(() => {
                                    this.toasts = this.toasts.filter(t => t.id !== id);
                                }, 300); // Wait for transition
                            }
                        }
                    }
                }
            </script>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
