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
                    <span
                        class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold uppercase">{{ $tournament->status }}</span>
                </div>

                <!-- Add Participants Form -->
                <div x-data="{ tab: 'single' }" class="mb-8">
                    <div class="flex gap-4 mb-4 border-b border-gray-100">
                        <button @click="tab = 'single'"
                            :class="{ 'border-primary text-primary': tab === 'single', 'border-transparent text-gray-500 hover:text-gray-700': tab !== 'single' }"
                            class="pb-2 border-b-2 font-semibold transition">
                            Add One-by-One
                        </button>
                        <button @click="tab = 'bulk'"
                            :class="{ 'border-primary text-primary': tab === 'bulk', 'border-transparent text-gray-500 hover:text-gray-700': tab !== 'bulk' }"
                            class="pb-2 border-b-2 font-semibold transition">
                            Bulk Import
                        </button>
                    </div>

                    <!-- Single Add Form -->
                    <form x-show="tab === 'single'" action="{{ route('tournaments.participants.store', $tournament) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Participant Name</label>
                                <input type="text" name="name"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                                    placeholder="e.g. Daniel LaRusso" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Dojo / Affiliation</label>
                                <input type="text" name="dojo"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                                    placeholder="e.g. Miyagi-Do">
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 font-bold mb-2">Photo</label>
                            <input type="file" name="image"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition">
                        </div>
                        <button type="submit"
                            class="bg-secondary text-pink-900 font-bold py-3 px-6 rounded-xl hover:bg-pink-300 transition shadow-sm">
                            + Add Participant
                        </button>
                    </form>

                    <!-- Bulk Add Form -->
                    <form x-show="tab === 'bulk'" action="{{ route('tournaments.participants.store', $tournament) }}"
                        method="POST" style="display: none;">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-gray-700 font-bold mb-2">Add Participants (One per line)</label>
                            <textarea name="participants" rows="6"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                                placeholder="Enter names..."></textarea>
                        </div>
                        <button type="submit"
                            class="bg-secondary text-pink-900 font-bold py-3 px-6 rounded-xl hover:bg-pink-300 transition shadow-sm">
                            + Add All
                        </button>
                    </form>
                </div>
            </div>

            <!-- Current Participants List -->
            <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Current Participants ({{ $participants->count() }})</h2>

                @if($participants->isEmpty())
                    <p class="text-gray-400 italic">No participants added yet.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8" x-data="participantHandler()">
                        @foreach($participants as $participant)
                            <div
                                class="bg-gray-50 px-4 py-3 rounded-xl border border-gray-100 flex justify-between items-center group hover:border-red-100 transition relative">
                                <div class="flex items-center gap-3">
                                    @if($participant->image_path)
                                        <img src="{{ asset('storage/' . $participant->image_path) }}" alt="{{ $participant->name }}"
                                            class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                                class="w-6 h-6">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-5.5-2.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a5.99 5.99 0 00-4.793 2.39A9.948 9.948 0 0010 18a9.948 9.948 0 004.793-1.61A5.99 5.99 0 0010 12z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="font-medium text-gray-700 block">{{ $participant->name }}</span>
                                        @if($participant->dojo)
                                            <span class="text-xs text-gray-500">{{ $participant->dojo }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button type="button"
                                        @click="openEditModal('{{ route('participants.update', [$tournament, $participant]) }}', '{{ $participant->name }}', '{{ $participant->dojo }}')"
                                        class="text-gray-300 hover:text-blue-500 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                            class="w-5 h-5">
                                            <path
                                                d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                            <path
                                                d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                        </svg>
                                    </button>
                                    <button type="button"
                                        @click="confirmDelete('{{ route('participants.destroy', [$tournament, $participant]) }}', '{{ $participant->name }}')"
                                        class="text-gray-300 hover:text-red-500 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                            class="w-5 h-5">
                                            <path fill-rule="evenodd"
                                                d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        <!-- Edit Modal -->
                        <div x-show="editModalOpen" style="display: none;" class="relative z-50">
                            <div x-show="editModalOpen" class="fixed inset-0 bg-black/20 backdrop-blur-sm transition-opacity"
                                @click="closeEditModal"></div>
                            <div class="fixed inset-0 z-10 overflow-y-auto">
                                <div class="flex min-h-full items-center justify-center p-4">
                                    <div x-show="editModalOpen"
                                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg p-6">
                                        <h3 class="text-lg font-bold text-gray-900 mb-4">Edit Participant</h3>
                                        <form :action="editUrl" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                                <input type="text" name="name" x-model="editName"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Dojo</label>
                                                <input type="text" name="dojo" x-model="editDojo"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                                    placeholder="e.g. Cobra Kai">
                                            </div>

                                            <div class="mb-6">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                                                <input type="file" name="image"
                                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
                                            </div>

                                            <div class="flex justify-end gap-3">
                                                <button type="button" @click="closeEditModal"
                                                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                                                <button type="submit"
                                                    class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Save
                                                    Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div x-show="deleteModalOpen" style="display: none;" class="relative z-50" aria-labelledby="modal-title"
                            role="dialog" aria-modal="true">
                            <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="fixed inset-0 bg-black/20 backdrop-blur-sm transition-opacity" @click="closeDeleteModal">
                            </div>

                            <div class="fixed inset-0 z-10 overflow-y-auto">
                                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                    <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave="ease-in duration-200"
                                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                            <div class="sm:flex sm:items-start">
                                                <div
                                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                                    </svg>
                                                </div>
                                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                                    <h3 class="text-base font-semibold leading-6 text-gray-900"
                                                        id="modal-title">Remove Participant</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Are you sure you want to remove <span
                                                                class="font-bold text-gray-800"
                                                                x-text="participantName"></span>? This action cannot be undone.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                            <form :action="deleteUrl" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex w-full justify-center rounded-xl bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Remove</button>
                                            </form>
                                            <button type="button" @click="closeDeleteModal"
                                                class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
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

                                editModalOpen: false,
                                editUrl: '',
                                editName: '',
                                editDojo: '',

                                confirmDelete(url, name) {
                                    this.deleteUrl = url;
                                    this.participantName = name;
                                    this.deleteModalOpen = true;
                                },
                                closeDeleteModal() {
                                    this.deleteModalOpen = false;
                                },

                                openEditModal(url, name, dojo) {
                                    this.editUrl = url;
                                    this.editName = name;
                                    this.editDojo = dojo;
                                    this.editModalOpen = true;
                                },
                                closeEditModal() {
                                    this.editModalOpen = false;
                                }
                            }
                        }
                    </script>
                @endif

                <hr class="my-8 border-gray-100">




                @if($participants->count() >= 2)
                    <form action="{{ route('tournaments.generate', $tournament) }}" method="POST"
                        onsubmit="return confirmGeneration(event)">
                        @csrf
                        <button type="submit"
                            class="w-full bg-primary hover:bg-purple-500 text-white font-bold py-4 rounded-xl shadow-md transition transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                            </svg>
                            <span>Generate Bracket</span>
                        </button>
                    </form>

                    <script>
                        function confirmGeneration(e) {
                            const nameInput = document.querySelector('input[name="name"]');
                            const imageInput = document.querySelector('input[name="image"]');

                            // Check Single Add Form
                            if (nameInput && nameInput.value.trim() !== '') {
                                return confirm('You have unsaved changes in the "Add Participant" form. Generating the bracket will discard them. Are you sure?');
                            }
                            if (imageInput && imageInput.value !== '') {
                                return confirm('You have selected an image but haven\'t added the participant yet. Generating the bracket will discard it. Are you sure?');
                            }

                            // Check Bulk Form
                            const bulkInput = document.querySelector('textarea[name="participants"]');
                            if (bulkInput && bulkInput.value.trim() !== '' && bulkInput.offsetWidth > 0) { // offsetWidth > 0 checks visibility
                                return confirm('You have unsaved data in the Bulk Import form. Generating the bracket will discard it. Are you sure?');
                            }

                            return true;
                        }
                    </script>
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