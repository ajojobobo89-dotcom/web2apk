#!/bin/bash
cd "$(dirname "$0")/.."

BACKUP_DIR="/backups/lifx"
DATE=$(date +%Y%m%d)
BACKUP_FILE="$BACKUP_DIR/daily_$DATE.sql"

mkdir -p $BACKUP_DIR

mysqldump -u lifx_user -p'password' lifx_web2apk > $BACKUP_FILE

gzip $BACKUP_FILE

find $BACKUP_DIR -name "daily_*.sql.gz" -mtime +7 -delete

echo "[$(date)] Database backup completed" >> ./logs/backup.log
