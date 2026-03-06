#!/bin/bash
cd "$(dirname "$0")/.."

BUILDS_DIR="./builds"
OUTPUT_DIR="./output"
LOG_FILE="./cleanup.log"

echo "[$(date)] Starting cleanup..." >> $LOG_FILE

find $BUILDS_DIR -type f -mtime +1 -delete 2>> $LOG_FILE
find $OUTPUT_DIR -type f -mtime +1 -delete 2>> $LOG_FILE

find $BUILDS_DIR -type d -empty -delete 2>> $LOG_FILE

echo "[$(date)] Cleanup completed" >> $LOG_FILE
