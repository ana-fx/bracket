@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">

        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">Edit Tournament</h1>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">Cancel</a>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-lg border border-primary/20 mb-8">
            <form action="{{ route('tournaments.update', $tournament) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Tournament Name</label>
                    <input type="text" name="name" value="{{ old('name', $tournament->name) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none">{{ old('description', $tournament->description) }}</textarea>
                </div>

                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $tournament->start_date?->format('Y-m-d')) }}" class="datepicker w-full px-4 py-3 rounded-xl border {{ $errors->has('start_date') ? 'border-red-500' : 'border-gray-200' }} focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none bg-white" placeholder="Select Start Date">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $tournament->end_date?->format('Y-m-d')) }}" class="datepicker w-full px-4 py-3 rounded-xl border {{ $errors->has('end_date') ? 'border-red-500' : 'border-gray-200' }} focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none bg-white" placeholder="Select End Date">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Match Format (Best Of)</label>
                    <select name="best_of" class="w-full px-4 py-3 rounded-xl border {{ $errors->has('best_of') ? 'border-red-500' : 'border-gray-200' }} focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none">
                        <option value="1" {{ old('best_of', $tournament->best_of) == 1 ? 'selected' : '' }}>Best of 1 (Single Match)</option>
                        <option value="3" {{ old('best_of', $tournament->best_of) == 3 ? 'selected' : '' }}>Best of 3</option>
                        <option value="5" {{ old('best_of', $tournament->best_of) == 5 ? 'selected' : '' }}>Best of 5</option>
                        <option value="7" {{ old('best_of', $tournament->best_of) == 7 ? 'selected' : '' }}>Best of 7</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Location</label>
                    <input type="text" name="location" value="{{ old('location', $tournament->location) }}" class="w-full px-4 py-3 rounded-xl border {{ $errors->has('location') ? 'border-red-500' : 'border-gray-200' }} focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Location Map (Embed Code)</label>
                    <textarea name="location_map" rows="2" class="w-full px-4 py-3 rounded-xl border {{ $errors->has('location_map') ? 'border-red-500' : 'border-gray-200' }} focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none">{{ old('location_map', $tournament->location_map) }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Terms & Conditions</label>
                    <textarea name="terms_and_conditions" rows="3" class="w-full px-4 py-3 rounded-xl border {{ $errors->has('terms_and_conditions') ? 'border-red-500' : 'border-gray-200' }} focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none">{{ old('terms_and_conditions', $tournament->terms_and_conditions) }}</textarea>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 font-bold mb-2">Cover Image</label>

                    @if($tournament->cover_image)
                        <div class="mb-4">
                            <p class="text-xs text-gray-400 mb-1">Current Image:</p>
                            <img src="{{ asset('storage/' . $tournament->cover_image) }}" class="h-32 rounded-lg object-cover">
                        </div>
                    @endif

                    <div class="mt-2 flex justify-center rounded-xl border border-dashed border-gray-300 px-6 py-10 hover:bg-gray-50 transition relative group">
                         <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300 group-hover:text-primary transition" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                            </svg>
                            <div class="mt-4 flex text-sm leading-6 text-gray-600 justify-center">
                                <label for="cover_image" class="relative cursor-pointer rounded-md bg-white font-semibold text-primary focus-within:outline-none focus-within:ring-2 focus-within:ring-primary focus-within:ring-offset-2 hover:text-purple-500">
                                    <span>Change file</span>
                                    <input id="cover_image" name="cover_image" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
                                </label>
                            </div>
                            <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF up to 2MB</p>
                        </div>
                    </div>
                     <!-- Preview Container -->
                    <div id="image-preview" class="mt-4 hidden">
                        <p class="text-sm text-gray-500 mb-2">New Selection Preview:</p>
                        <img id="preview-img" src="#" alt="Preview" class="max-h-48 rounded-lg shadow-md mx-auto">
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4">
                     <a href="{{ route('tournaments.participants', $tournament) }}" class="text-primary font-bold hover:underline">
                        Manage Participants &raquo;
                    </a>

                    <button type="submit" class="bg-primary hover:bg-purple-500 text-white font-bold py-3 px-8 rounded-xl shadow-md transition transform hover:-translate-y-1">
                        Save Changes
                    </button>
                </div>

            </form>
        </div>

        <script>
            function previewImage(input) {
                const previewContainer = document.getElementById('image-preview');
                const previewImg = document.getElementById('preview-img');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                    }

                    reader.readAsDataURL(input.files[0]);
                } else {
                    previewContainer.classList.add('hidden');
                }
            }

            // Initialize Datepicker
            document.addEventListener('DOMContentLoaded', function() {
                flatpickr(".datepicker", {
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    theme: "airbnb"
                });
            });
        </script>

        <!-- Danger Zone -->
        <div class="bg-red-50 p-8 rounded-3xl border border-red-100">
            <h3 class="text-red-700 font-bold mb-2">Danger Zone</h3>
            <p class="text-red-500 text-sm mb-4">Deleting a tournament is irreversible. All matches and participant data will be lost.</p>

            <form action="{{ route('tournaments.destroy', $tournament) }}" method="POST" onsubmit="return confirm('Are you absolutely sure you want to delete this tournament? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-white border border-red-200 text-red-600 font-bold py-2 px-4 rounded-lg hover:bg-red-600 hover:text-white transition">
                    Delete Tournament
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
