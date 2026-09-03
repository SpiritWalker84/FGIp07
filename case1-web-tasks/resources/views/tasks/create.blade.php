@extends('layouts.app')

@section('title', ' — Новая задача')

@section('content')
<div class="page-header">
    <div>
        <h1>Новая задача</h1>
        <p class="page-subtitle">Заполните поля и назначьте исполнителя</p>
    </div>
</div>

<form method="POST" action="{{ route('tasks.store') }}" class="form-card">
    @csrf
    <div class="form-group">
        <label for="title">Название</label>
        <input id="title" type="text" name="title" value="{{ old('title') }}" required placeholder="Например: Подготовить отчёт">
        @error('title') <span class="field-error">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label for="description">Описание</label>
        <textarea id="description" name="description" placeholder="Детали задачи...">{{ old('description') }}</textarea>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="assignee_id">Исполнитель</label>
            <select id="assignee_id" name="assignee_id">
                <option value="">— не назначен —</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(old('assignee_id') == $user->id)>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="status">Статус</label>
            <select id="status" name="status">
                <option value="new" @selected(old('status', 'new') === 'new')>Новая</option>
                <option value="in_progress" @selected(old('status') === 'in_progress')>В работе</option>
                <option value="done" @selected(old('status') === 'done')>Завершена</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="due_date">Срок выполнения</label>
        <input id="due_date" type="date" name="due_date" value="{{ old('due_date') }}">
    </div>
    <div class="form-actions">
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Отмена</a>
        <button type="submit" class="btn btn-primary">Создать задачу</button>
    </div>
</form>
@endsection
