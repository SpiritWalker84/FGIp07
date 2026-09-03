@extends('layouts.app')

@section('title', ' — ' . $task->title)

@section('content')
<div class="task-detail">
    <div class="task-detail-main">
        <div class="task-detail-top">
            @include('tasks.partials.status-badge', ['status' => $task->status])
            @if($task->due_date)
                <span class="task-due @if($task->due_date->isPast() && $task->status !== 'done') task-due-overdue @endif">
                    Срок: {{ $task->due_date->format('d.m.Y') }}
                </span>
            @endif
        </div>
        <h1>{{ $task->title }}</h1>
        @if($task->description)
            <div class="task-description">{{ $task->description }}</div>
        @endif
        @auth
            <div class="task-actions">
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-secondary btn-sm">Редактировать</a>
                <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Удалить задачу?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                </form>
            </div>
        @endauth
    </div>

    <aside class="task-detail-sidebar">
        <dl class="meta-list">
            <div class="meta-item">
                <dt>Постановщик</dt>
                <dd>
                    <span class="avatar avatar-sm">{{ mb_strtoupper(mb_substr($task->creator->name, 0, 1)) }}</span>
                    {{ $task->creator->name }}
                </dd>
            </div>
            <div class="meta-item">
                <dt>Исполнитель</dt>
                <dd>
                    @if($task->assignee)
                        <span class="avatar avatar-sm">{{ mb_strtoupper(mb_substr($task->assignee->name, 0, 1)) }}</span>
                        {{ $task->assignee->name }}
                    @else
                        <span class="text-muted">Не назначен</span>
                    @endif
                </dd>
            </div>
            <div class="meta-item">
                <dt>Создана</dt>
                <dd>{{ $task->created_at->format('d.m.Y H:i') }}</dd>
            </div>
        </dl>
    </aside>
</div>

@auth
    <section class="comments-section">
        <h2>Комментарии <span class="comments-count">{{ $task->comments->count() }}</span></h2>
        <form method="POST" action="{{ route('comments.store', $task) }}" class="comment-form">
            @csrf
            <div class="form-group">
                <label for="comment-body">Добавить комментарий</label>
                <textarea id="comment-body" name="body" required placeholder="Напишите комментарий..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Отправить</button>
        </form>
        @if($task->comments->count() > 0)
            <div class="comment-list">
                @foreach($task->comments as $comment)
                    <article class="comment-item">
                        <div class="comment-head">
                            <span class="avatar avatar-sm">{{ mb_strtoupper(mb_substr($comment->user->name, 0, 1)) }}</span>
                            <div>
                                <div class="comment-author">{{ $comment->user->name }}</div>
                                <div class="comment-date">{{ $comment->created_at->format('d.m.Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="comment-body">{{ $comment->body }}</div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="text-muted text-center">Пока нет комментариев</p>
        @endif
    </section>
@endauth
@endsection
