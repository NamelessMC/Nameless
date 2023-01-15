#!/usr/bin/env bash
set -e
cd /data
mkdir -p release
rm -f release/*
python3 dev/scripts/release.py
