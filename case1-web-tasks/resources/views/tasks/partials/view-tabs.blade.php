@php
    $currentView = $view ?? request('view', 'involved');
    $routeName = Route::currentRouteName();
@endphp
<div class="view-tabs" role="tablist" aria-label="Фильтр задач">
    <a href="{{ route($routeName, ['view' => 'involved']) }}"
       role="tab"
       @class(['view-tab', 'active' => $currentView === 'involved'])>
        Мои задачи
    </a>
    <a href="{{ route($routeName, ['view' => 'created']) }}"
       role="tab"
       @class(['view-tab', 'active' => $currentView === 'created'])>
        Я — постановщик
    </a>
    @auth
        @if(auth()->user()->isAdmin())
            <a href="{{ route($routeName, ['view' => 'all']) }}"
               role="tab"
               @class(['view-tab', 'active' => $currentView === 'all'])>
                Все задачи
            </a>
        @endif
    @endauth
</div>
