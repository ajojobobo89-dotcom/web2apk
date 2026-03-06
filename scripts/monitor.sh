#!/bin/bash
cd "$(dirname "$0")/.."

LOG_FILE="./logs/monitor.log"
ALERT_EMAIL="diazalif807@gmail.com"

check_service() {
    curl -s -o /dev/null -w "%{http_code}" http://localhost/api/status.php | grep -q 200
    return $?
}

check_disk_space() {
    USAGE=$(df -h . | awk 'NR==2 {print $5}' | sed 's/%//')
    if [ $USAGE -gt 90 ]; then
        return 1
    fi
    return 0
}

check_build_queue() {
    QUEUE_COUNT=$(find ./builds -type f -name "*.building" 2>/dev/null | wc -l)
    if [ $QUEUE_COUNT -gt 50 ]; then
        return 1
    fi
    return 0
}

echo "[$(date)] Starting monitoring check..." >> $LOG_FILE

if ! check_service; then
    echo "[$(date)] ALERT: API service is down!" >> $LOG_FILE
    echo "API service is down on $(hostname)" | mail -s "LIFX Service Alert" $ALERT_EMAIL
fi

if ! check_disk_space; then
    echo "[$(date)] ALERT: Disk space critical!" >> $LOG_FILE
    echo "Disk space critical on $(hostname)" | mail -s "LIFX Disk Alert" $ALERT_EMAIL
fi

if ! check_build_queue; then
    echo "[$(date)] ALERT: Build queue overloaded!" >> $LOG_FILE
fi

echo "[$(date)] Monitoring check completed" >> $LOG_FILE
