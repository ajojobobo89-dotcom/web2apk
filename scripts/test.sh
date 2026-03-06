#!/bin/bash
cd "$(dirname "$0")/.."

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo "Testing LIFX Web2APK Installation"
echo "=================================="

check() {
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓${NC} $1"
    else
        echo -e "${RED}✗${NC} $1"
        exit 1
    fi
}

echo -e "\n${YELLOW}Checking directories...${NC}"
[ -d "api" ] && check "API directory exists"
[ -d "assets" ] && check "Assets directory exists"
[ -d "builds" ] && check "Builds directory exists"
[ -d "output" ] && check "Output directory exists"

echo -e "\n${YELLOW}Checking PHP...${NC}"
php -v > /dev/null 2>&1 && check "PHP installed"
php -m | grep -q curl && check "PHP curl extension"

echo -e "\n${YELLOW}Checking Android SDK...${NC}"
[ -d "$ANDROID_SDK_PATH" ] && check "Android SDK found"

echo -e "\n${YELLOW}Testing API endpoints...${NC}"
curl -s -o /dev/null -w "%{http_code}" http://localhost/api/status.php | grep -q 200 && check "Status API"
curl -s -o /dev/null -w "%{http_code}" http://localhost/api/stats.php | grep -q 200 && check "Stats API"

echo -e "\n${GREEN}All tests passed!${NC}"
