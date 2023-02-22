#!/usr/bin/env bash
echo Before running the script:
echo - Make sure you have run "isccBaseSetup.sh" as the non-www-data user
echo "- Make sure the WINEPREFIX has the www-data (as an owner) with full access"
# Main variables
SOURCE_USER=$1
export WINEPREFIX="/home/$SOURCE_USER/.wine/"
echo "Wine base dir: $WINEPREFIX"

SCRIPTNAME=$2
INNO_BIN="Inno Setup 5/ISCC.exe"
# Get inno setup path
if $# -gt 2; then
  INNO_PATH=$3
else
  INNO_PATH="${PROGFILES_PATH%?}/${INNO_BIN}"
fi
# pwd
echo "Looking inno setup in : $INNO_PATH";

# Check if variable is set
[ -z "$SCRIPTNAME" ] && { echo "Usage: $0 <USER_WITH_WINE_INSTALLED> <SCRIPT_NAME>"; echo; exit 1; }

# Check if filename exist
[ ! -f "$SCRIPTNAME" ] && { echo "File not found. Aborting."; echo; exit 1; }

# Check if wine is present
command -v wine >/dev/null 2>&1 || { echo >&2 "I require wine but it's not installed. Aborting."; echo; exit 1; }

# Get Program Files path via wine command prompt
PROGRAMFILES=$(wine cmd /c 'echo %PROGRAMFILES%' )
echo "Got program files path as: $PROGRAMFILES"

# Translate windows path to absolute unix path
PROGFILES_PATH=$(winepath -u "${PROGRAMFILES}" )
echo "Program files translated as linux path: $PROGFILES_PATH"

# Translate unix script path to windows path
SCRIPTNAME=$(winepath -w "$SCRIPTNAME" )

# Check if Inno Setup is installed into wine
[ ! -f "$INNO_PATH" ] && { echo "Install Inno Setup 5 Quickstart before running this script.";  echo; exit 1; }

# Compile!
WINEDLLOVERRIDES="mscoree,mshtml=" wine "$INNO_PATH" "$SCRIPTNAME"