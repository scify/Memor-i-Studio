#!/usr/bin/env bash

# Main variables
WORKING_PATH=$1
GAME_CONFIG_FILE_NAME=$2

echo "Working path: $WORKING_PATH"
echo "Game config file name: $GAME_CONFIG_FILE_NAME"

docker run --rm -i -v "$WORKING_PATH" amake/innosetup:innosetup6 "$GAME_CONFIG_FILE_NAME"