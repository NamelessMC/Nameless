#!/usr/bin/env bash
set -e
docker run -it --rm -u $(id -u) -v "$(pwd):/data" --entrypoint="/data/dev/scripts/release-entrypoint.sh" namelessmc/php:dev
