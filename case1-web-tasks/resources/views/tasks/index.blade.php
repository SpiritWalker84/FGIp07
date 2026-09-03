@extends('layouts.app')

@section('title', ' — Задачи')

@section('content')
<div class="page-header">
    <div>
        <h1>Задачи</h1>
        <p class="page-subtitle">Управление задачами команды</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('tasks.kanban', ['view' => $view ?? 'involved']) }}" class="btn btn-secondary">Канбан</a>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">+ Создать задачу</a>
    </div>
</div>

@include('tasks.partials.view-tabs')

@if($tasks->count() > 0)
    <div class="task-grid">
        @foreach($tasks as $task)
            <a href="{{ route('tasks.show', $task) }}" class="task-card">
                <div class="task-card-header">
                    @include('tasks.partials.status-badge', ['status' => $task->status])
                    @if($task->due_date)
                        <span class="task-due @if($task->due_date->isPast() && $task->status !== 'done') task-due-overdue @endif">
                            {{ $task->due_date->format('d.m.Y') }}
                        </span>
                    @endif
                </div>
                <h3 class="task-card-title">{{ $task->title }}</h3>
                @if($task->description)
                    <p class="task-card-desc">{{ Str::limit($task->description, 90) }}</p>
                @endif
                <div class="task-card-footer">
                    @if($task->assignee)
                        <span class="avatar avatar-sm">{{ mb_strtoupper(mb_substr($task->assignee->name, 0, 1)) }}</span>
                        <span>{{ $task->assignee->name }}</span>
                    @else
                        <span class="text-muted">Без исполнителя</span>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
    <div class="pagination">{{ $tasks->links() }}</div>
@else
    <div class="empty-state">
        <div class="empty-icon" aria-hidden="true">📋</div>
        <h2>Задач пока нет</h2>
        <p>Создайте первую задачу или смените фильтр</p>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">Создать задачу</a>
    </div>
@endif
@endsection
