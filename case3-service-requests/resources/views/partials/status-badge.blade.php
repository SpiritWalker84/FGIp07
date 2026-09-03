@php
    $labels = [
        'new' => 'Новая',
        'assigned' => 'Назначена',
        'in_progress' => 'В работе',
        'done' => 'Выполнена',
        'canceled' => 'Отменена',
    ];
@endphp
<span class="status-pill status-{{ $status }}">{{ $labels[$status] ?? $status }}</span>
