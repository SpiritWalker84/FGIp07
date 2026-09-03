@extends('layouts.auth')

@section('title', ' — Регистрация')

@section('content')
<div class="auth-form-wrap">
    <p class="auth-kicker">Команда сервиса</p>
    <h1>Регистрация</h1>
    <p class="auth-lead">Доступ для сотрудников «МастерДом»</p>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf
        <div class="form-group">
            <label for="name">Имя</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required>
            @error('name') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            @error('email') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input id="password" type="password" name="password" required>
            @error('password') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation">Подтверждение</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Создать аккаунт</button>
    </form>

    <p class="auth-footer">Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
</div>
@endsection
