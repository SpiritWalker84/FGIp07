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
<body @class(['has-sidebar' => auth()->check()])>
    @auth
        <aside class="sidebar">
            <a href="{{ auth()->user()->isDispatcher() ? route('dispatcher.index') : route('master.index') }}" class="sidebar-brand">
                <span class="brand-icon-sm" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-9.5z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M14 7l2 2-5 5-2-2 5-5z" fill="currentColor"/></svg>
                </span>
                <span>МастерДом</span>
            </a>

            <nav class="sidebar-nav" aria-label="Основное меню">
                @if(auth()->user()->isDispatcher())
                    <a href="{{ route('dispatcher.index') }}" @class(['sidebar-link', 'active' => request()->routeIs('dispatcher.*')])>
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        Диспетчерская
                    </a>
                    <a href="{{ route('requests.create') }}" @class(['sidebar-link', 'active' => request()->routeIs('requests.create')])>
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        Новая заявка
                    </a>
                @elseif(auth()->user()->isMaster())
                    <a href="{{ route('master.index') }}" @class(['sidebar-link', 'active' => request()->routeIs('master.*')])>
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Мои выезды
                    </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                @include('partials.sidebar-profile', ['user' => auth()->user()])
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-logout">Выйти</button>
                </form>
            </div>
        </aside>
    @else
        <header class="public-topbar">
            <a href="{{ route('requests.create') }}" class="public-brand">
                <span class="brand-icon-sm" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-9.5z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M14 7l2 2-5 5-2-2 5-5z" fill="currentColor"/></svg>
                </span>
                МастерДом
            </a>
            <div class="public-topbar-actions">
                <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Вход</a>
                <a href="{{ route('register') }}" class="btn btn-accent btn-sm">Регистрация</a>
            </div>
        </header>
    @endauth

    <div class="app-content">
        <main class="page-main">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">
                    <ul class="alert-list">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
