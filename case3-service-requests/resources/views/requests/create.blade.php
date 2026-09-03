@extends('layouts.app')

@section('title', ' — Новая заявка')

@section('content')
<div class="public-hero">
    <div class="public-hero-text">
        <p class="page-kicker">Для клиентов</p>
        <h1>Вызов мастера на дом</h1>
        <p>Оставьте заявку — диспетчер «МастерДом» свяжется с вами и назначит специалиста.</p>
    </div>
    <div class="public-hero-stats">
        <div><strong>~2 ч</strong><span>средний выезд</span></div>
        <div><strong>24/7</strong><span>приём заявок</span></div>
    </div>
</div>

<div class="form-panel">
    <form method="POST" action="{{ route('requests.store') }}" class="request-form">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label for="client_name">Имя клиента <span class="req">*</span></label>
                <input id="client_name" type="text" name="client_name" value="{{ old('client_name') }}" required placeholder="Иван Петров">
                @error('client_name') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="phone">Телефон <span class="req">*</span></label>
                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required placeholder="+7 (999) 123-45-67">
                @error('phone') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="form-group">
            <label for="address">Адрес <span class="req">*</span></label>
            <input id="address" type="text" name="address" value="{{ old('address') }}" required placeholder="г. Москва, ул. Примерная, д. 1, кв. 1">
            @error('address') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="problem_text">Описание проблемы <span class="req">*</span></label>
            <textarea id="problem_text" name="problem_text" rows="5" required placeholder="Например: не включается бойлер, нужен выезд сантехника">{{ old('problem_text') }}</textarea>
            @error('problem_text') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-accent btn-lg btn-block">Отправить заявку</button>
    </form>
</div>
@endsection
