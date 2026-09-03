@extends('layouts.app')

@section('title', ' — Диспетчер')

@section('content')
<div class="page-header">
    <div class="page-header-main">
        <p class="page-kicker">Панель управления</p>
        <h1>Диспетчерская</h1>
        <p class="page-desc">Все заявки сервиса — назначение мастеров и контроль статусов</p>
    </div>
    <a href="{{ route('requests.create') }}" class="btn btn-accent">+ Новая заявка</a>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('dispatcher.index') }}" class="filter-form">
        <label for="status">Статус</label>
        <select id="status" name="status">
            <option value="">Все</option>
            <option value="new" @selected(request('status') === 'new')>Новая</option>
            <option value="assigned" @selected(request('status') === 'assigned')>Назначена</option>
            <option value="in_progress" @selected(request('status') === 'in_progress')>В работе</option>
            <option value="done" @selected(request('status') === 'done')>Выполнена</option>
            <option value="canceled" @selected(request('status') === 'canceled')>Отменена</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Фильтр</button>
        <a href="{{ route('dispatcher.index') }}" class="btn btn-ghost btn-sm">Сброс</a>
    </form>
</div>

@if($requests->count() > 0)
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Клиент</th>
                    <th>Телефон</th>
                    <th>Адрес</th>
                    <th>Статус</th>
                    <th>Мастер</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                    <tr>
                        <td class="mono">#{{ $request->id }}</td>
                        <td><strong>{{ $request->client_name }}</strong></td>
                        <td><a href="tel:{{ $request->phone }}" class="tel-link">{{ $request->phone }}</a></td>
                        <td class="muted">{{ Str::limit($request->address, 36) }}</td>
                        <td>@include('partials.status-badge', ['status' => $request->status])</td>
                        <td>{{ $request->assignedTo?->name ?? '—' }}</td>
                        <td>
                            <div class="row-actions">
                                @if($request->status === 'new')
                                    <form method="POST" action="{{ route('dispatcher.requests.assign', $request) }}" class="inline-form">
                                        @csrf
                                        <select name="master_id" required class="select-sm">
                                            <option value="">Мастер</option>
                                            @foreach($masters as $master)
                                                <option value="{{ $master->id }}">{{ $master->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-xs">Назначить</button>
                                    </form>
                                @endif
                                @if($request->canBeCanceled())
                                    <form method="POST" action="{{ route('dispatcher.requests.cancel', $request) }}" class="inline-form"
                                          onsubmit="return confirm('Отменить заявку #{{ $request->id }}?');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-xs">Отменить</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $requests->links() }}</div>
@else
    <div class="empty-state">
        <div class="empty-icon">📭</div>
        <h2>Заявок не найдено</h2>
        <p>Попробуйте сбросить фильтр или создайте новую заявку</p>
    </div>
@endif
@endsection
