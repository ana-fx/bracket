@extends('layouts.admin')


@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">

        <div class="bg-white p-8 rounded-3xl shadow-lg border border-primary/20 mb-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $tournament->name }}</h1>
                    <p class="text-gray-500">Add participants to this tournament</p>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold uppercase">{{ $tournament->status }}</span>
            </div>

            <!-- Add Participants Form -->
            <form action="{{ route('tournaments.participants.store', $tournament) }}" method="POST" class="mb-8">
                @csrf
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Add Participants (One per line)</label>
                    <textarea name="participants" rows="6" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none" placeholder="Enter names..."></textarea>
                </div>
                <button type="submit" class="bg-secondary text-pink-900 font-bold py-3 px-6 rounded-xl hover:bg-pink-300 transition shadow-sm">
                    + Add Participants
                </button>
            </form>
        </div>

        <!-- Current Participants List -->
        <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Current Participants ({{ $participants->count() }})</h2>

            @if($participants->isEmpty())
                <p class="text-gray-400 italic">No participants added yet.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8" x-data="participantHandler()">
                    @foreach($participants as $participant)
                        <div class="bg-gray-50 px-4 py-3 rounded-xl border border-gray-100 flex justify-between items-center group hover:border-red-100 transition">
                            <span class="font-medium text-gray-700">{{ $participant->name }}</span>
                            <button type="button"
                                    @click="confirmDelete('{{ route('participants.destroy', [$tournament, $participant]) }}', '{{ $participant->name }}')"
                                    class="text-gray-300 hover:text-red-500 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                  <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    @endforeach

                    <!-- Delete Confirmation Modal -->
                    <div x-show="deleteModalOpen" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div x-show="deleteModalOpen"
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 bg-black/20 backdrop-blur-sm transition-opacity" @click="closeDeleteModal"></div>

                        <div class="fixed inset-0 z-10 overflow-y-auto">
                            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                <div x-show="deleteModalOpen"
                                     x-transition:enter="ease-out duration-300"
                                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                     x-transition:leave="ease-in duration-200"
                                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                                </svg>
                                            </div>
                                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Remove Participant</h3>
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-500">Are you sure you want to remove <span class="font-bold text-gray-800" x-text="participantName"></span>? This action cannot be undone.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                        <form :action="deleteUrl" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Remove</button>
                                        </form>
                                        <button type="button" @click="closeDeleteModal" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function participantHandler() {
                        return {
                            deleteModalOpen: false,
                            deleteUrl: '',
                            participantName: '',

                            confirmDelete(url, name) {
                                this.deleteUrl = url;
                                this.participantName = name;
                                this.deleteModalOpen = true;
                            },
                            closeDeleteModal() {
                                this.deleteModalOpen = false;
                            }
                        }
                    }
                </script>
            @endif

            <hr class="my-8 border-gray-100">




            @if($participants->count() >= 2)
                <form action="{{ route('tournaments.generate', $tournament) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-primary hover:bg-purple-500 text-white font-bold py-4 rounded-xl shadow-md transition transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                        </svg>
                        <span>Generate Bracket</span>
                    </button>
                </form>
            @else
                <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-8 text-center text-gray-400">
                    <p class="mb-2 font-semibold">Ready to start?</p>
                    <p class="text-sm">Add at least 2 participants above to unlock bracket generation.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
