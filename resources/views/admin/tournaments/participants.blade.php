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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                    @foreach($participants as $participant)
                        <div class="bg-gray-50 px-4 py-3 rounded-xl border border-gray-100 flex justify-between items-center">
                            <span class="font-medium text-gray-700">{{ $participant->name }}</span>
                            <form action="{{ route('participants.destroy', [$tournament, $participant]) }}" method="POST" onsubmit="return confirm('Remove {{ $participant->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600">
                                    &times;
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif

            <hr class="my-8 border-gray-100">




            <form action="{{ route('tournaments.generate', $tournament) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-primary hover:bg-purple-500 text-white font-bold py-4 rounded-xl shadow-md transition transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed" {{ $participants->count() < 2 ? 'disabled' : '' }}>
                    Generate Bracket &raquo;
                </button>
                @if($participants->count() < 2)
                    <p class="text-center text-sm text-red-400 mt-2">Add at least 2 participants to generate a bracket.</p>
                @endif
            </form>
        </div>

    </div>
</div>
@endsection
