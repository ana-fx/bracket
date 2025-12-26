@extends('layouts.admin')

@section('content')

    <div class="min-h-screen bg-gray-50/50">
        <!-- Admin Header -->
        <div class="bg-white border-b border-gray-200">
            <div class="w-full px-6 py-8">
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

                        <button onclick="window.print()" title="Print Bracket"
                            class="p-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm no-print">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.618 0-1.113-.497-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                            </svg>
                        </button>

                        <a href="{{ route('tournaments.edit', $tournament) }}" title="Settings"
                            class="p-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.581-.495.644-.869l.214-1.281Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </a>

                        @if(!$tournament->hasActiveMatches())
                            <a href="{{ route('tournaments.participants', $tournament) }}" title="Manage Participants"
                                class="p-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                            </a>

                            <button @click="$dispatch('open-add-participant-modal')" title="Add Participant"
                                class="p-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>

                            @if(!$matchesByRound->isEmpty())
                                <button type="button" onclick="runGacha(this)" title="Shuffle Bracket"
                                    class="p-2.5 bg-primary text-white rounded-xl hover:bg-purple-600 shadow-sm transition-all hover:shadow-md">
                                    <svg id="gacha-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
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
                                </button>
                            @endif
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
                    document.getElementById('gacha-spinner').classList.remove('hidden');

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

        <div class="w-full px-4 py-8" x-data="matchModal()">
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

        <div x-data="participantEditModal" class="relative" style="z-index: 9999;" aria-labelledby="modal-title"
            role="dialog" aria-modal="true" x-show="isOpen" style="display: none;">

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-900/75 transition-opacity" x-show="isOpen"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" aria-hidden="true"
                @click="closeModal"></div>

            <div class="fixed inset-0 w-screen overflow-y-auto" style="z-index: 9999;">
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

    @push('scripts')
        <style>
            @media print {

                /* 1. Hide everything on the page */
                body * {
                    visibility: hidden !important;
                }

                /* 2. Specifically show ONLY the bracket wrapper and its content */
                #bracket-wrapper,
                #bracket-wrapper * {
                    visibility: visible !important;
                }

                /* 3. Position the bracket at the absolute top-left for zero waste */
                #bracket-wrapper {
                    position: absolute !important;
                    left: 0 !important;
                    top: 0 !important;
                    width: 100% !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    transform: scale(0.9);
                    /* Adjust to ensure it fits landscape paper */
                    transform-origin: top left;
                }

                /* 4. Completely remove non-bracket layout blocks to prevent empty pages/spacing */
                header,
                nav,
                footer,
                aside,
                .no-print,
                .toast-container,
                [class*="bg-white border-b"] {
                    display: none !important;
                }

                /* 5. Landscape Optimization */
                @page {
                    size: landscape;
                    margin: 0.5cm;
                }

                body {
                    background: white !important;
                }

                /* Ensure background colors and paths are printed */
                * {
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }
            }
        </style>
    @endpush
    <!-- Add Participant Modal -->
    <div x-data="participantAddModal" class="relative" style="z-index: 9999;" aria-labelledby="modal-title"
        role="dialog" aria-modal="true" x-show="isOpen" style="display: none;">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/75 transition-opacity" x-show="isOpen"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" aria-hidden="true"
            @click="closeModal"></div>

        <div class="fixed inset-0 w-screen overflow-y-auto" style="z-index: 9999;">
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
                                    <h3 class="text-xl font-bold leading-6 text-gray-900" id="modal-title">Add New
                                        Participant</h3>
                                    <p class="text-sm text-gray-500 mt-1">This will add a new participant. You may need
                                        to shuffle or regenerate the bracket.</p>
                                    <div class="mt-4 space-y-4">

                                        <!-- Name -->
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700">Name</label>
                                            <input type="text" x-model="name"
                                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm font-bold"
                                                required placeholder="Fighter Name">
                                        </div>

                                        <!-- Dojo -->
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700">Dojo / Club</label>
                                            <input type="text" x-model="dojo"
                                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                                                placeholder="Club Name">
                                        </div>

                                        <!-- Image -->
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700">Image</label>
                                            <div class="mt-2 flex items-center gap-x-3">
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
                                class="inline-flex w-full justify-center rounded-lg bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-500 sm:ml-3 sm:w-auto">Add
                                Participant</button>
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