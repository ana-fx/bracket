@extends('layouts.admin')

@section('content')

    <div class="min-h-screen bg-gray-50/50">
        <!-- Admin Header -->
        <div class="bg-white border-b border-gray-200">
            <div class="container mx-auto px-6 py-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <span
                            class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-bold tracking-wider uppercase mb-2 inline-block">
                            {{ $tournament->status }}
                        </span>
                        <h1 class="text-3xl font-black text-gray-900 leading-tight">
                            {{ $tournament->name }} <span class="text-gray-400 font-light">| Admin View</span>
                        </h1>
                    </div>

                    <div class="flex gap-3">

                        <a href="{{ route('tournaments.edit', $tournament) }}"
                            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50">
                            Settings
                        </a>

                        <button @click="$dispatch('open-add-participant-modal')"
                            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Add Participant
                        </button>

                        @if(!$matchesByRound->isEmpty())
                            <button type="button" onclick="runGacha(this)"
                                class="px-4 py-2 bg-primary text-white font-bold rounded-lg hover:bg-purple-600 shadow-sm flex items-center gap-2">
                                <svg id="gacha-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                <svg id="gacha-spinner" class="animate-spin h-5 w-5 text-white hidden"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
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
            <div x-show="isOpen" style="display: none;" class="relative z-50" aria-labelledby="slide-over-title"
                role="dialog" aria-modal="true">
                <!-- Background backdrop -->
                <div x-show="isOpen" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-900/75 transition-opacity" @click="closeModal"></div>

                <div class="fixed inset-0 overflow-hidden" pointer-events="none">
                    <div class="absolute inset-0 overflow-hidden">
                        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">

                            <div x-show="isOpen"
                                x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                                x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                                class="pointer-events-auto w-screen max-w-md" @click.stop="">

                                <form @submit.prevent="submitScore"
                                    class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                                    @csrf
                                    @method('PUT')

                                    <div class="px-4 py-6 sm:px-6 bg-primary">
                                        <div class="flex items-start justify-between">
                                            <h2 class="text-xl font-bold leading-6 text-white" id="slide-over-title">Update
                                                Match Score</h2>
                                            <div class="ml-3 flex h-7 items-center">
                                                <button type="button"
                                                    class="relative rounded-md text-white/70 hover:text-white focus:outline-none"
                                                    @click="closeModal">
                                                    <span class="absolute -inset-2.5"></span>
                                                    <span class="sr-only">Close panel</span>
                                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="relative flex-1 px-4 py-6 sm:px-6">
                                        <!-- Content -->
                                        <div class="mb-8">
                                            <div class="mb-2 text-xs font-bold uppercase tracking-wide text-gray-400">Match
                                                Layout</div>

                                            <template x-if="matchHistory && matchHistory.length > 0">
                                                <div class="mb-4">
                                                    <p class="text-xs font-bold text-gray-500 mb-2">Match History (Draws)
                                                    </p>
                                                    <template x-for="(history, index) in matchHistory" :key="index">
                                                        <div
                                                            class="flex justify-between items-center bg-gray-50 px-3 py-2 rounded-lg mb-2 text-sm text-gray-600 border border-gray-100">
                                                            <span>Draw <span x-text="index + 1"></span></span>
                                                            <span class="font-mono font-bold">
                                                                <span x-text="history.p1"></span> - <span
                                                                    x-text="history.p2"></span>
                                                            </span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            <div
                                                class="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-between">
                                                <div class="text-center w-1/3 flex flex-col items-center">
                                                    <template x-if="p1Image">
                                                        <img :src="'/storage/' + p1Image"
                                                            class="w-16 h-16 mx-auto rounded-full object-cover shadow-sm mb-3 border border-gray-100">
                                                    </template>
                                                    <template x-if="!p1Image">
                                                        <div
                                                            class="w-16 h-16 mx-auto bg-white rounded-full shadow-sm flex items-center justify-center text-xl font-bold text-primary mb-3 border border-gray-100">
                                                            P1</div>
                                                    </template>
                                                    <p class="text-sm font-bold text-gray-900 truncate w-full"
                                                        x-text="p1Name"></p>
                                                    <p class="text-xs text-gray-500 truncate w-full" x-show="p1Dojo"
                                                        x-text="p1Dojo"></p>
                                                </div>
                                                <div class="text-gray-300 font-bold text-2xl">VS</div>
                                                <div class="text-center w-1/3 flex flex-col items-center">
                                                    <template x-if="p2Image">
                                                        <img :src="'/storage/' + p2Image"
                                                            class="w-16 h-16 mx-auto rounded-full object-cover shadow-sm mb-3 border border-gray-100">
                                                    </template>
                                                    <template x-if="!p2Image">
                                                        <div
                                                            class="w-16 h-16 mx-auto bg-white rounded-full shadow-sm flex items-center justify-center text-xl font-bold text-pink-500 mb-3 border border-gray-100">
                                                            P2</div>
                                                    </template>
                                                    <p class="text-sm font-bold text-gray-900 truncate w-full"
                                                        x-text="p2Name"></p>
                                                    <p class="text-xs text-gray-500 truncate w-full" x-show="p2Dojo"
                                                        x-text="p2Dojo"></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium leading-6 text-gray-900 mb-2">Participant 1
                                                Score</label>
                                            <div class="relative rounded-md shadow-sm">
                                                <input type="number" name="participant_1_score" x-model="p1Score" min="0"
                                                    class="block w-full rounded-xl border-0 py-4 px-4 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-lg sm:leading-6 font-bold"
                                                    placeholder="0">
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium leading-6 text-gray-900 mb-2">Participant 2
                                                Score</label>
                                            <div class="relative rounded-md shadow-sm">
                                                <input type="number" name="participant_2_score" x-model="p2Score" min="0"
                                                    class="block w-full rounded-xl border-0 py-4 px-4 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-lg sm:leading-6 font-bold"
                                                    placeholder="0">
                                            </div>
                                        </div>

                                    </div>

                                    <div
                                        class="flex flex-shrink-0 justify-end px-4 py-4 bg-gray-50 border-t border-gray-100">
                                        <button type="button"
                                            class="rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 mr-3"
                                            @click="closeModal">Cancel</button>
                                        <button type="submit"
                                            class="inline-flex justify-center rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-purple-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Save
                                            Results</button>
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
                    p1Image: '',
                    p1Dojo: '',
                    p2Image: '',
                    p2Dojo: '',
                    p1Score: 0,
                    p2Score: 0,
                    matchHistory: [],
                    actionUrl: '',

                    async submitScore() {
                        try {
                            const response = await fetch(this.actionUrl, {
                                method: 'POST', // Actually PUT via _method
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    _method: 'PUT',
                                    participant_1_score: this.p1Score,
                                    participant_2_score: this.p2Score
                                })
                            });

                            const contentType = response.headers.get("content-type");
                            let data;
                            if (contentType && contentType.indexOf("application/json") !== -1) {
                                data = await response.json();
                            } else {
                                // If not JSON, it's likely an error page (500)
                                const text = await response.text();
                                console.error("Non-JSON Response:", text);
                                throw new Error(`Server returned standard error (${response.status}). Check console for details.`);
                            }

                            if (!response.ok) {
                                if (response.status === 422) {
                                    // Validation Error
                                    const errors = Object.values(data.errors).flat().join('\n');
                                    window.dispatchEvent(new CustomEvent('notify', {
                                        detail: {
                                            message: data.message || 'Validation Failed',
                                            type: 'error',
                                            description: errors
                                        }
                                    }));
                                } else {
                                    throw new Error(data.message || 'Something went wrong');
                                }
                                return;
                            }

                            // Success
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: {
                                    message: 'Match Updated',
                                    type: 'success'
                                }
                            }));

                            this.closeModal();
                            // Reload page to reflect bracket changes (unless we want to update DOM dynamically, but full reload is safer for bracket status)
                            setTimeout(() => window.location.reload(), 500);

                        } catch (error) {
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: {
                                    message: 'Error',
                                    type: 'error',
                                    description: error.message
                                }
                            }));
                        }
                    },

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
                        this.actionUrl = `/admin/matches/${match.id}`;
                        this.isOpen = true;
                    },

                    closeModal() {
                        this.isOpen = false;
                    }
                }
            }
        </script>

        <div x-data="participantEditModal" class="relative z-[60]" aria-labelledby="modal-title" role="dialog"
            aria-modal="true" x-show="isOpen" style="display: none;">

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-900/75 transition-opacity" x-show="isOpen"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" aria-hidden="true"
                @click="closeModal"></div>

            <div class="fixed inset-0 z-[60] w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                        x-show="isOpen" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                        <form @submit.prevent="submitParticipant">
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                        <h3 class="text-xl font-bold leading-6 text-gray-900" id="modal-title">Edit
                                            Participant</h3>
                                        <div class="mt-4 space-y-4">

                                            <!-- Name -->
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700">Name</label>
                                                <input type="text" x-model="name"
                                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm font-bold"
                                                    required>
                                            </div>

                                            <!-- Dojo -->
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700">Dojo / Club</label>
                                                <input type="text" x-model="dojo"
                                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                            </div>

                                            <!-- Image -->
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700">Image</label>
                                                <div class="mt-2 flex items-center gap-x-3">
                                                    <template x-if="currentImage && !imageFile">
                                                        <img :src="'/storage/' + currentImage"
                                                            class="h-12 w-12 rounded-full object-cover bg-gray-50">
                                                    </template>
                                                    <input type="file" @change="imageFile = $event.target.files[0]"
                                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-purple-600">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit"
                                    class="inline-flex w-full justify-center rounded-lg bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-500 sm:ml-3 sm:w-auto">Save
                                    Changes</button>
                                <button type="button" @click="closeModal"
                                    class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('participantEditModal', () => ({
                    isOpen: false,
                    participantId: null,
                    name: '',
                    dojo: '',
                    imageFile: null,
                    currentImage: null,
                    actionUrl: '',

                    init() {
                        console.log('Participant Edit Modal Initialized');
                        window.addEventListener('open-participant-modal', (e) => {
                            console.log('Event received', e.detail);
                            this.openModal(e.detail);
                        });
                    },

                    openModal(participant) {
                        console.log('Opening modal for', participant);
                        this.participantId = participant.id;
                        this.name = participant.name;
                        this.dojo = participant.dojo || '';
                        this.currentImage = participant.image_path;
                        this.actionUrl = `/admin/tournaments/${participant.tournament_id}/participants/${participant.id}`;
                        this.imageFile = null;
                        this.isOpen = true;
                    },

                    closeModal() {
                        this.isOpen = false;
                        this.imageFile = null;
                    },

                    async submitParticipant() {
                        const formData = new FormData();
                        formData.append('_method', 'PUT');
                        formData.append('name', this.name);
                        formData.append('dojo', this.dojo);
                        if (this.imageFile) {
                            formData.append('image', this.imageFile);
                        }

                        try {
                            const response = await fetch(this.actionUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                },
                                body: formData
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                throw new Error(data.message || 'Update failed');
                            }

                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: { message: 'Participant Updated', type: 'success' }
                            }));
                            this.closeModal();

                            setTimeout(() => window.location.reload(), 500);

                        } catch (error) {
                            console.error(error);
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: { message: 'Error', type: 'error', description: error.message }
                            }));
                        }
                    }
                }));
            });
        </script>
