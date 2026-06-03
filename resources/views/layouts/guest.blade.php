<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tani Cibiru') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .bg-efood {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 30px;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="text-gray-900 antialiased bg-efood min-h-screen">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 p-4">

        <div class="mb-6 animate-[slideUp_0.5s_ease-out]">
            <a href="/" class="flex flex-col items-center gap-3 text-decoration-none hover:opacity-90 transition-opacity">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-md p-2 overflow-hidden border border-emerald-100">
                    <img src="{{ asset('image/logo-cibiru.png') }}"
                        alt="Logo Cibiru"
                        style="width: 120px; height: 120px; object-fit: contain;">
                </div>
            </a>
        </div>

        <div class="w-full px-10 py-12 glass-card" style="max-width: 550px;">
            {{ $slot }}
        </div>

    </div>
</body>

</html>