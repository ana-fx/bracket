@extends('layouts.admin')

@section('content')
<div class="w-full px-6 py-8">
    
    <!-- Header Section -->
    <div class="mb-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">{{ now()->format('l, F jS') }}</p>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight">Welcome back!</h1>
                <p class="text-gray-500 mt-2 font-medium">Here's what's happening with your tournaments today.</p>
            </div>
            <a href="{{ route('tournaments.create') }}" 
               class="group relative inline-flex items-center justify-center px-8 py-3 font-bold text-white transition-all duration-200 bg-gray-900 font-pj rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 hover:bg-gray-800">
                <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-gray-200"></span>
                <span class="relative flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 group-hover:rotate-90 transition-transform">
                        <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                    </svg>
                    Create Tournament
                </span>
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Card 1: Total Tournaments -->
        <div class="bg-white p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-purple-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400 text-sm font-bold uppercase tracking-wider">Tournaments</h3>
                    <div class="p-2 bg-purple-100 rounded-xl text-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black text-gray-900">{{ $totalTournaments }}</span>
                    <span class="text-sm font-medium text-gray-400">Total</span>
                </div>
                <div class="mt-4 flex items-center gap-2 text-sm">
                    <span class="flex h-2 w-2 rounded-full bg-green-500"></span>
                    <span class="font-bold text-gray-600">{{ $activeTournaments }} Active</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Participants -->
        <div class="bg-white p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-blue-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400 text-sm font-bold uppercase tracking-wider">Fighters</h3>
                    <div class="p-2 bg-blue-100 rounded-xl text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black text-gray-900">{{ $totalParticipants }}</span>
                </div>
                <div class="mt-4 text-sm font-medium text-gray-400">
                    Across all brackets
                </div>
            </div>
        </div>

        <!-- Card 3: Matches Progress -->
        <div class="bg-white p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-pink-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400 text-sm font-bold uppercase tracking-wider">Progress</h3>
                    <div class="p-2 bg-pink-100 rounded-xl text-pink-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mb-2">
                    <span class="text-4xl font-black text-gray-900">{{ $completionPercentage }}%</span>
                </div>
                
                <!-- Progress Bar -->
                <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                    <div class="bg-gradient-to-r from-pink-500 to-purple-500 h-2 rounded-full transition-all duration-1000" style="width: {{ $completionPercentage }}%"></div>
                </div>
                <p class="text-xs text-gray-400 font-bold">{{ $completedMatches }} / {{ $totalMatches }} Matches</p>
            </div>
        </div>

        <!-- Card 4: Upcoming -->
        <div class="bg-white p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-yellow-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-400 text-sm font-bold uppercase tracking-wider">Upcoming</h3>
                    <div class="p-2 bg-yellow-100 rounded-xl text-yellow-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black text-gray-900">{{ $upcomingTournaments }}</span>
                </div>
                <div class="mt-4 text-sm font-medium text-gray-400">
                    Scheduled events
                </div>
            </div>
        </div>
    </div>

    <!-- Main Section: Tournament List & Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Tournaments List (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Active Tournaments</h2>
                <a href="#" class="text-sm font-bold text-primary hover:text-purple-700 transition">View All</a>
            </div>

            @if($tournaments->isEmpty())
                <div class="bg-white rounded-3xl border border-dashed border-gray-300 p-12 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">No tournaments yet</h3>
                    <p class="text-gray-500 mb-6">Create your first tournament to start managing brackets.</p>
                    <a href="{{ route('tournaments.create') }}" class="inline-flex px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-purple-600 transition shadow-lg shadow-purple-200">
                        Get Started
                    </a>
                </div>
            @else
                <div class="bg-white rounded-3xl shadow-[0_2px_20px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50/50 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-8 py-5 font-bold">Tournament</th>
                                    <th class="px-6 py-5 font-bold">Status</th>
                                    <th class="px-6 py-5 font-bold text-right pt-5 pr-8">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($tournaments->take(5) as $tournament)
                                    <tr class="hover:bg-gray-50 transition group">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-4">
                                                @if($tournament->cover_image)
                                                    <img src="{{ asset('storage/' . $tournament->cover_image) }}" class="w-12 h-12 rounded-xl object-cover ring-2 ring-gray-100">
                                                @else
                                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-400 ring-2 ring-gray-100">
                                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-bold text-gray-900 group-hover:text-primary transition-colors cursor-pointer" onclick="window.location='{{ route('admin.tournaments.show', $tournament) }}'">
                                                        {{ $tournament->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-400 font-medium mt-0.5">{{ $tournament->participants->count() }} Participants</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            @if($tournament->status == 'active')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span>
                                                    Live
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
                                                    Draft
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-5 text-right pr-8">
                                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="{{ route('admin.tournaments.show', $tournament) }}" class="p-2 text-gray-400 hover:text-primary hover:bg-purple-50 rounded-lg transition" title="View Bracket">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                                </a>
                                                <a href="{{ route('tournaments.participants', $tournament) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Participants">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                                </a>
                                                <a href="{{ route('tournaments.edit', $tournament) }}" class="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition" title="Settings">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($tournaments->count() > 5)
                        <div class="px-8 py-4 border-t border-gray-100 bg-gray-50 text-center">
                            <a href="#" class="text-sm font-bold text-gray-500 hover:text-gray-800 transition">View all {{ $tournaments->count() }} tournaments</a>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Right: Recent Activity / Sidebar -->
        <div class="space-y-6">
            <h2 class="text-xl font-bold text-gray-900">Recent Victories</h2>
            
            <div class="bg-white rounded-3xl shadow-[0_2px_20px_rgb(0,0,0,0.04)] border border-gray-100 p-6">
                @if($recentWinners->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-8">No recent matches played.</p>
                @else
                    <div class="space-y-6">
                        @foreach($recentWinners as $match)
                            <div class="flex items-start gap-4 pb-6 border-b border-gray-50 last:border-0 last:pb-0 relative">
                                <!-- Winner Avatar -->
                                <div class="relative">
                                    @if($match->winner->image_path)
                                        <img src="{{ asset('storage/' . $match->winner->image_path) }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-yellow-100">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center text-xs font-bold ring-2 ring-yellow-100">
                                            {{ substr($match->winner->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <div class="absolute -bottom-1 -right-1 bg-yellow-400 text-white text-[10px] p-0.5 rounded-full border border-white">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    </div>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-900">
                                        <span class="font-bold">{{ $match->winner->name }}</span> won a match against <span class="text-gray-500">{{ $match->participant1_id == $match->winner_id ? ($match->participant2 ? $match->participant2->name : 'Bye') : ($match->participant1 ? $match->participant1->name : 'Bye') }}</span>
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-primary font-bold bg-primary/5 px-2 py-0.5 rounded">{{ $match->tournament->name }}</span>
                                        <span class="text-xs text-gray-300">•</span>
                                        <span class="text-xs text-gray-400">{{ $match->updated_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Quick Tips or Promo -->
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl p-6 text-white shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                
                <h3 class="font-bold text-lg mb-2 relative z-10">Pro Tip</h3>
                <p class="text-indigo-100 text-sm mb-4 relative z-10 leading-relaxed">
                    Did you know? You can randomize seeds before generating the bracket to mix up the competition!
                </p>
                <div class="relative z-10">
                    <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-lg text-xs font-bold border border-white/10">Bracket Management</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
