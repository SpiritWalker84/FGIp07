# Кейс 2: Telegram task manager

Бот для постановки и контроля задач команды (заказ FL.ru №5450726).

## Запуск на VPS

```bash
cd case2-telegram-tasks
cp .env.example .env
# Заполните BOT_TOKEN от @BotFather
docker compose up -d --build
docker compose logs -f bot
```

## Команды бота

- `/start` — регистрация
- `/tasks` — список задач
- `/new` — создать задачу (FSM)
- `/done <id>` — отметить выполненной
- `/help` — справка

Демо-админ: первый пользователь с `ADMIN_TELEGRAM_ID` в `.env` или пользователь с role=admin в БД.
