# ТЗ — Кейс 1: Task-трекер (TaskHub)

**Источник заказа:** https://www.fl.ru/projects/4242426/sdelat-task-treker-na-php-i-bootstrap.html

## Цель

MVP внутреннего трекера задач для команды с ролями и базовым контролем прав.

## Роли

| Роль | Возможности |
|------|-------------|
| user | Свои задачи (исполнитель), создание задач |
| manager | Как user + управление задачами команды |
| admin | Все задачи, удаление, фильтр «Все» |

## Функции MVP

1. Регистрация / авторизация
2. CRUD задач: title, description, status, assignee, due_date
3. Комментарии к задаче
4. Фильтры: «Мои задачи», «Я постановщик», «Все» (admin)
5. Канбан-доска по статусам (new / in_progress / done)
6. Смена статуса из списка и канбана

## Критерии приёмки

- [ ] Вход под разными ролями
- [ ] Создание задачи с назначением исполнителя
- [ ] Комментарий сохраняется
- [ ] Канбан отображает задачи по колонкам
- [ ] User не видит чужие задачи (кроме admin)

## Стек

Laravel 11, MySQL, Docker, Bootstrap/CSS.

## Деплой

**URL (локально):** http://localhost:8086

```bash
cd case1-web-tasks
cp .env.example .env   # APP_PORT=8086
docker compose up -d --build
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```
