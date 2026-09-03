# FGIp07: три трекера — веб, Telegram, заявки

## Краткое описание

Учебное ДЗ «Кейс 4. Разработка трекеров»: **три независимых MVP** в одном репозитории — **task-трекер на Laravel**, **Telegram-бот задач** и **трекер заявок «МастерДом»**. Отклики на биржах не отправлялись; кейсы собраны по реальным заказам FL.ru / Kwork и доработаны под сдачу. Запуск — **Docker Compose** на локальной машине или на своём сервере, когда нужна проверка или демо.

| Кейс | Папка | Заказ / база | Локальный URL |
|------|-------|--------------|---------------|
| **1. TaskHub** | `case1-web-tasks/` | [FL.ru #4242426](https://www.fl.ru/projects/4242426/sdelat-task-treker-na-php-i-bootstrap.html) · [TaskHub](https://github.com/SpiritWalker84/TaskHub) | http://localhost:8086 |
| **2. Telegram task bot** | `case2-telegram-tasks/` | [FL.ru #5450726](https://www.fl.ru/projects/5450726/task-manager---crm-v-telegram-bot.html) | Telegram (нужен `BOT_TOKEN`) |
| **3. МастерДом** | `case3-service-requests/` | Kwork (CRM/заявки) · [Zayavki](https://github.com/SpiritWalker84/Zayavki) | http://localhost:8087 |

**Как запускать.** В каждой папке: `cp .env.example .env`, при необходимости правки портов и секретов, затем `docker compose up -d --build`. Laravel-кейсы: `php artisan key:generate` и `migrate --seed` внутри контейнера `app`. Логи: `docker compose logs -f`. Остановка: `docker compose down`.

**Документация ДЗ:** `FGip07_worknote_filled.docx` (генерация — `python3 fill_workbook.py`, файл локально / на Яндекс.Диске, в git не входит), ТЗ — `info/tz-case*.md`.

Структура кода согласована с [Guide](https://github.com/SpiritWalker84/Guide) (`docs/conventions/`).

## Стек

| Кейс | Технологии |
|------|------------|
| **1** | Laravel 11, PHP 8.2, MySQL 8, Nginx, Redis (опц.), Docker |
| **2** | Python 3, **aiogram** 3, MySQL 8, Docker |
| **3** | Laravel 11, PHP 8.2, MySQL 8, Nginx, Docker; UI бренда «МастерДом» |

## Структура репозитория

| Путь | Назначение |
|------|------------|
| `case1-web-tasks/` | Task-трекер: роли, задачи, канбан, фильтры «мои / постановщик / все» |
| `case2-telegram-tasks/` | Telegram-бот: `/tasks`, `/new`, `/done`, MySQL |
| `case3-service-requests/` | Заявки: форма, диспетчер, мастер, race condition при «взять в работу» |
| `info/tz-case1-taskhub.md` | ТЗ кейса №1 |
| `info/tz-case2-telegram-tasks.md` | ТЗ кейса №2 |
| `info/tz-case3-zayavki.md` | ТЗ кейса №3 (МастерДом) |
| `fill_workbook.py` | Заполнение `FGip07_worknote_filled.docx` |

## Запуск

### Кейс 1 — TaskHub (порт **8086**)

```bash
cd case1-web-tasks
cp .env.example .env
# APP_PORT=8086, APP_URL=http://localhost:8086

docker compose up -d --build
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

Открыть: **http://localhost:8086**

**Демо:** `admin@taskhub.local` / `password`  
**Проверка:** вход → список задач → канбан → фильтры вкладок → создание задачи с исполнителем.

### Кейс 2 — Telegram-бот

```bash
cd case2-telegram-tasks
cp .env.example .env
# BOT_TOKEN от @BotFather, ADMIN_TELEGRAM_ID — ваш Telegram ID

docker compose up -d --build
docker compose logs -f bot
```

**Команды:** `/start`, `/tasks`, `/new`, `/done <id>`, `/help`.

> Один `BOT_TOKEN` не должен одновременно polling из двух процессов (конфликт `getUpdates`).

### Кейс 3 — МастерДом (порт **8087**)

```bash
cd case3-service-requests
cp .env.example .env
# APP_PORT=8087, APP_URL=http://localhost:8087
# DB_PORT в .env оставить 3306 (для Laravel внутри Docker)

docker compose up -d --build
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan view:clear   # после правок Blade/CSS
```

Открыть: **http://localhost:8087**

**Демо:**

| Роль | Email | Пароль |
|------|-------|--------|
| Диспетчер | `dispatcher@example.com` | `password` |
| Мастер | `master1@example.com` | `password` |

**Проверка:** `/requests/create` без входа → диспетчер назначает мастера → мастер «Начать работу» / «Завершить» → две вкладки на одну заявку (одна ошибка).

## Сценарии для скриншотов (тетрадь)

| Кейс | Что снять |
|------|-----------|
| **1** | Логин, список/канбан, создание задачи, фильтр «Мои задачи» |
| **2** | `/start`, список `/tasks`, создание `/new`, `/done` |
| **3** | Логин, форма заявки, диспетчерская, «Мои выезды» мастера |

Скриншоты и ссылку на **Яндекс.Диск** — в тетради вручную.

## Конфигурация

Секреты в git не коммитить. Основные переменные — в `.env.example` каждого кейса.

| Переменная | Кейс | Описание |
|------------|------|----------|
| `APP_PORT` | 1, 3 | Порт на хосте (**8086** / **8087** по умолчанию в ДЗ) |
| `APP_URL` | 1, 3 | URL приложения (`http://localhost:…` локально; при деплое — ваш домен) |
| `DB_PORT` | 1, 3 | **3306** внутри контейнера (не менять на host-порт) |
| `BOT_TOKEN` | 2 | Токен от [@BotFather](https://t.me/BotFather) |
| `ADMIN_TELEGRAM_ID` | 2 | Telegram ID администратора |

## Тетрадь

```bash
python3 fill_workbook.py   # → FGip07_worknote_filled.docx (локально)
```

## Проверка задания (кратко)

1. **Кейс 1:** три роли, CRUD задач, канбан, приложение открывается на `:8086`.
2. **Кейс 2:** бот отвечает на команды, задачи пишутся в MySQL.
3. **Кейс 3:** заявка проходит цепочку статусов, race condition при двойном «взять» не проходит.
4. **Тетрадь:** три кейса заполнены, скрины вставлены, ссылка на Диск указана.

## Лицензия

Учебный проект курса FGIp07.
