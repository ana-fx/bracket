@extends('layouts.admin')


@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
            <p class="text-gray-500">Manage tournaments and participants</p>
        </div>
        <a href="{{ route('tournaments.create') }}" class="bg-primary hover:bg-purple-500 text-white px-6 py-2 rounded-xl font-bold shadow-md transition">
            + New Tournament
        </a>

    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 font-medium">Active Tournaments</h3>
                <span class="bg-primary/10 text-primary p-2 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $tournaments->count() }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
             <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 font-medium">Total Participants</h3>
                <span class="bg-secondary/10 text-secondary p-2 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $totalParticipants }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
             <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 font-medium">Completed Matches</h3>
                <span class="bg-accent/10 text-teal-600 p-2 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $completedMatches }}</p>
        </div>
    </div>

    <!-- Recent Activity Placeholder -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Your Tournaments</h3>
        </div>

        @if($tournaments->isEmpty())
            <div class="p-12 text-center text-gray-500">
                No tournaments found. Create one to get started!
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-sm uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold">Name</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Created</th>
                            <th class="px-6 py-4 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($tournaments as $tournament)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($tournament->cover_image)
                                            <img src="{{ asset('storage/' . $tournament->cover_image) }}" class="w-10 h-10 rounded-lg object-cover bg-gray-200">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center text-gray-500">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0V5.625a2.625 2.625 0 11-5.25 0v9.75m5.25 0V5.625a2.625 2.625 0 11-5.25 0v9.75" /></svg>
                                            </div>
                                        @endif
                                        <div class="font-bold text-gray-800">{{ $tournament->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-2 py-1 bg-{{ $tournament->status == 'active' ? 'green' : 'gray' }}-100 text-{{ $tournament->status == 'active' ? 'green' : 'gray' }}-700 rounded text-xs font-bold uppercase">
                                        {{ $tournament->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-sm">
                                    {{ $tournament->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.tournaments.show', $tournament) }}" class="text-primary hover:text-purple-700 font-bold text-sm mr-3">View</a>
                                    <a href="{{ route('tournaments.participants', $tournament) }}" class="text-gray-400 hover:text-gray-600 text-sm mr-3">Participants</a>
                                    <a href="{{ route('tournaments.edit', $tournament) }}" class="text-gray-400 hover:text-blue-600 text-sm" title="Edit Settings">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.11v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.11V6.893c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
