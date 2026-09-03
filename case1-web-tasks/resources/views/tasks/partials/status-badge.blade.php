@php
    $labels = ['new' => 'Новая', 'in_progress' => 'В работе', 'done' => 'Завершена'];
@endphp
<span class="badge badge-{{ $status }}">{{ $labels[$status] ?? $status }}</span>
