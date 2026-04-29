<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/my-styles.css') }}">
    <title>TutaGlitch</title>
    <style>
        body {
            background: url('{{ asset('bg.png') }}') no-repeat center center fixed;
            background-size: cover;
        }
        /* Сероватая полоса сверху */
        .header-gray {
            background-color: rgba(25, 25, 25, 0.85);
            border-bottom: 1px solid #333;
            backdrop-blur: 5px;
        }
        /* Разные оттенки серого для логотипа */
        .logo-text-light { color: #e0e0e0; } /* Светло-серый */
        .logo-text-dark { color: #808080; }  /* Темно-серый */

        /* Футер - делаем текст темнее и приглушеннее */
        .footer-dark {
            background-color: rgba(15, 15, 15, 0.9);
            color: #555 !important;
            font-size: 0.9rem;
        }
        /* Серо-синий цвет для основной кнопки (как фон) */
        .btn-slate-blue {
            background-color: #34495e !important;
            border-color: #2c3e50 !important;
            color: #bdc3c7 !important;
        }
        .btn-slate-blue:hover {
            background-color: #2c3e50 !important;
            color: #ffffff !important;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<header class="header-gray fixed-top w-100">
    <div class="container d-flex justify-content-between align-items-center py-3"> {{-- Увеличил отступы py-3 --}}
        <a class="d-flex align-items-center text-decoration-none" href="/">
            {{-- Увеличил лого до 70 --}}
            <img src="{{ asset('logo.png') }}" alt="Logo" width="70" height="70" class="rounded border border-secondary shadow-sm">
            <h1 class="h2 mb-0 ms-3 fw-bold">
                <span class="logo-text-light">Tuta</span><span class="logo-text-dark">Glitch</span>
            </h1>
        </a>

        {{-- Меню справа --}}
        <div class="d-flex align-items-center">
            @yield('content1')
        </div>
    </div>
</header>

<main class="flex-grow-1 d-flex align-items-center">
    <div class="container text-center">
        @yield('content2')
    </div>
</main>

<footer class="footer-dark py-3">
    <div class="container text-center">
        <span>© 2025 TutaGlitch — Быстрая и удобная загрузка видео</span>
    </div>
</footer>

</body>
</html>
