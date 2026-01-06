ENV_FILE := .env.infrastructure

# Load environment variables
ifneq (,$(wildcard $(ENV_FILE)))
    include $(ENV_FILE)
    export
endif

# Include sub-makefiles
include makefiles/common.mk
include makefiles/infrastructure.mk
include makefiles/shortener.mk
include makefiles/redirector.mk

.DEFAULT_GOAL := help

# ============================================
# Main Commands
# ============================================
.PHONY: help
help:
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make $(CYAN)<target>$(NC)\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  $(CYAN)%-20s$(NC) %s\n", $$1, $$2 } /^##@/ { printf "\n$(BOLD)%s$(NC)\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

.PHONY: setup
setup: infra-setup shortener-setup redirector-setup ## Complete initial setup
	@echo "$(GREEN)✅ All setup complete!$(NC)"

.PHONY: up
up: setup shared-up shortener-up redirector-up ## Start all services
	@echo "$(GREEN)✅ All services started!$(NC)"

.PHONY: down
down: shortener-down redirector-down shared-down ## Stop all services
	@echo "$(GREEN)✅ All services stopped!$(NC)"

.PHONY: clean
clean: shortener-clean redirector-clean shared-clean ## Clean all services
	@echo "$(GREEN)✅ All services cleaned!$(NC)"

.PHONY: restart
restart: down up ## Restart all services