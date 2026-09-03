@extends('layouts.app')

@section('title', ' — Мастер')

@section('content')
@php
    $assignedCount = $requests->where('status', 'assigned')->count();
    $inProgressCount = $requests->where('status', 'in_progress')->count();
    $doneCount = $requests->where('status', 'done')->count();
@endphp

<header class="page-header">
    <div class="page-header-main">
        <h1>Мои выезды</h1>
        <p class="page-desc">Заявки, назначенные на вас. Адрес и телефон — первым делом на объекте.</p>
    </div>
    <div class="page-stats">
        <div class="page-stat page-stat--wait">
            <span class="page-stat-num">{{ $assignedCount }}</span>
            <span class="page-stat-label">Ждут</span>
        </div>
        <div class="page-stat page-stat--work">
            <span class="page-stat-num">{{ $inProgressCount }}</span>
            <span class="page-stat-label">В работе</span>
        </div>
        <div class="page-stat page-stat--done">
            <span class="page-stat-num">{{ $doneCount }}</span>
            <span class="page-stat-label">Готово</span>
        </div>
    </div>
</header>

@if($requests->count() > 0)
    <div class="visit-list">
        @foreach($requests as $request)
            <article @class(['visit-card', 'visit-card--' . $request->status])>
                <div class="visit-card-top">
                    <span class="visit-card-num">#{{ $request->id }}</span>
                    @include('partials.status-badge', ['status' => $request->status])
                    <time datetime="{{ $request->created_at->toIso8601String() }}">{{ $request->created_at->format('d.m.Y, H:i') }}</time>
                </div>

                <h2 class="visit-card-address">{{ $request->address }}</h2>

                <div class="visit-card-meta">
                    <div class="visit-meta-item">
                        <span class="visit-meta-label">Клиент</span>
                        <span class="visit-meta-value">{{ $request->client_name }}</span>
                    </div>
                    <div class="visit-meta-item">
                        <span class="visit-meta-label">Телефон</span>
                        <a href="tel:{{ preg_replace('/\D+/', '', $request->phone) }}" class="visit-meta-phone">{{ $request->phone }}</a>
                    </div>
                </div>

                @if($request->problem_text)
                    <p class="visit-card-problem">{{ $request->problem_text }}</p>
                @endif

                @if($request->canBeTaken() || $request->canBeCompleted())
                    <div class="visit-card-actions">
                        @if($request->canBeTaken())
                            <form method="POST" action="{{ route('master.requests.take', $request) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-block">Начать работу</button>
                            </form>
                        @endif
                        @if($request->canBeCompleted())
                            <form method="POST" action="{{ route('master.requests.complete', $request) }}"
                                  onsubmit="return confirm('Завершить заявку #{{ $request->id }}?');">
                                @csrf
                                <button type="submit" class="btn btn-accent btn-block">Завершить выезд</button>
                            </form>
                        @endif
                    </div>
                @endif
            </article>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <svg class="empty-svg" width="48" height="48" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h2>Нет назначенных заявок</h2>
        <p>Когда диспетчер назначит выезд — карточка появится здесь</p>
    </div>
@endif
@endsection
