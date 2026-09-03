# TaskHub

Веб-приложение для управления задачами: создание задач, назначение исполнителей, отслеживание статусов и обсуждение в комментариях.

## Стек технологий

- **Backend**: Laravel 11 (PHP 8.2)
- **База данных**: MySQL 8.0
- **Веб-сервер**: Nginx
- **Контейнеризация**: Docker, Docker Compose
- **Кэш/Очереди**: Redis (опционально)

## Запуск

```bash
# 1. Клонировать репозиторий
git clone https://github.com/SpiritWalker84/TaskHub.git
cd TaskHub

# 2. Создать .env из примера
cp .env.example .env

# 3. Запустить контейнеры
docker compose up -d --build

# 4. Сгенерировать ключ приложения и выполнить миграции
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate

# 5. Открыть в браузере
# http://localhost:8080
```

**Порты по умолчанию**: 8080 (веб), 3307 (MySQL), 6380 (Redis)
