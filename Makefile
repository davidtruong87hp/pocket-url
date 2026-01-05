# ============================================
# Configuration
# ============================================

# Colors for terminal output
BLUE := \033[0;34m
GREEN := \033[0;32m
YELLOW := \033[0;33m
RED := \033[0;31m
NC := \033[0m  # No Color

SHARED_COMPOSE_FILE := infrastructures/docker-compose/shared/docker-compose.yml
SHORTENER_COMPOSE_FILE := infrastructures/docker-compose/apps/shortener/docker-compose.yml

ENV_FILE := .env.infrastructure

# Include environment variables
include ${ENV_FILE}
export

# ============================================
# Commands
# ============================================
.PHONY: help
help:
	@echo "$(BLUE)PocketURL Platform - Development Commands$(NC)"
	@echo "$(GREEN)Available commands:$(NC)"
	@echo ""
	@echo "$(GREEN)Example workflows:$(NC)"
	@echo "  1. Start full development:  make dev-full"
	@echo "  2. Stop everything:  make dev-stop"
	@echo "  3. Remove everything including volumes:  make dev-down"

.PHONY: setup
setup:
	@echo "Creating infrastructure network..."
	@docker network create ${INFRA_NETWORK} 2>/dev/null || echo "Network ${INFRA_NETWORK} already exists"
	@echo "$(GREEN)Setting up environment...$(NC)"
	@if [ ! -f .env.infrastructure ]; then \
		cp .env.infrastructure.example .env.infrastructure; \
		echo "Created .env.infrastructure - please update it"; \
	fi
	@echo "$(GREEN)Environment setup complete.$(NC)"

.PHONY: shared-up
shared-up:
	@echo "Starting shared infrastructure..."
	docker-compose -p $(PROJECT_NAME)-shared -f $(SHARED_COMPOSE_FILE) up -d --build

.PHONY: dev-full
dev-full: setup shared-up
	@echo "$(GREEN)Starting all services...$(NC)"
	@docker-compose -p ${PROJECT_NAME}-shortener -f $(SHORTENER_COMPOSE_FILE) --env-file $(ENV_FILE) up -d --build
	@echo "$(GREEN)All services started.$(NC)"

.PHONY: dev-stop
dev-stop:
	@echo "$(GREEN)Stopping all services...$(NC)"
	@docker-compose -p ${PROJECT_NAME}-shortener -f $(SHORTENER_COMPOSE_FILE) down
	@docker-compose -p ${PROJECT_NAME} -f $(SHARED_COMPOSE_FILE) down
	@echo "$(GREEN)All services stopped.$(NC)"

.PHONY: dev-down
dev-down:
	@echo "$(GREEN)Removing development environment...$(NC)"
	@docker-compose -p ${PROJECT_NAME}-shortener -f $(SHORTENER_COMPOSE_FILE) down --volumes
	@docker-compose -p ${PROJECT_NAME} -f $(SHARED_COMPOSE_FILE) down --volumes
	@echo "$(GREEN)Development environment removed.$(NC)"

.PHONY: urls
urls:
	@echo "=== Service URLs ==="
	@echo "Shortener API: http://localhost:${SHORTENER_API_PORT}"
	@echo "Redis Insight: http://localhost:5540"
