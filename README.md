# Comments API

API для системы комментариев к контенту на Laravel.

## Стек

- PHP 8.4
- Laravel 11
- MySQL 8
- Docker
- Swagger (L5-Swagger)

## Быстрый старт

```bash
# Собрать и запустить проект
make install

# Или поэтапно:
# 1. Собрать и запустить контейнеры
make up

# 2. Установить зависимости и мигрировать БД
make setup
```

## Основные команды

```bash
make up          # Запустить контейнеры
make down        # Остановить контейнеры
make restart     # Перезапустить контейнеры
make logs        # Посмотреть логи

make migrate     # Запустить миграции
make swagger     # Сгенерировать Swagger документацию

make shell       # Войти в контейнер приложения
```

## API

- **Базовый URL**: http://localhost:8080/api/v1
- **Документация**: http://localhost:8080/api/documentation

### Эндпоинты

**Новости**
- `GET /news` - список
- `POST /news` - создание
- `GET /news/{id}` - чтение с комментариями (пагинация: `?cursor=...&per_page=15`)
- `PUT /news/{id}` - обновление
- `DELETE /news/{id}` - удаление

**Видео посты**
- `GET /video-posts` - список
- `POST /video-posts` - создание
- `GET /video-posts/{id}` - чтение с комментариями (пагинация)
- `PUT /video-posts/{id}` - обновление
- `DELETE /video-posts/{id}` - удаление

**Комментарии**
- `POST /comments` - создание
- `GET /comments/{id}` - просмотр
- `PUT /comments/{id}` - обновление (проверка автора)
- `DELETE /comments/{id}` - удаление (soft delete, проверка автора)

**Пользователи**
- `GET /users` - список
- `GET /users/{id}` - просмотр

## Архитектура

- **Controller** → **Service** → **Repository** → **Model**
- Полиморфные связи для комментариев
- Курсорная пагинация
- Мягкое удаление комментариев

## Структура БД

**users**: id, name, email

**news**: id, title, description

**video_posts**: id, title, description

**comments**: id, user_id, commentable_type, commentable_id, parent_id (nullable), content, deleted_at
