#!/usr/bin/env bash

function start() {
    docker-compose up -d
}

function stop() {
    docker-compose stop
}

function php() {
    docker exec -it quiz_php /bin/bash
}

function server() {
    docker exec -it quiz_web /bin/bash
}

function db() {
    docker exec -it quiz_db /bin/bash
}

function composer() {
    cd html
    ./composer update
}

"$@"