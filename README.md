# InfoTek

Данный репозиторий содержит выполнение тестового задания с использованием фреймворка Codeception (PHP).
Пример HTML отчета, который сгенерирован встроенными методами Codeception

[report.html](report.html)

## Стек технологий

- PHP - 8.4
- Codeception - 5.3
- Selenium Standalone Chrome - веб-драйвер для UI тестов
- MySQL 8.0 - тестовая БД
- Docker - окружение для запуска тестов

## Структура репозитория

```
.
├── Docker/
│   └── Php/              # Dockerfile для сборки контейнера с PHP/Composer/Codeception
├── Tests/                # Рабочая папка с тестами
│   ├── Acceptance/       # Тесты по UI
│   ├── Api/              # Тесты по Api
│   └── Support/          # Вспомогательны классы
│       ├── Constants/    # Enum классы + Final классы, которых хранят в себе статичную вспомогательную информацию   
│       ├── Data/         # Тестовые данные для тестов (dataProvider)
│       ├── Helper/       # Вспомогательные функции
│       ├── Page/         # Описания Web страниц
│       └── Step/         # Список повторящихся шагов между разными тестами
├── codeception.yml       # Общий конфиг Codeception
├── composer.json
├── composer.lock
├── docker-compose.yaml
└── .gitignore
```

## Требования для запуска

- Docker Desktop

## Настройка окружения

Перед запуском создайте файл `.env`, скопировав `.env.example` в корень проекта и замените `GITHUB_ACCESS_TOKEN` на свой
> `GITHUB_ACCESS_TOKEN` используется при сборке образа для аутентификации Composer в приватных/лимитированных репозиториях GitHub.

## Установка и запуск

1. Склонируйте репозиторий:

   ```bash
   git clone https://github.com/Hellmerise/InfoTek.git
   cd InfoTek
   ```

2. Создайте и заполните `.env` (см. выше).

3. Соберите и поднимите контейнеры (должен быть запущен Docker Destop):

   ```bash
   docker compose build
   docker compose up -d
   ```

4. Установите зависимости внутри контейнера `selenium`:

   ```bash
   docker compose run --rm selenium composer install
   ```

   > Сервис `selenium` не запускает постоянный процесс, поэтому после `up -d` контейнер сразу завершается. Для одноразовых команд (установка зависимостей, запуск тестов) используется `docker compose run --rm ...`.

## Запуск тестов

В `composer.json` определены готовые скрипты для запуска через контейнер `selenium`:

```bash
# Acceptance-тесты
composer run:acceptance

# API-тесты
composer run:api

# Все тесты
composer run:all
```

Под капотом эти команды выполняют, например:

```bash
docker compose run --rm selenium ./vendor/bin/codecept run Acceptance -vvv
```

## Конфигурация Codeception

Основные пути заданы в `codeception.yml`:
Параметры окружения подтягиваются из `.env` через расширение `params`.

## Selenium / Chrome

Контейнер `chrome` поднимает `selenium/standalone-chrome` и пробрасывает порт `4444` (WebDriver). Chrome запускается в режиме `--incognito`, без пароля для VNC (`VNC_NO_PASSWORD=1`), что позволяет визуально наблюдать за ходом тестов при необходимости (http://localhost:4444/ui/#/sessions).

## MySQL

Контейнер `mysql` использует образ `mysql:8.0`, данные хранятся в volume `mysql_data`, порт проброшен на `3307`. Готовность базы проверяется через `healthcheck` (`docker compose exec mysql mysqladmin ping -h localhost -uroot -p`). Если попросят пароль, то можно ввести `root password` из `.env`