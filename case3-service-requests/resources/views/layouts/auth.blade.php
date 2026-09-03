<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>МастерДом@yield('title', '')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}?v=2" rel="stylesheet">
    @stack('styles')
</head>
<body class="auth-body">
    <div class="auth-shell">
        <aside class="brand-panel" aria-hidden="false">
            <div class="brand-panel-inner">
                <div class="brand-logo">
                    <span class="brand-icon" aria-hidden="true">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 10.5L12 3l9 7.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-9.5z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M14 7l2 2-5 5-2-2 5-5z" fill="currentColor"/>
                        </svg>
                    </span>
                    <div>
                        <strong>МастерДом</strong>
                        <span>Сервисная служба</span>
                    </div>
                </div>
                <h2 class="brand-headline">Ремонт и выезд мастера — без хаоса в мессенджерах</h2>
                <p class="brand-text">Единая система заявок для диспетчеров и мастеров. Принимаем вызов, назначаем исполнителя, контролируем статус.</p>
                <ul class="brand-features">
                    <li><span class="dot"></span> Заявки с сайта и телефона</li>
                    <li><span class="dot"></span> Панель диспетчера и мастера</li>
                    <li><span class="dot"></span> Защита от двойного взятия заявки</li>
                </ul>
            </div>
        </aside>
        <main class="auth-main">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
