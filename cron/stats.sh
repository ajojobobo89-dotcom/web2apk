#!/bin/bash
cd "$(dirname "$0")/.."

STATS_FILE="./builds/stats.json"
LOG_FILE="./logs/stats.log"

if [ ! -f "$STATS_FILE" ]; then
    echo '{"total_builds":0,"today_builds":0,"success_rate":100,"avg_build_time":0,"daily":{}}' > $STATS_FILE
fi

BUILD_COUNT=$(find ./output -name "*.apk" -mtime -1 | wc -l)
FAIL_COUNT=$(grep -c "Build failed" ./logs/build.log 2>/dev/null || echo 0)
TOTAL=$(jq '.total_builds' $STATS_FILE)
NEW_TOTAL=$((TOTAL + BUILD_COUNT))

jq --arg date "$(date +%Y-%m-%d)" \
   --arg count "$BUILD_COUNT" \
   --arg total "$NEW_TOTAL" \
   '.daily[$date] = ($count|tonumber) | .total_builds = ($total|tonumber) | .today_builds = ($count|tonumber)' \
   $STATS_FILE > ${STATS_FILE}.tmp

mv ${STATS_FILE}.tmp $STATS_FILE

echo "[$(date)] Stats updated: $BUILD_COUNT new builds" >> $LOG_FILE
