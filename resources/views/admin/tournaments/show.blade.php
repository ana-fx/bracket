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

    <div class="container mx-auto px-4 py-8">
        @include('partials.bracket')
    </div>
</div>
@endsection
