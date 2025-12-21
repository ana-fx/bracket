@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
    <div class="mb-8 relative">
        <h1 class="text-9xl font-black text-gray-100 select-none">404</h1>
        <div class="absolute inset-0 flex items-center justify-center">
            <span class="text-2xl md:text-4xl font-bold text-gray-800">Oss! Page Not Found</span>
        </div>
    </div>

    <p class="text-lg text-gray-600 max-w-md mb-10">
        It seems you've been thrown off the mat. The page you are looking for doesn't exist or has moved.
    </p>

    <a href="/" class="flex items-center space-x-2 bg-secondary hover:bg-pink-300 text-pink-900 px-8 py-3 rounded-full font-bold shadow-md transition transform hover:-translate-y-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        <span>Back to Home</span>
    </a>
</div>
@endsection
