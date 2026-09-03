<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}@yield('title', '')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <header class="app-header">
        <a href="{{ auth()->check() ? route('tasks.index') : route('login') }}" class="brand">
            <span class="brand-mark" aria-hidden="true">✓</span>
            {{ config('app.name') }}
        </a>

        @auth
            <nav class="main-nav" aria-label="Основное меню">
                <a href="{{ route('tasks.index') }}" @class(['nav-link', 'active' => request()->routeIs('tasks.index')])>Список</a>
                <a href="{{ route('tasks.kanban') }}" @class(['nav-link', 'active' => request()->routeIs('tasks.kanban')])>Канбан</a>
                <a href="{{ route('tasks.create') }}" @class(['nav-link', 'nav-link-accent'])>+ Задача</a>
            </nav>
            <div class="header-user">
                <span class="avatar" title="{{ auth()->user()->name }}">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                <div class="header-user-text">
                    <span class="header-user-name">{{ auth()->user()->name }}</span>
                    <span class="header-user-role">{{ auth()->user()->role }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm">Выход</button>
                </form>
            </div>
        @else
            <nav class="main-nav">
                <a href="{{ route('login') }}" class="nav-link">Вход</a>
                <a href="{{ route('register') }}" class="nav-link nav-link-accent">Регистрация</a>
            </nav>
        @endauth
    </header>

    <main class="app-main @yield('main_class')">
        @if(session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