@endsection
    <!-- Add Participant Modal -->
    <div x-data="participantAddModal" 
         class="relative z-[60]" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true" 
         x-show="isOpen" 
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/75 transition-opacity" 
             x-show="isOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             aria-hidden="true"
             @click="closeModal"></div>

        <div class="fixed inset-0 z-[60] w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                     x-show="isOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <form @submit.prevent="submitParticipant">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-xl font-bold leading-6 text-gray-900" id="modal-title">Add New Participant</h3>
                                    <p class="text-sm text-gray-500 mt-1">This will add a new participant. You may need to shuffle or regenerate the bracket.</p>
                                    <div class="mt-4 space-y-4">
                                        
                                        <!-- Name -->
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700">Name</label>
                                            <input type="text" x-model="name" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm font-bold" required placeholder="Fighter Name">
                                        </div>

                                        <!-- Dojo -->
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700">Dojo / Club</label>
                                            <input type="text" x-model="dojo" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm" placeholder="Club Name">
                                        </div>

                                        <!-- Image -->
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700">Image</label>
                                            <div class="mt-2 flex items-center gap-x-3">
                                                <input type="file" @change="imageFile = $event.target.files[0]" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-purple-600">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-500 sm:ml-3 sm:w-auto">Add Participant</button>
                            <button type="button" @click="closeModal" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('participantAddModal', () => ({
                isOpen: false,
                name: '',
                dojo: '',
                imageFile: null,
                actionUrl: '{{ route('tournaments.participants.store', $tournament) }}',

                init() {
                    window.addEventListener('open-add-participant-modal', () => {
                         this.openModal();
                    });
                },

                openModal() {
                    this.name = '';
                    this.dojo = '';
                    this.imageFile = null;
                    this.isOpen = true;
                },

                closeModal() {
                    this.isOpen = false;
                    this.imageFile = null;
                },

                async submitParticipant() {
                    const formData = new FormData();
                    formData.append('name', this.name);
                    formData.append('dojo', this.dojo); 
                    if (this.imageFile) {
                        formData.append('image', this.imageFile);
                    }

                    try {
                        const response = await fetch(this.actionUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Creation failed');
                        }

                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: { message: 'Participant Added', type: 'success' }
                        }));
                        this.closeModal();

                         setTimeout(() => window.location.reload(), 500);

                    } catch (error) {
                         console.error(error);
                         window.dispatchEvent(new CustomEvent('notify', {
                            detail: { message: 'Error', type: 'error', description: error.message }
                        }));
                    }
                }
            }));
        });
    </script>
