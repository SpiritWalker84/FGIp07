@extends('layouts.app')

@section('title', ' — Канбан')

@section('content')
<div class="page-header">
    <div>
        <h1>Канбан</h1>
        <p class="page-subtitle">Перетаскивание заменено выпадающим списком — удобно с клавиатуры</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('tasks.index', ['view' => $view]) }}" class="btn btn-secondary">Список</a>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">+ Создать задачу</a>
    </div>
</div>

@include('tasks.partials.view-tabs')

<div class="kanban-board">
    @php
        $labels = ['new' => 'Новая', 'in_progress' => 'В работе', 'done' => 'Завершена'];
    @endphp
    @foreach($columns as $status => $items)
        <section class="kanban-column kanban-column-{{ $status }}" aria-label="{{ $labels[$status] ?? $status }}">
            <div class="kanban-column-head">
                <h2 class="kanban-column-title">{{ $labels[$status] ?? $status }}</h2>
                <span class="kanban-count">{{ $items->count() }}</span>
            </div>
            <div class="kanban-cards">
                @forelse($items as $task)
                    <article class="kanban-card">
                        <a href="{{ route('tasks.show', $task) }}" class="kanban-card-title">{{ $task->title }}</a>
                        @if($task->assignee)
                            <div class="kanban-meta">
                                <span class="avatar avatar-sm">{{ mb_strtoupper(mb_substr($task->assignee->name, 0, 1)) }}</span>
                                {{ $task->assignee->name }}
                            </div>
                        @endif
                        @if($task->due_date)
                            <div class="kanban-meta kanban-due">Срок: {{ $task->due_date->format('d.m.Y') }}</div>
                        @endif
                        <form method="POST" action="{{ route('tasks.status', $task) }}" class="kanban-move">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="view" value="{{ $view }}">
                            <label class="sr-only" for="status-{{ $task->id }}">Статус</label>
                            <select id="status-{{ $task->id }}" name="status" onchange="this.form.submit()">
                                @foreach($labels as $value => $label)
                                    <option value="{{ $value }}" @selected($task->status === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </form>
                    </article>
                @empty
                    <p class="kanban-empty">Нет задач</p>
                @endforelse
            </div>
        </section>
    @endforeach
</div>
@endsection
