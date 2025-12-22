@extends('layouts.app')

@section('content')

    <div class="min-h-screen bg-gray-50/50">
        <!-- Hero Section -->
        <div class="relative w-full h-[500px] overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-gray-900/90 z-10"></div>

            @if($tournament->cover_image)
                <img src="{{ asset('storage/' . $tournament->cover_image) }}" alt="Cover"
                    class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
            @else
                <div class="w-full h-full bg-gradient-to-br from-primary to-purple-800 animate-gradient-xy"></div>
            @endif

            <div class="absolute bottom-0 left-0 w-full z-20 p-8 md:p-16">
                <div class="container mx-auto">
                    {{-- Status Badge --}}
                    <div class="mb-4">
                        <span
                            class="px-4 py-2 bg-white/20 backdrop-blur-md border border-white/10 text-white rounded-full text-xs font-bold tracking-wider uppercase shadow-sm">
                            {{ $tournament->status }}
                        </span>
                    </div>

                    <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight drop-shadow-md leading-tight">
                        {{ $tournament->name }}
                    </h1>

                    @if($tournament->description)
                        <p class="text-gray-200 text-lg md:text-xl max-w-2xl font-light leading-relaxed drop-shadow">
                            {{ $tournament->description }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 -mt-10 mb-20 relative z-30" x-data="publicMatchModal()">
            @include('partials.bracket')

            <!-- Read-Only Match Detail Modal -->
            <div x-show="isOpen" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog"
                aria-modal="true">
                <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="closeModal"></div>

                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                        <div x-show="isOpen" x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-200">

                            <div
                                class="bg-gradient-to-r from-primary to-purple-600 px-6 py-4 flex justify-between items-center">
                                <h3 class="text-lg font-bold text-white">Match Details</h3>
                                <button @click="closeModal" class="text-white/70 hover:text-white transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-6 h-6">
                                        <path fill-rule="evenodd"
                                            d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <div class="px-6 py-10">
                                <!-- Draw History -->
                                <template x-if="matchHistory && matchHistory.length > 0">
                                    <div class="mb-8 text-center bg-gray-50 rounded-xl p-4 mx-4">
                                        <h5 class="text-sm font-bold text-gray-400 uppercase tracking-wide mb-3">Draw
                                            History</h5>
                                        <div class="space-y-2">
                                            <template x-for="(history, index) in matchHistory" :key="index">
                                                <div class="flex justify-center gap-4 text-sm font-bold text-gray-600">
                                                    <span>Round <span x-text="index + 1"></span>:</span>
                                                    <span class="font-mono">
                                                        <span x-text="history.p1"></span> - <span
                                                            x-text="history.p2"></span>
                                                    </span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <!-- Matchup Display -->
                                <div class="flex flex-col md:flex-row items-center justify-center gap-8 md:gap-12">

                                    <!-- Player 1 -->
                                    <div class="flex flex-col items-center text-center w-full md:w-1/3 group">
                                        <template x-if="p1Image">
                                            <div class="relative mb-4">
                                                <div
                                                    class="absolute inset-0 bg-primary/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition duration-500">
                                                </div>
                                                <img :src="'/storage/' + p1Image"
                                                    class="w-24 h-24 md:w-32 md:h-32 rounded-full object-cover border-4 border-white shadow-lg relative z-10 transition transform group-hover:scale-105">
                                            </div>
                                        </template>
                                        <template x-if="!p1Image">
                                            <div
                                                class="w-24 h-24 md:w-32 md:h-32 rounded-full bg-gray-50 flex items-center justify-center text-3xl font-black text-gray-200 border-4 border-white shadow-inner mb-4">
                                                P1
                                            </div>
                                        </template>
                                        <h4 class="text-xl font-bold text-gray-900 leading-tight mb-1" x-text="p1Name"></h4>
                                        <p class="text-sm font-medium text-primary uppercase tracking-wider" x-show="p1Dojo"
                                            x-text="p1Dojo"></p>

                                        <div class="mt-4">
                                            <span class="text-4xl font-black text-gray-200"
                                                :class="{'text-primary': p1Score > p2Score, 'text-gray-300': p1Score <= p2Score}"
                                                x-text="p1Score"></span>
                                        </div>
                                    </div>

                                    <!-- VS -->
                                    <div class="flex flex-col items-center">
                                        <span class="text-xl font-black text-gray-200 italic">VS</span>
                                    </div>

                                    <!-- Player 2 -->
                                    <div class="flex flex-col items-center text-center w-full md:w-1/3 group">
                                        <template x-if="p2Image">
                                            <div class="relative mb-4">
                                                <div
                                                    class="absolute inset-0 bg-pink-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition duration-500">
                                                </div>
                                                <img :src="'/storage/' + p2Image"
                                                    class="w-24 h-24 md:w-32 md:h-32 rounded-full object-cover border-4 border-white shadow-lg relative z-10 transition transform group-hover:scale-105">
                                            </div>
                                        </template>
                                        <template x-if="!p2Image">
                                            <div
                                                class="w-24 h-24 md:w-32 md:h-32 rounded-full bg-gray-50 flex items-center justify-center text-3xl font-black text-gray-200 border-4 border-white shadow-inner mb-4">
                                                P2
                                            </div>
                                        </template>
                                        <h4 class="text-xl font-bold text-gray-900 leading-tight mb-1" x-text="p2Name"></h4>
                                        <p class="text-sm font-medium text-pink-500 uppercase tracking-wider"
                                            x-show="p2Dojo" x-text="p2Dojo"></p>

                                        <div class="mt-4">
                                            <span class="text-4xl font-black text-gray-200"
                                                :class="{'text-pink-500': p2Score > p1Score, 'text-gray-300': p2Score <= p1Score}"
                                                x-text="p2Score"></span>
                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function publicMatchModal() {
                return {
                    isOpen: false,
                    p1Name: '',
                    p2Name: '',
                    p1Image: '',
                    p1Dojo: '',
                    p2Image: '',
                    p2Dojo: '',
                    p1Score: 0,
                    p2Score: 0,
                    matchHistory: [],

                    openModal(match) {
                        this.p1Name = match.participant1 ? match.participant1.name : 'Bye';
                        this.p1Image = match.participant1 ? match.participant1.image_path : null;
                        this.p1Dojo = match.participant1 ? match.participant1.dojo : null;

                        this.p2Name = match.participant2 ? match.participant2.name : 'Bye';
                        this.p2Image = match.participant2 ? match.participant2.image_path : null;
                        this.p2Dojo = match.participant2 ? match.participant2.dojo : null;

                        this.p1Score = match.participant_1_score;
                        this.p2Score = match.participant_2_score;
                        this.matchHistory = match.score_history || [];
                        this.isOpen = true;
                    },

                    closeModal() {
                        this.isOpen = false;
                    }
                }
            }
        </script>
    </div>
@endsection