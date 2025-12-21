@extends('layouts.admin')

@section('content')

<div class="min-h-screen bg-gray-50/50">
    <!-- Admin Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="container mx-auto px-6 py-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                     <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-bold tracking-wider uppercase mb-2 inline-block">
                        {{ $tournament->status }}
                    </span>
                    <h1 class="text-3xl font-black text-gray-900 leading-tight">
                        {{ $tournament->name }} <span class="text-gray-400 font-light">| Admin View</span>
                    </h1>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('tournaments.participants', $tournament) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50">
                        Participants
                    </a>
                    <a href="{{ route('tournaments.edit', $tournament) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50">
                        Settings
                    </a>

                    @if(!$matchesByRound->isEmpty())
                        <button type="button" onclick="runGacha(this)" class="px-4 py-2 bg-primary text-white font-bold rounded-lg hover:bg-purple-600 shadow-sm flex items-center gap-2">
                             <svg id="gacha-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            <svg id="gacha-spinner" class="animate-spin h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span id="gacha-text">Shuffle Bracket</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(!$matchesByRound->isEmpty())
        <script>
            function runGacha(btn) {
                // UI Loading State
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                document.getElementById('gacha-icon').classList.add('hidden');
                document.getElementById('gacha-spinner').classList.remove('hidden');
                document.getElementById('gacha-text').innerText = 'Shuffling...';

                // Call Backend
                fetch('{{ route('tournaments.randomize', $tournament) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                })
                .catch(error => {
                    alert('Error shuffling!');
                    btn.disabled = false;
                    window.location.reload();
                });
            }
        </script>
    @endif

    <div class="container mx-auto px-4 py-8" x-data="matchModal()">
        @include('partials.bracket', ['isAdmin' => true])

        <!-- Match Score Drawer -->
        <div x-show="isOpen" style="display: none;" class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <!-- Background backdrop -->
            <div x-show="isOpen"
                 x-transition:enter="ease-in-out duration-500"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-500"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/75 transition-opacity"
                 @click="closeModal"></div>

            <div class="fixed inset-0 overflow-hidden" pointer-events="none">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">

                        <div x-show="isOpen"
                             x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                             x-transition:enter-start="translate-x-full"
                             x-transition:enter-end="translate-x-0"
                             x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                             x-transition:leave-start="translate-x-0"
                             x-transition:leave-end="translate-x-full"
                             class="pointer-events-auto w-screen max-w-md"
                             @click.stop="">

                            <form :action="actionUrl" method="POST" class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                                @csrf
                                @method('PUT')

                                <div class="px-4 py-6 sm:px-6 bg-primary">
                                    <div class="flex items-start justify-between">
                                        <h2 class="text-xl font-bold leading-6 text-white" id="slide-over-title">Update Match Score</h2>
                                        <div class="ml-3 flex h-7 items-center">
                                            <button type="button" class="relative rounded-md text-white/70 hover:text-white focus:outline-none" @click="closeModal">
                                                <span class="absolute -inset-2.5"></span>
                                                <span class="sr-only">Close panel</span>
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-1">
                                         <p class="text-sm text-purple-100">
                                            Match Format: Best of {{ $tournament->best_of }} (First to {{ ceil($tournament->best_of / 2) }})
                                        </p>
                                    </div>
                                </div>

                                <div class="relative flex-1 px-4 py-6 sm:px-6">
                                    <!-- Content -->
                                    <div class="mb-8">
                                        <div class="mb-2 text-xs font-bold uppercase tracking-wide text-gray-400">Match Layout</div>
                                        <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-between">
                                             <div class="text-center w-1/3">
                                                <div class="w-16 h-16 mx-auto bg-white rounded-full shadow-sm flex items-center justify-center text-xl font-bold text-primary mb-3 border border-gray-100">P1</div>
                                                <p class="text-sm font-bold text-gray-900 truncate" x-text="p1Name"></p>
                                            </div>
                                            <div class="text-gray-300 font-bold text-2xl">VS</div>
                                             <div class="text-center w-1/3">
                                                <div class="w-16 h-16 mx-auto bg-white rounded-full shadow-sm flex items-center justify-center text-xl font-bold text-pink-500 mb-3 border border-gray-100">P2</div>
                                                <p class="text-sm font-bold text-gray-900 truncate" x-text="p2Name"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Participant 1 Score</label>
                                        <div class="relative rounded-md shadow-sm">
                                            <input type="number" name="participant_1_score" x-model="p1Score" min="0" class="block w-full rounded-xl border-0 py-4 px-4 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-lg sm:leading-6 font-bold" placeholder="0">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Participant 2 Score</label>
                                        <div class="relative rounded-md shadow-sm">
                                            <input type="number" name="participant_2_score" x-model="p2Score" min="0" class="block w-full rounded-xl border-0 py-4 px-4 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-lg sm:leading-6 font-bold" placeholder="0">
                                        </div>
                                    </div>

                                </div>

                                <div class="flex flex-shrink-0 justify-end px-4 py-4 bg-gray-50 border-t border-gray-100">
                                    <button type="button" class="rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 mr-3" @click="closeModal">Cancel</button>
                                    <button type="submit" class="inline-flex justify-center rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-purple-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Save Results</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function matchModal() {
            return {
                isOpen: false,
                p1Name: '',
                p2Name: '',
                p1Score: 0,
                p2Score: 0,
                actionUrl: '',

                openModal(match) {
                    this.p1Name = match.participant1 ? match.participant1.name : 'Bye';
                    this.p2Name = match.participant2 ? match.participant2.name : 'Bye';
                    this.p1Score = match.participant_1_score;
                    this.p2Score = match.participant_2_score;
                    this.actionUrl = `/matches/${match.id}`;
                    this.isOpen = true;
                },

                closeModal() {
                    this.isOpen = false;
                }
            }
        }
    </script>
@endsection
