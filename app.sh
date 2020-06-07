#!/usr/bin/env bash

function start() {
    docker-compose up -d
}

function stop() {
    docker-compose stop
}

function php() {
    docker exec -it quiz_php /bin/sh
}

function server() {
    docker exec -it quiz_web /bin/sh
}

function db() {
    docker exec -it quiz_db /bin/sh
}

function composer() {
    cd html
    ./composer update
}

"$@"
