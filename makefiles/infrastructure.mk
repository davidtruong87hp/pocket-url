SHARED_COMPOSE_FILE := infrastructures/docker-compose/shared/docker-compose.yml

.PHONY: infra-setup
infra-setup: ## Setup infrastructure (network, env files)
	@$(call log_info,Setting up infrastructure...)
	@docker network create ${INFRA_NETWORK} 2>/dev/null || $(call log_warning,Network already exists)
	@if [ ! -f .env.infrastructure ]; then \
		cp .env.infrastructure.example .env.infrastructure; \
		$(call log_success,Created .env.infrastructure); \
	fi
	@$(call log_success,Environment setup complete)

.PHONY: shared-up
shared-up:
	@$(call log_info,"Starting shared infrastructure services...")
	docker-compose -p $(PROJECT_NAME)-shared -f $(SHARED_COMPOSE_FILE) up -d
	@$(call log_success,"Shared infrastructure started")

.PHONY: shared-down
shared-down: ## Stop shared infrastructure
	@$(call log_info,"Stopping shared infrastructure...")
	@docker-compose -p $(PROJECT_NAME)-shared -f $(SHARED_COMPOSE_FILE) down
	@$(call log_success,"Shared infrastructure stopped")

.PHONY: shared-build
shared-build: ## Rebuild shared infrastructure containers
	@$(call log_info,"Building shared infrastructure containers...")
	@docker-compose -p $(PROJECT_NAME)-shared -f $(SHARED_COMPOSE_FILE) build
	@$(call log_success,"Build complete")

.PHONY: shared-clean
shared-clean: ## Clean shared infrastructure (remove volumes)
	@$(call log_warning,"Removing infrastructure volumes...")
	@docker-compose -p $(PROJECT_NAME)-shared -f $(SHARED_COMPOSE_FILE) down --volumes
	@$(call log_success,"Infrastructure cleaned")

.PHONY: infra-urls
infra-urls: ## Show infrastructure URLs
	@echo "$(BOLD)=== Infrastructure Services ===$(NC)"
	@echo "RedisInsight: http://localhost:5540"
	@echo "RabbitMQ UI: http://localhost:15672"
