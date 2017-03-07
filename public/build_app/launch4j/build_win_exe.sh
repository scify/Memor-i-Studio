#launch4j_application/launch4j.sh $1

#!/bin/sh
LAUNCH4J="$(dirname "$0")"/launch4j_application/launch4j.jar
echo LAUNCH4J: "$LAUNCH4J"
if [ -n "$JAVA_HOME" ]; then
  $JAVA_HOME/bin/java -jar "$LAUNCH4J" "$@"
else
  java -jar "$LAUNCH4J" "$@"
fi
