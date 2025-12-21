@if($matchesByRound->isEmpty())
    <div class="text-center py-20 bg-white rounded-3xl shadow-xl border border-gray-100">
        <div class="mb-4 text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0V5.625a2.625 2.625 0 11-5.25 0v9.75m5.25 0V5.625a2.625 2.625 0 11-5.25 0v9.75" />
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Bracket Not Ready</h3>

        <p class="text-gray-500">Add participants and generate the bracket to see the matchups.</p>
    </div>
@else
    <!-- Bracket Container -->
    <div class="relative min-h-[500px] flex justify-center py-12 overflow-x-auto">
        {{-- Flex container for Rounds --}}
        <div class="flex gap-16 px-8">
            @foreach($matchesByRound as $round => $matches)
                <div class="flex flex-col justify-around relative space-y-8 min-w-[260px]">

                    {{-- Round Title --}}
                    <div class="absolute -top-10 left-0 w-full text-center">
                        <span class="text-xs font-bold tracking-[0.2em] text-gray-400 uppercase">
                            @if($loop->last)
                                <span class="flex items-center gap-1 text-yellow-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                        <path fill-rule="evenodd" d="M5.166 2.621v.858c-1.035.148-2.059.33-3.071.543a.75.75 0 00-.584.859 6.753 6.753 0 006.138 5.6 6.73 6.73 0 002.743 1.346A6.707 6.707 0 019.279 15H8.54c-1.036 0-1.875.84-1.875 1.875V19.5h-.75a2.25 2.25 0 00-2.25 2.25c0 .414.336.75.75.75h15a.75.75 0 00.75-.75 2.25 2.25 0 00-2.25-2.25h-.75v-2.625c0-1.036-.84-1.875-1.875-1.875h-.739a6.706 6.706 0 01-1.112-3.173 6.73 6.73 0 002.743-1.347 6.753 6.753 0 006.139-5.6.75.75 0 00-.585-.858 47.077 47.077 0 00-3.07-.543V2.62a.75.75 0 00-.658-.744 49.22 49.22 0 00-6.093-.377c-2.063 0-4.096.128-6.093.377a.75.75 0 00-.657.744zm0 2.629c0 1.196.312 2.32.857 3.294A5.266 5.266 0 013.16 5.337a45.6 45.6 0 012.006-.348zm13.668 8.04c-.455 1.637-1.682 2.944-3.235 3.518V19.5h3.235v-6.21zM7.16 19.5h3.235v-2.625c-1.553-.574-2.78-1.88-3.235-3.518H7.16v6.143zM16.54 9.554A5.266 5.266 0 0118.835 5.29c.71.112 1.396.228 2.005.348a6.766 6.766 0 00-.857 3.294v.62z" clip-rule="evenodd" />
                                    </svg>
                                    Champion
                                </span>
                            @elseif($loop->iteration == $loop->count - 1)
                                Finals
                            @elseif($loop->iteration == $loop->count - 2)
                                Semi-Finals
                            @else
                                Round {{ $round }}
                            @endif
                        </span>
                    </div>

                    @foreach($matches as $match)
                        {{-- Match Card --}}
                        <div class="relative bg-white rounded-lg shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] ring-1 ring-gray-100 hover:ring-primary/50 transition-all duration-300 group">

                            {{-- Connector Lines (Right side) --}}
                            @if(!$loop->parent->last)
                                <div class="absolute top-1/2 -right-8 w-8 h-px bg-gray-200 group-hover:bg-primary/30 transition-colors"></div>
                            @endif

                            {{-- Match Content --}}
                            <div class="py-3 px-4">
                                {{-- Participant 1 --}}
                                <div class="flex justify-between items-center mb-3">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-xs text-gray-400 font-bold border border-gray-100">
                                            {{ $match->participant1?->seed ?? '-' }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold truncate {{ $match->winner_id == $match->participant_1_id ? 'text-gray-900' : ($match->winner_id && $match->winner_id != $match->participant_1_id ? 'text-gray-300 line-through decoration-gray-200' : 'text-gray-600') }}">
                                                {{ $match->participant1?->name ?? 'Bye' }}
                                            </span>
                                            @if($match->participant1)
                                                <span class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">{{ $match->participant1->affiliation }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($match->winner_id == $match->participant_1_id)
                                        <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    @endif
                                </div>

                                {{-- Divider --}}
                                <div class="h-px w-full bg-gradient-to-r from-transparent via-gray-100 to-transparent my-1"></div>

                                {{-- Participant 2 --}}
                                <div class="flex justify-between items-center mt-3">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-xs text-gray-400 font-bold border border-gray-100">
                                            {{ $match->participant2?->seed ?? '-' }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold truncate {{ $match->winner_id == $match->participant_2_id ? 'text-gray-900' : ($match->winner_id && $match->winner_id != $match->participant_2_id ? 'text-gray-300 line-through decoration-gray-200' : 'text-gray-600') }}">
                                                {{ $match->participant2?->name ?? 'Bye' }}
                                            </span>
                                            @if($match->participant2)
                                                <span class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">{{ $match->participant2->affiliation }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($match->winner_id == $match->participant_2_id)
                                        <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    @endif
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endif
