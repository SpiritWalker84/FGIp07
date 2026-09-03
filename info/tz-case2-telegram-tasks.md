# ТЗ — Кейс 2: Task manager в Telegram

**Источник заказа:** https://www.fl.ru/projects/5450726/task-manager---crm-v-telegram-bot.html

## Цель

Автономный Telegram-бот для постановки и контроля задач команды. Исходный код + MySQL.

## Роли

- **admin** — видит все задачи, может закрывать любую
- **user** — свои задачи (создатель или исполнитель)

## Команды бота

| Команда | Описание |
|---------|----------|
| /start | Регистрация |
| /tasks | Список задач |
| /new | Создание задачи (FSM: название → описание) |
| /done \<id\> | Отметить выполненной |
| /help | Справка |

## Модель данных

**users:** telegram_id, username, full_name, role  
**tasks:** title, description, status (new/in_progress/done), creator_id, assignee_id

## Критерии MVP

- [ ] Создание задачи через FSM
- [ ] Список задач с фильтром по правам
- [ ] Смена статуса на done
- [ ] Данные в MySQL, docker-compose для запуска

## Стек

Python 3.12, aiogram 3, aiomysql, Docker.

## Деплой

VPS + systemd или `docker compose up -d`. Требуется `BOT_TOKEN` от @BotFather.
