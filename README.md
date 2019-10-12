**Для полного клонирования с подмодулями нужно вызывать**

```
$ git clone git@github.com:wyster/phalcon-workspacex.git
$ git submodule init
$ git submodule update
```

**Запуск контейнеров**

1. Необходимо создать `docker-compose.override.yml` и определиться с веб сервером:

_Запуск контейнеров с внутренним php сервером_

```yaml
version: '3'
services:
  site:
    environment:
      USE_PHP_INTERNAL_SERVER: 1
    ports:
      - "${PORT}:80"
  users:
    environment:
      USE_PHP_INTERNAL_SERVER: 1
```

_Запуск контейнеров с nginx сервером_

```yaml
version: '3'
services:
  site_nginx:
    container_name: ${SITE_CONTAINER_NAME}_nginx
    image: nginx:alpine
    volumes:
      - ./site/conf/nginx/nginx-site.conf:/etc/nginx/conf.d/default.conf
    ports:
      - "${PORT}:80"
    depends_on:
      - site
  users_nginx:
    container_name: ${USERS_CONTAINER_NAME}_nginx
    image: nginx:alpine
    volumes:
      - ./users/conf/nginx/nginx-site.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - users
  site:
    tty: true`
    environment:
      USERS_CONTAINER_NAME: ${USERS_CONTAINER_NAME}_nginx
      # Включает xdebug
      #ENABLE_XDEBUG: 1
  users:
    tty: true
```

Скопировать конфиг предпочитаемого сервера и вставить его в `docker-compose.override.yml`

2. Для того чтобы проверить работоспособность нужна база данных, можно использовать sqlite, для этого создать
`users/app/config/config.local.php` с следующим содержимым

```php
<?php

declare(strict_types=1);

return new \Phalcon\Config([
    'database' => [
        'adapter' => 'Sqlite',
        'dbname' => BASE_PATH . '/data/sqlite.db',
        'schema' => '',
    ],
]);

```
3. Выполнить `$ docker-compose up`

Через env можно передать желаемый порт, по умолчанию 80

Пример запуска на порту 8080

`$ PORT=8080 docker-compose up`

Так же можно передать USERS_CONTAINER_NAME и SITE_CONTAINER_NAME
 
Это имена контейнеров и они доступны из php в массиве $_ENV


Другие доступные переменные можно посмотерть в .env


**Тесты**

```
$ cd site && make unit-test && cd -
$ cd users && make unit-test && cd -
```


**Покрытие**

```
$ cd site && make coverage && cd -
$ cd users && make coverage && cd -
```

