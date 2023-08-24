#!/bin/bash
LAUNCH4J="$(dirname "$0")"/launch4j_application/launch4j.jar
echo LAUNCH4J: "$LAUNCH4J"
COMMAND=("java -jar $LAUNCH4J $@")
if [ -n "$JAVA_HOME" ]; then
  COMMAND=("$JAVA_HOME/bin/java -jar $LAUNCH4J $@")
fi
echo "${COMMAND[0]}"

eval "${COMMAND[0]}"