#!/bin/bash
cd "$(dirname "$0")/.."

if [ -z "$1" ]; then
    echo "Usage: $0 <backup_file>"
    exit 1
fi

BACKUP_FILE="$1"

if [ ! -f "$BACKUP_FILE" ]; then
    echo "Backup file not found: $BACKUP_FILE"
    exit 1
fi

echo "WARNING: This will overwrite existing files!"
read -p "Continue? (y/N): " confirm

if [ "$confirm" != "y" ] && [ "$confirm" != "Y" ]; then
    echo "Restore cancelled"
    exit 0
fi

tar -xzf "$BACKUP_FILE" --overwrite

if [ $? -eq 0 ]; then
    echo "[$(date)] Restore completed from: $BACKUP_FILE" >> ./logs/restore.log
    echo "Restore completed successfully"
else
    echo "[$(date)] Restore failed from: $BACKUP_FILE" >> ./logs/restore.log
    echo "Restore failed"
    exit 1
fi
