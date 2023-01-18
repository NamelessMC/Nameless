#!/usr/bin/env bash
set -e
cd /data
mkdir -p release
rm -f release/*.zip
rm -f release/*.tar.xz
rm -rf release/upgrade_temp # In case a previous run failed
export PYTHONUNBUFFERED=1
python3 dev/scripts/release.py
rm -rf release/upgrade_temp
