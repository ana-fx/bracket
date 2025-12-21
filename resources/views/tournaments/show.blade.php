@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-50/50">
    <!-- Hero Section -->
    <div class="relative w-full h-[500px] overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-gray-900/90 z-10"></div>

        @if($tournament->cover_image)
            <img src="{{ asset('storage/' . $tournament->cover_image) }}" alt="Cover" class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
        @else
            <div class="w-full h-full bg-gradient-to-br from-primary to-purple-800 animate-gradient-xy"></div>
        @endif

        <div class="absolute bottom-0 left-0 w-full z-20 p-8 md:p-16">
            <div class="container mx-auto">
                {{-- Status Badge --}}
                <div class="mb-4">
                     <span class="px-4 py-2 bg-white/20 backdrop-blur-md border border-white/10 text-white rounded-full text-xs font-bold tracking-wider uppercase shadow-sm">
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

    <div class="container mx-auto px-4 -mt-10 mb-20 relative z-30">
        @include('partials.bracket')
    </div>
</div>
@endsection
