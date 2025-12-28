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
    <!-- Bracket Container (Scrollable) -->
    <div class="overflow-x-auto py-12 min-h-[500px]" id="bracket-container">

        <!-- Wrapper for SVG and Rounds (Expands with content) -->
        <div class="relative inline-flex min-w-full" id="bracket-wrapper">

            {{-- SVG Layer for Connectivity --}}
            <svg class="absolute inset-0 w-full h-full pointer-events-none z-0" id="connector-svg">
                <!-- Lines will be injected here via JS -->
            </svg>

            {{-- Flex container for Rounds --}}
            <div class="flex flex-nowrap gap-16 px-8 relative z-10 m-auto">
                @foreach($matchesByRound as $round => $matches)
                    <div class="flex flex-col justify-around relative space-y-8 min-w-[260px]">

                        {{-- Round Title --}}
                        <div class="absolute -top-10 left-0 w-full text-center">
                            <span class="text-xs font-bold tracking-[0.2em] text-gray-400 uppercase">
                                @if($loop->last)
                                    <span class="flex items-center gap-2 text-yellow-500 justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                        </svg>
                                        Finals
                                    </span>
                                @elseif($loop->iteration == $loop->count - 1)
                                    <span class="flex items-center gap-2 text-indigo-400 justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd" />
                                        </svg>
                                        Semi-Finals
                                    </span>
                                @elseif($loop->iteration == $loop->count - 2)
                                    <span class="flex items-center gap-2 text-blue-400 justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6zm14.25 6a2.25 2.25 0 01-2.25 2.25H9a2.25 2.25 0 01-2.25-2.25v-2.25a2.25 2.25 0 012.25-2.25h6a2.25 2.25 0 012.25 2.25v2.25z" clip-rule="evenodd" />
                                        </svg>
                                        Quarter-Finals
                                    </span>
                                @else
                                    <span class="flex items-center gap-2 text-gray-400 justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                            <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                            <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" />
                                        </svg>
                                        Round {{ $round }}
                                    </span>
                                @endif
                            </span>
                        </div>

                        @foreach($matches as $match)
                            {{-- Match Wrapper (Split Layout) --}}
                            <div class="relative flex flex-col justify-center gap-6 group match-node"
                                 id="match-{{ $match->id }}"
                                 data-match-id="{{ $match->id }}"
                                 data-next-match="{{ $match->next_match_id }}"
                                 data-participant1-id="{{ $match->participant_1_id }}"
                                 data-participant2-id="{{ $match->participant_2_id }}"
                                 data-winner-id="{{ $match->winner_id }}">

                                    {{-- Connector Bracket Logic Removed --}}

                                    {{-- P1 Card --}}
                                    <div id="match-{{ $match->id }}-p1" class="relative bg-white rounded-xl shadow-sm ring-1 ring-gray-100 flex items-stretch overflow-hidden cursor-pointer hover:ring-primary/50 hover:shadow-md transition-all h-[72px]"
                                         @click='openModal(@json($match))'>

                                        <!-- Sidebar (Image) -->
                                        <div class="w-16 flex-shrink-0 bg-red-600 flex items-center justify-center border-r border-red-700">
                                            @if($match->participant1 && $match->participant1->image_path)
                                                <img src="{{ asset('storage/' . $match->participant1->image_path) }}"
                                                     alt="{{ $match->participant1->name }}"
                                                     crossorigin="anonymous"
                                                     onerror="this.style.display='none'; this.setAttribute('data-img-error', 'true');"
                                                     class="w-10 h-10 rounded-full object-cover shadow-sm ring-2 ring-white/20">
                                            @else
                                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm">
                                                    <span class="text-xs text-red-600 font-bold">{{ $match->participant1?->seed ?? '-' }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Content (Text) -->
                                        <div class="flex-1 flex items-center justify-between px-3 py-2 min-w-0">
                                            <div class="flex flex-col min-w-0 mr-2">
                                                <span class="text-sm font-bold truncate {{ $match->winner_id && $match->winner_id == $match->participant_1_id ? 'text-gray-900' : ($match->winner_id && $match->winner_id != $match->participant_1_id ? 'text-gray-400 decoration-gray-300' : 'text-gray-700') }}">
                                                    {{ $match->participant1?->name ?? 'Bye' }}
                                                </span>
                                                @if($match->participant1 && $match->participant1->dojo)
                                                    <span class="text-sm text-gray-400 font-bold truncate">{{ $match->participant1->dojo }}</span>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-2">
                                                @if(isset($isAdmin) && $isAdmin && $match->participant1)
                                                    <button @click.stop="$dispatch('open-participant-modal', {{ json_encode($match->participant1) }})" class="text-gray-300 hover:text-primary transition-colors p-1 rounded-md hover:bg-purple-50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                                        </svg>
                                                    </button>
                                                @endif

                                                @if($match->winner_id && $match->winner_id == $match->participant_1_id)
                                                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                @endif

                                                @if($match->participant_1_score > 0 || $match->participant_2_score > 0 || !empty($match->score_history))
                                                    @if(!empty($match->score_history))
                                                        <div class="flex flex-col text-[10px] text-gray-400 font-mono text-right">
                                                            @foreach($match->score_history as $h)
                                                                <span>{{ $h['p1'] }}</span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    <span class="font-bold text-lg ml-1 {{ $match->winner_id == $match->participant_1_id ? 'text-green-600' : 'text-gray-300' }}">
                                                        {{ $match->participant_1_score }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- P2 Card --}}
                                    <div id="match-{{ $match->id }}-p2" class="relative bg-white rounded-xl shadow-sm ring-1 ring-gray-100 flex items-stretch overflow-hidden cursor-pointer hover:ring-primary/50 hover:shadow-md transition-all h-[72px]"
                                         @click='openModal(@json($match))'>

                                        <!-- Sidebar (Image) -->
                                        <div class="w-16 flex-shrink-0 bg-blue-600 flex items-center justify-center border-r border-blue-700">
                                            @if($match->participant2 && $match->participant2->image_path)
                                                <img src="{{ asset('storage/' . $match->participant2->image_path) }}"
                                                     alt="{{ $match->participant2->name }}"
                                                     crossorigin="anonymous"
                                                     onerror="this.style.display='none'; this.setAttribute('data-img-error', 'true');"
                                                     class="w-10 h-10 rounded-full object-cover shadow-sm ring-2 ring-white/20">
                                            @else
                                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm">
                                                    <span class="text-xs text-blue-600 font-bold">{{ $match->participant2?->seed ?? '-' }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Content (Text) -->
                                        <div class="flex-1 flex items-center justify-between px-3 py-2 min-w-0">
                                            <div class="flex flex-col min-w-0 mr-2">
                                                <span class="text-sm font-bold truncate {{ $match->winner_id && $match->winner_id == $match->participant_2_id ? 'text-gray-900' : ($match->winner_id && $match->winner_id != $match->participant_2_id ? 'text-gray-400 decoration-gray-300' : 'text-gray-700') }}">
                                                    {{ $match->participant2?->name ?? 'Bye' }}
                                                </span>
                                                @if($match->participant2 && $match->participant2->dojo)
                                                    <span class="text-sm text-gray-400 font-bold truncate">{{ $match->participant2->dojo }}</span>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-2">
                                                @if(isset($isAdmin) && $isAdmin && $match->participant2)
                                                    <button @click.stop="$dispatch('open-participant-modal', {{ json_encode($match->participant2) }})" class="text-gray-300 hover:text-primary transition-colors p-1 rounded-md hover:bg-purple-50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                                        </svg>
                                                    </button>
                                                @endif

                                                @if($match->winner_id && $match->winner_id == $match->participant_2_id)
                                                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                @endif

                                                @if($match->participant_1_score > 0 || $match->participant_2_score > 0 || !empty($match->score_history))
                                                    @if(!empty($match->score_history))
                                                        <div class="flex flex-col text-[10px] text-gray-400 font-mono text-right">
                                                            @foreach($match->score_history as $h)
                                                                <span>{{ $h['p2'] }}</span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    <span class="font-bold text-lg ml-1 {{ $match->winner_id == $match->participant_2_id ? 'text-green-600' : 'text-gray-300' }}">
                                                        {{ $match->participant_2_score }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Line Drawing Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
             drawConnectors();
             window.addEventListener('resize', drawConnectors);

             // Redraw when container size changes
             const container = document.getElementById('bracket-container');
             if (container && window.ResizeObserver) {
                 new ResizeObserver(() => drawConnectors()).observe(container);
             }

             // Also observe the wrapper for content size changes
             const wrapper = document.getElementById('bracket-wrapper');
             if (wrapper && window.ResizeObserver) {
                 new ResizeObserver(() => drawConnectors()).observe(wrapper);
             }

             setTimeout(drawConnectors, 500);

             // Redraw for print to ensure alignment
             window.addEventListener('beforeprint', () => {
                 setTimeout(drawConnectors, 10);
                 setTimeout(drawConnectors, 500); // Safety double-check
             });
        });

        function drawConnectors() {
            const svg = document.getElementById('connector-svg');
            const wrapper = document.getElementById('bracket-wrapper');
            if (!svg || !wrapper) return;

            // Clear existing lines
            svg.innerHTML = '';

            const matches = document.querySelectorAll('.match-node');
            const wrapperRect = wrapper.getBoundingClientRect(); // Use wrapper as reference

            matches.forEach(match => {
                const matchId = match.dataset.matchId;
                const nextMatchId = match.dataset.nextMatch;
                const winnerId = match.dataset.winnerId;
                const p1Id = match.dataset.participant1Id;
                const p2Id = match.dataset.participant2Id;

                if (!nextMatchId) return;

                const nextMatch = document.getElementById('match-' + nextMatchId);
                if (!nextMatch) return;

                // Elements
                const p1Card = document.getElementById(`match-${matchId}-p1`);
                const p2Card = document.getElementById(`match-${matchId}-p2`);

                if (!p1Card || !p2Card) return;

                // Coordinates relative to the WRAPPER (which scales with content)
                const p1Rect = p1Card.getBoundingClientRect();
                const p2Rect = p2Card.getBoundingClientRect();
                const nextRect = nextMatch.getBoundingClientRect();

                // Calculate Points
                // We subtract wrapperRect.left/top directly.
                // Since wrapper and svg move together, lines stay anchored.
                const p1RightX = p1Rect.right - wrapperRect.left;
                const p1Y = p1Rect.top + p1Rect.height / 2 - wrapperRect.top;

                const p2RightX = p2Rect.right - wrapperRect.left;
                const p2Y = p2Rect.top + p2Rect.height / 2 - wrapperRect.top;

                const nextLeftX = nextRect.left - wrapperRect.left;
                const nextY = nextRect.top + nextRect.height / 2 - wrapperRect.top;

                // Fork Logic
                const forkX = p1RightX + 20;

                // Highlight Logic
                const safeWinnerId = (winnerId || '').toLowerCase();
                const safeP1Id = (p1Id || '').toLowerCase();
                const safeP2Id = (p2Id || '').toLowerCase();

                const isP1Winner = safeWinnerId && safeWinnerId == safeP1Id;
                const isP2Winner = safeWinnerId && safeWinnerId == safeP2Id;
                const defaultColor = '#E5E7EB';
                const highlightColor = '#A78BFA';
                const width = '2';

                // Path 1
                const d1 = `M ${p1RightX} ${p1Y} L ${forkX} ${p1Y}`;
                createPath(svg, d1, isP1Winner ? highlightColor : defaultColor, width);

                // Path 2
                const d2 = `M ${p2RightX} ${p2Y} L ${forkX} ${p2Y}`;
                createPath(svg, d2, isP2Winner ? highlightColor : defaultColor, width);

                // Path 3
                const topY = Math.min(p1Y, p2Y);
                const bottomY = Math.max(p1Y, p2Y);
                const midY = (p1Y + p2Y) / 2;

                const d3Top = `M ${forkX} ${p1Y} L ${forkX} ${midY}`;
                createPath(svg, d3Top, isP1Winner ? highlightColor : defaultColor, width);

                const d3Bottom = `M ${forkX} ${p2Y} L ${forkX} ${midY}`;
                createPath(svg, d3Bottom, isP2Winner ? highlightColor : defaultColor, width);

                // Path 4
                const stepX = nextLeftX - 20;
                const d4 = `M ${forkX} ${midY} L ${stepX} ${midY} L ${stepX} ${nextY} L ${nextLeftX} ${nextY}`;
                createPath(svg, d4, (isP1Winner || isP2Winner) ? highlightColor : defaultColor, width);
            });
        }

        function createPath(svg, d, color, width) {
            const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            path.setAttribute('d', d);
            path.setAttribute('stroke', color);
            path.setAttribute('stroke-width', width);
            path.setAttribute('fill', 'none');
            svg.appendChild(path);
        }
    </script>
@endif
