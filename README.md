**Для полного клонирования с подмодулями нужно вызывать**

```
$ git clone git@github.com:wyster/phalcon-workspacex.git
$ git submodule init
$ git submodule update
```

**Запуск контейнеров**

1. Настройка, запустить `make setup`, при желании можно поменять коннект к базе данных и другие переменные в .env

2. Необходимо создать `docker-compose.override.yml` и определиться с веб сервером:

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
    tty: true
    restart: always
  users_nginx:
    container_name: ${USERS_CONTAINER_NAME}_nginx
    image: nginx:alpine
    volumes:
      - ./users/conf/nginx/nginx-site.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - users
    tty: true
    restart: always
  site:
    environment:
      USERS_CONTAINER_NAME: ${USERS_CONTAINER_NAME}_nginx
```

Скопировать конфиг предпочитаемого сервера и вставить его в `docker-compose.override.yml`

3. Выполнить `$ docker-compose up`

Через env можно передать желаемый порт, по умолчанию 80

Пример запуска на порту 8080

`$ PORT=8080 docker-compose up`

Так же можно передать USERS_CONTAINER_NAME и SITE_CONTAINER_NAME
 
Это имена контейнеров и они доступны из php в массиве $_ENV

Другие доступные переменные можно посмотерть в .env

**Тесты**

```
$ make site-unit-test
$ make users-unit-test
```


**Покрытие**

```
$ make site-coverage
$ make users-coverage
```

