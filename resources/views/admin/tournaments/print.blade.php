<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Print Bracket - {{ $tournament->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @media print {
            @page {
                size: landscape;
                margin: 0.5cm;
            }

            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

        /* Force specific width for consistent rendering */
        .print-container {
            width: 1280px;
            margin: 0 auto;
            position: relative;
        }

        /* Ensure lines are visible */
        #connector-svg path {
            stroke: #4b5563 !important;
            stroke-width: 2px !important;
        }
    </style>
</head>

<body class="bg-white text-gray-900 p-8">

    <div class="print-container">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black uppercase tracking-wider">{{ $tournament->name }}</h1>
            <p class="text-gray-500 font-bold mt-2">{{ $tournament->location ?? 'Tournament Bracket' }}</p>
        </div>

        @include('partials.bracket', ['isAdmin' => false]) <!-- Non-interactive bracket -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Wait slightly for layout to settle then print
            setTimeout(() => {
                window.print();
            }, 1000);
        });
    </script>
</body>

</html>