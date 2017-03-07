#!/usr/bin/env bash
echo Before running the script:
echo - Make sure you have run "xhost +"
echo - Make sure the WINE_BASE_DIR has the www-data as an owner with full access
echo
echo After running the script:
echo - Revert the X permissions by running "xhost -"
# Main variables
SOURCE_USER=$1
WINE_BASE_DIR="/home/$SOURCE_USER/winebase/"
INNOSETUP_EXE_PACKAGE=$3

# Init wine
. /home/$SOURCE_USER/.bashrc
export HOME=$WINE_BASE_DIR # Switch www-data home to the new dir

# If wine not present
[ ! -d "$HOME" ] && mkdir -p $HOME && winecfg  # Init www-data wine dir and init wine

# If innosetup has not been installed
if [ ! -f "$HOME/.wine/drive_c/Program\ Files\ \(x86\)/Inno\ Setup\ 5/ISCC.exe" ]; then
	echo "Missing innoSetup. Installing..."
	# Install innosetup
	wine $INNOSETUP_EXE_PACKAGE && # Run local innosetup executable
	##########
	echo "Install done."
fi;

SCRIPTNAME=$2
INNO_BIN="Inno Setup 5/ISCC.exe"

# Check if variable is set
[ -z "$SCRIPTNAME" ] && { echo "Usage: $0 <SCRIPT_NAME>"; echo; exit 1; }

# Check if filename exist
[ ! -f "$SCRIPTNAME" ] && { echo "File not found. Aborting."; echo; exit 1; }

# Check if wine is present
command -v wine >/dev/null 2>&1 || { echo >&2 "I require wine but it's not installed. Aborting."; echo; exit 1; }

# Get Program Files path via wine command prompt
PROGRAMFILES=$(wine cmd /c 'echo %PROGRAMFILES%' 2>/dev/null)

# Translate windows path to absolute unix path
PROGFILES_PATH=$(winepath -u "${PROGRAMFILES}" 2>/dev/null)

# Get inno setup path
INNO_PATH="${PROGFILES_PATH%?}/${INNO_BIN}"
pwd
echo "$INNO_PATH";
# Translate unix script path to windows path 
SCRIPTNAME=$(winepath -w "$SCRIPTNAME" 2> /dev/null)

# Check if Inno Setup is installed into wine
[ ! -f "$INNO_PATH" ] && { echo "Install Inno Setup 5 Quickstart before running this script.";  echo; exit 1; }

# Compile!
WINEDLLOVERRIDES="mscoree,mshtml=" wine "$INNO_PATH" "$SCRIPTNAME"
