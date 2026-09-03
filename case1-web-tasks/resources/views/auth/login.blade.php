@extends('layouts.app')

@section('title', ' — Вход')
@section('main_class', 'app-main-auth')

@section('content')
<div class="auth-card">
    <h1>Вход в TaskHub</h1>
    <p class="auth-subtitle">Корпоративный трекер задач</p>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@taskhub.local">
            @error('email') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input id="password" type="password" name="password" required placeholder="••••••••">
            @error('password') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group form-check">
            <label>
                <input type="checkbox" name="remember">
                Запомнить меня
            </label>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Войти</button>
    </form>
    <p class="auth-footer">Нет аккаунта? <a href="{{ route('register') }}">Регистрация</a></p>
</div>
@endsection
