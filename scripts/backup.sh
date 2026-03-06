#!/bin/bash
cd "$(dirname "$0")/.."

BACKUP_DIR="./backups"
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/lifx_backup_$DATE.tar.gz"

mkdir -p $BACKUP_DIR

tar -czf $BACKUP_FILE \
    --exclude="builds/*" \
    --exclude="output/*" \
    --exclude="cache/*" \
    --exclude="logs/*.log" \
    --exclude="node_modules" \
    --exclude=".git" \
    .

if [ $? -eq 0 ]; then
    echo "[$(date)] Backup created: $BACKUP_FILE" >> ./logs/backup.log
    
    find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete
    
    echo "Backup completed: $BACKUP_FILE"
else
    echo "[$(date)] Backup failed" >> ./logs/backup.log
    echo "Backup failed"
    exit 1
fi
