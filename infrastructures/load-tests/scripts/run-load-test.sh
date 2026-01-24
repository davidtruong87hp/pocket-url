#!/bin/bash

# Load Test Runner
# Easy way to run different load tests

set -e

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
K6_DIR="$SCRIPT_DIR/../k6"
TIMESTAMP=$(date +%Y%m%d-%H%M%S)

# Check if k6 is installed
if ! command -v k6 &> /dev/null; then
    echo -e "${RED}❌ k6 is not installed${NC}"
    echo "Install with: brew install k6"
    echo "Or visit: https://k6.io/docs/get-started/installation/"
    exit 1
fi

# Function to show menu
show_menu() {
    echo -e "${BLUE}╔═══════════════════════════════════════╗${NC}"
    echo -e "${BLUE}║    Pocket URL - Load Test Runner     ║${NC}"
    echo -e "${BLUE}╚═══════════════════════════════════════╝${NC}"
    echo ""
    echo "Select a test to run:"
    echo ""
    echo "  1) Simple Load Test        (2 min,  50 users)"
    echo "  2) Realistic Traffic       (5 min, 100 users)"
    echo "  3) Spike Test              (1 min, 10→500 users)"
    echo "  4) Stress Test            (12 min, 100→500 users)"
    echo "  5) Soak Test              (34 min,  50 users)"
    echo ""
    echo "  6) Run All Tests (except soak)"
    echo "  7) Custom Test (specify parameters)"
    echo ""
    echo "  0) Exit"
    echo ""
}

# Function to run test
run_test() {
    local test_name=$1
    local test_file=$2
    
    echo ""
    echo -e "${GREEN}🚀 Running: $test_name${NC}"
    echo -e "${YELLOW}⏱️  Started at: $(date '+%H:%M:%S')${NC}"
    echo ""
    
    # Run k6 test
    if k6 run "$test_file"; then
        echo ""
        echo -e "${GREEN}✅ Test completed successfully!${NC}"
    else
        echo ""
        echo -e "${RED}❌ Test failed or thresholds not met${NC}"
    fi
    
    echo ""
    echo -e "${BLUE}📊 View live metrics: http://localhost:9091${NC}"
    echo ""
}

# Function to check services
check_services() {
    echo -e "${YELLOW}🔍 Checking services...${NC}"
    
    # Check if app is running
    if curl -s -o /dev/null -w "%{http_code}" http://localhost:3000/abc123 | grep -q "301\|404"; then
        echo -e "${GREEN}✅ Redirector service is running${NC}"
    else
        echo -e "${RED}❌ Redirector service is not responding${NC}"
        echo "   Start it with: docker-compose up -d"
        return 1
    fi
    
    # Check if Prometheus is running
    if curl -s http://localhost:9090/-/healthy > /dev/null 2>&1; then
        echo -e "${GREEN}✅ Prometheus is running${NC}"
    else
        echo -e "${YELLOW}⚠️  Prometheus is not running (optional)${NC}"
        echo "   Start monitoring: cd monitoring && docker-compose up -d"
    fi
    
    # Check if Grafana is running
    if curl -s http://localhost:9091/api/health > /dev/null 2>&1; then
        echo -e "${GREEN}✅ Grafana is running${NC}"
        echo -e "${BLUE}📊 Dashboard: http://localhost:9091${NC}"
    else
        echo -e "${YELLOW}⚠️  Grafana is not running (optional)${NC}"
    fi
    
    echo ""
    return 0
}

# Main menu loop
while true; do
    show_menu
    read -p "Enter your choice [0-7]: " choice
    
    case $choice in
        1)
            check_services && run_test "simple-load" "$K6_DIR/simple-load.js"
            read -p "Press Enter to continue..."
            ;;
        2)
            check_services && run_test "realistic-traffic" "$K6_DIR/realistic-traffic.js"
            read -p "Press Enter to continue..."
            ;;
        3)
            check_services && run_test "spike-test" "$K6_DIR/spike-test.js"
            read -p "Press Enter to continue..."
            ;;
        4)
            check_services && run_test "stress-test" "$K6_DIR/stress-test.js"
            read -p "Press Enter to continue..."
            ;;
        5)
            echo -e "${YELLOW}⚠️  This test runs for 34 minutes!${NC}"
            read -p "Are you sure? [y/N]: " confirm
            if [[ $confirm =~ ^[Yy]$ ]]; then
                check_services && run_test "soak-test" "$K6_DIR/soak-test.js"
            fi
            read -p "Press Enter to continue..."
            ;;
        6)
            echo -e "${GREEN}🧪 Running test suite...${NC}"
            echo ""
            check_services || exit 1
            
            run_test "simple-load" "$K6_DIR/simple-load.js"
            sleep 5
            run_test "realistic-traffic" "$K6_DIR/realistic-traffic.js"
            sleep 5
            run_test "spike-test" "$K6_DIR/spike-test.js"
            sleep 5
            run_test "stress-test" "$K6_DIR/stress-test.js"
            
            echo ""
            echo -e "${GREEN}✅ All tests completed!${NC}"
            read -p "Press Enter to continue..."
            ;;
        7)
            echo ""
            echo "Custom test options:"
            read -p "Number of users (VUs): " vus
            read -p "Duration (e.g., 30s, 2m, 1h): " duration
            read -p "Base URL [http://localhost:3000]: " base_url
            base_url=${base_url:-http://localhost:3000}
            
            echo ""
            echo -e "${GREEN}🚀 Running custom test...${NC}"
            echo "  Users: $vus"
            echo "  Duration: $duration"
            echo "  URL: $base_url"
            echo ""
            
            k6 run --vus "$vus" --duration "$duration" \
                -e BASE_URL="$base_url" \
                "$K6_DIR/simple-load.js"
            
            read -p "Press Enter to continue..."
            ;;
        0)
            echo ""
            echo -e "${GREEN}👋 Goodbye!${NC}"
            echo ""
            exit 0
            ;;
        *)
            echo ""
            echo -e "${RED}❌ Invalid choice. Please try again.${NC}"
            sleep 2
            ;;
    esac
    
    clear
done
