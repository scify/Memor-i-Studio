#!/usr/bin/env bash
echo Before running the script:
echo - Make sure you have run "xhost +"
echo - Make sure the WINE_BASE_DIR has the www-data as an owner with full access
echo
echo After running the script:
echo - Revert the X permissions by running "xhost -"
# Main variables
INNOSETUP_EXE_PACKAGE=$1

# Init wine
# If wine not present
[ ! -d "$HOME" ] && echo "Initializing Wine..." && mkdir -p $HOME && winecfg && echo "Done."  # Init www-data wine dir and init wine

echo "Setting permissions for WINEBASE dir"
chmod -R 0777 $HOME/.wine/
echo "Done."



# If innosetup has not been installed
if [ ! -f "$HOME/.wine/drive_c/Program\ Files\ \(x86\)/Inno\ Setup\ 5/ISCC.exe" ]; then
	echo "Missing innoSetup. Installing..."
	# Install innosetup
	wine $INNOSETUP_EXE_PACKAGE && # Run local innosetup executable
	##########
	echo "Install done."
fi;

echo "If you received no errors above, then all is fine and installed."
