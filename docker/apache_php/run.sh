#!/bin/sh
set -e

echo "Hello From container"

service apache2 restart

tail -f /dev/null