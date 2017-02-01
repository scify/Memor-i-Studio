#!/bin/bash
echo $3 | jarsigner -keystore memoristore -signedjar $1 $2 scify 2>&1