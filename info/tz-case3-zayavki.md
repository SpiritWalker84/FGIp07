# ТЗ — Кейс 3: Трекер заявок (МастерДом)

**База:** https://github.com/SpiritWalker84/Zayavki  
**Проект:** `FGIp07/case3-service-requests/`

## Цель

MVP приёма и распределения заявок для сервисной компании без Excel и мессенджеров.

## Роли

| Роль | Email (демо) | Экран |
|------|--------------|-------|
| dispatcher | `dispatcher@example.com` | `/dispatcher` — все заявки, назначение мастера |
| master | `master1@example.com` | `/master` — «Мои выезды», взять в работу, завершить |

Пароль для всех демо-аккаунтов: `password`

## Статусы заявки

`new` → `assigned` → `in_progress` → `done` (также `canceled`)

## Функции MVP

1. Публичная форма создания заявки (`/requests/create`)
2. Диспетчерская: список, фильтр по статусу, назначение мастера
3. Панель мастера: карточки выездов, «Начать работу» / «Завершить»
4. Защита от race condition при взятии заявки (SELECT FOR UPDATE)

## UI — бренд «МастерДом»

- Бирюза + янтарный акцент, шрифт Plus Jakarta Sans
- Split-screen логин, тёмный sidebar для авторизованных
- Карточки выездов: адрес заголовком, клиент и телефон в одном блоке
- Отличие от кейса №1 (TaskHub — синий kanban)

## Критерии приёмки

- [x] Две вкладки не могут взять одну заявку
- [x] Мастер видит назначенные и свои in_progress
- [x] Статусы отображаются бейджами на UI
- [x] Деплой на VPS, демо-логины работают

## Стек

Laravel 11, MySQL, Docker.

## Деплой

**URL:** http://5.35.94.25:8087

```bash
cd case3-service-requests
docker compose up -d
docker compose exec app php artisan migrate --force --seed  # при первом запуске
```
