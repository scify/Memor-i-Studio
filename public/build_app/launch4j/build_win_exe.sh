#!/bin/bash
LAUNCH4J="$(dirname "$0")"/launch4j_application/launch4j.jar
echo -e LAUNCH4J: "$LAUNCH4J"
COMMAND=("java -jar $LAUNCH4J $@")
echo -e JAVA_HOME: "$JAVA_HOME"
if [ -n "$JAVA_HOME" ]; then
  COMMAND=("$JAVA_HOME/bin/java -jar $LAUNCH4J $@")
fi
echo -e "${COMMAND[0]}"

eval "${COMMAND[0]}"

echo -e "Game .exe was built."