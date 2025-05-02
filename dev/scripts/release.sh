#!/usr/bin/env bash
set -e
docker build -t nameless-release dev/scripts/release
docker run --rm -u $(id -u) -v "$(pwd):/data" nameless-release
