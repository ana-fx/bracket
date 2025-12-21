<header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="mx-auto max-w-7xl px-6 lg:px-8 mt-4">
        <div class="bg-white/70 backdrop-blur-xl border border-white/40 shadow-sm rounded-2xl px-6 py-4 flex justify-between items-center">

            <a href="/" class="flex items-center gap-2 group">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-primary to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-md group-hover:shadow-lg transition">B</div>
                <span class="text-xl font-bold tracking-tight text-slate-800 group-hover:text-primary transition">Bracket</span>
            </a>

            <nav class="flex items-center gap-1">
                <a href="/" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-primary hover:bg-primary/5 rounded-xl transition">Home</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="ml-2 px-5 py-2.5 bg-slate-900 text-white hover:bg-slate-800 rounded-xl transition text-sm font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="ml-2 px-5 py-2.5 bg-primary text-white hover:bg-purple-600 rounded-xl transition text-sm font-bold shadow-md hover:shadow-lg hover:shadow-primary/30 transform hover:-translate-y-0.5">Login</a>
                @endauth
            </nav>
        </div>
    </div>
</header>
<!-- Spacer for fixed header -->
<div class="h-24"></div>
