@extends('layouts.auth')

@section('title', ' — Вход')

@section('content')
<div class="auth-form-wrap">
    <p class="auth-kicker">Личный кабинет</p>
    <h1>Вход в систему</h1>
    <p class="auth-lead">Диспетчеры и мастера «МастерДом»</p>

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="dispatcher@example.com">
            @error('email') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input id="password" type="password" name="password" required placeholder="••••••••">
            @error('password') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group form-check">
            <label><input type="checkbox" name="remember"> Запомнить меня</label>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Войти</button>
    </form>

    <p class="auth-footer">Нет аккаунта? <a href="{{ route('register') }}">Регистрация</a></p>
    <p class="auth-footer"><a href="{{ route('requests.create') }}">← Оставить заявку без входа</a></p>
</div>
@endsection
