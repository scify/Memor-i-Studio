#!/bin/bash
avconv -i $1 -codec:a libmp3lame -b 128k -minrate 128k -maxrate 128k $2
echo $1