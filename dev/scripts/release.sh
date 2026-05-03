#!/usr/bin/env bash
set -e
podman build -t nameless-release dev/scripts/release
podman run --rm -v "$(pwd):/data:z" nameless-release
