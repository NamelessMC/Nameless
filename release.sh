#!/usr/bin/env bash
set -e
docker run --rm -u $(id -u) -v "$(pwd):/data" --entrypoint="/data/scripts/release-entrypoint.sh" namelessmc/php:dev
