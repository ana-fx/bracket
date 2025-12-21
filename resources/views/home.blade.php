@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="relative overflow-hidden min-h-[600px] flex items-center justify-center">
        <!-- Modern Abstract Background -->
        <div class="absolute inset-0 bg-gray-50">
             <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-transparent to-purple-500/5"></div>
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-[100px] animate-pulse"></div>
            <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-purple-400/20 rounded-full blur-[120px]"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10 text-center">
            <div class="inline-block mb-6 animate-fade-in-up">
                <span class="px-4 py-2 rounded-full bg-white/60 backdrop-blur-md border border-white/40 text-sm font-semibold text-primary shadow-sm tracking-wide flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    Binary Tree Tournament Generator
                </span>
            </div>

            <h1 class="text-5xl md:text-7xl font-black text-slate-900 mb-8 tracking-tight leading-tight animate-fade-in-up delay-100">
                Craft Perfect <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-600">Jiu-Jitsu Brackets</span>
            </h1>

            <p class="text-xl text-slate-600 max-w-2xl mx-auto mb-10 leading-relaxed animate-fade-in-up delay-200">
                Automate your tournament organization with our modern, binary-tree powered bracket generator. Designed for precision, built for effortless management.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up delay-300">
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition transform hover:-translate-y-1 shadow-lg shadow-slate-900/20">
                    Start Creating Brackets
                </a>
                <a href="#tournaments" class="w-full sm:w-auto px-8 py-4 bg-white/80 backdrop-blur-sm text-slate-700 font-bold rounded-2xl hover:bg-white border border-white/60 transition transform hover:-translate-y-1 shadow-md">
                    View Active Tournaments
                </a>
            </div>
        </div>
    </div>

    <!-- Active Tournaments -->
    <div class="py-20 bg-white/50 backdrop-blur-3xl relative z-20" id="tournaments">
        <div class="container mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-slate-800">Active Tournaments</h2>
                    <p class="text-slate-500 mt-2">Latest competitions happening right now</p>
                </div>
            </div>

            @if($tournaments->isEmpty())
                <div class="p-12 text-center bg-white/40 backdrop-blur-md rounded-[2rem] border border-white/50 border-dashed">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0V5.625a2.625 2.625 0 11-5.25 0v9.75m5.25 0V5.625a2.625 2.625 0 11-5.25 0v9.75" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">No Active Tournaments</h3>
                    <p class="text-slate-500">Check back later or start your own!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($tournaments as $tournament)
                        <a href="{{ route('tournaments.show', $tournament) }}" class="group relative bg-white rounded-[2rem] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.05)] border border-white/50 overflow-hidden hover:shadow-[0_20px_50px_-10px_rgba(100,50,250,0.15)] transition-all duration-300 hover:-translate-y-2">
                            <!-- Image -->
                            <div class="h-56 w-full overflow-hidden relative">
                                @if($tournament->cover_image)
                                    <img src="{{ asset('storage/' . $tournament->cover_image) }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-primary to-purple-700 group-hover:scale-110 transition duration-700"></div>
                                    <div class="absolute inset-0 flex items-center justify-center text-white/90">
                                        <svg class="w-16 h-16 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0V5.625a2.625 2.625 0 11-5.25 0v9.75m5.25 0V5.625a2.625 2.625 0 11-5.25 0v9.75" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>

                                <div class="absolute bottom-4 left-6 right-6">
                                     <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md border border-white/10 rounded-full text-[10px] font-bold uppercase tracking-wider text-white mb-2">
                                        {{ $tournament->status }}
                                    </span>
                                    <h3 class="text-2xl font-bold text-white leading-tight group-hover:text-primary-200 transition">{{ $tournament->name }}</h3>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6 pt-6">
                                <p class="text-slate-500 text-sm line-clamp-2 mb-6 leading-relaxed">
                                    {{ $tournament->description ?? 'Join us for an exciting display of skill and technique.' }}
                                </p>

                                <div class="flex justify-between items-center border-t border-gray-50 pt-4">
                                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                         <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        {{ $tournament->created_at->format('M d, Y') }}
                                    </span>
                                    <span class="text-sm font-bold text-primary group-hover:translate-x-1 transition flex items-center gap-1">
                                        View Bracket <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Features Grid -->
    <div class="py-24 relative overflow-hidden">
        <div class="container mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="p-8 rounded-[2.5rem] bg-white border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 mb-6 group-hover:scale-110 transition duration-300">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Winning Heritage</h3>
                    <p class="text-slate-500 leading-relaxed">Rooted in the tradition of Ishikawa since 1942, modernized for today's champions in Ponorogo.</p>
                </div>

                <!-- Card 2 -->
                <div class="p-8 rounded-[2.5rem] bg-white border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
                    <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-500 mb-6 group-hover:scale-110 transition duration-300">
                         <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Instant Generation</h3>
                    <p class="text-slate-500 leading-relaxed">Forget manual drawings. Our binary tree algorithm generates balanced brackets in milliseconds.</p>
                </div>

                 <!-- Card 3 -->
                <div class="p-8 rounded-[2.5rem] bg-white border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
                    <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center text-green-500 mb-6 group-hover:scale-110 transition duration-300">
                         <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Error-Free Management</h3>
                    <p class="text-slate-500 leading-relaxed">Calculated logic eliminates bias and errors, ensuring a fair fight for every participant on the mat.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
