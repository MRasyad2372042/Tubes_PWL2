#!/usr/bin/env bash
# POSIX helper to copy Sneat template into public/sneat
# Update SRC to the path where you extracted the template
SRC="/d/Tugas/PWL/sneat-1.0.0/sneat-1.0.0/html"
DEST="$(dirname "$0")/../public/sneat"

if [ ! -d "$SRC" ]; then
  echo "Source folder not found: $SRC" >&2
  echo "Edit the script and set SRC to your template location." >&2
  exit 1
fi

mkdir -p "$DEST"
cp -r "$SRC"/* "$DEST"/
echo "Copied template files to $DEST"
