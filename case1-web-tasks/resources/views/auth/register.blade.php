@extends('layouts.app')

@section('title', ' — Регистрация')
@section('main_class', 'app-main-auth')

@section('content')
<div class="auth-card">
    <h1>Регистрация</h1>
    <p class="auth-subtitle">Создайте аккаунт сотрудника</p>
    <form method="POST" action="{{ route('register') }}">
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
            <label for="password_confirmation">Подтверждение пароля</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Зарегистрироваться</button>
    </form>
    <p class="auth-footer">Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
</div>
@endsection
