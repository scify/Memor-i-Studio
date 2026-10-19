#!/bin/bash
# avconv (libav) is no longer packaged on current Debian and Ubuntu; ffmpeg needs -b:a for the same 128k CBR output.
if command -v avconv >/dev/null 2>&1; then
    avconv -i $1 -codec:a libmp3lame -b 128k -minrate 128k -maxrate 128k $2
else
    ffmpeg -i $1 -codec:a libmp3lame -b:a 128k $2
fi
echo $1
