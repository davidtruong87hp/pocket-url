ANALYTICS_COMPOSE_FILE := infrastructures/docker-compose/apps/analytics/docker-compose.yml

##@ Analytics Service

.PHONY: analytics-setup
analytics-setup: ## Setup analytics service
	@$(call log_info,Setting up analytics service...)
	@if [ ! -f apps/analytics/.env ]; then \
		cp apps/analytics/.env.example apps/analytics/.env; \
		$(call log_success,Created apps/analytics/.env); \
	fi
	@$(call log_success,Environment setup complete)

.PHONY: analytics-up
analytics-up: ## Start analytics service
	@$(call log_info,"Starting analytics service...")
	@docker-compose -p $(PROJECT_NAME)-analytics -f $(ANALYTICS_COMPOSE_FILE) --env-file $(ENV_FILE) up -d
	@$(call log_success,"Analytics started")

.PHONY: analytics-down
analytics-down: ## Stop analytics service
	@$(call log_info,"Stopping analytics service...")
	@docker-compose -p $(PROJECT_NAME)-analytics -f $(ANALYTICS_COMPOSE_FILE) --env-file $(ENV_FILE) down
	@$(call log_success,"Analytics stopped")

.PHONY: analytics-restart
analytics-restart: analytics-down analytics-up ## Restart analytics service

.PHONY: analytics-build
analytics-build: ## Rebuild analytics containers
	@$(call log_info,"Building analytics containers...")
	@docker-compose -p $(PROJECT_NAME)-analytics  -f $(ANALYTICS_COMPOSE_FILE) --env-file $(ENV_FILE) build
	@$(call log_success,"Build complete")
	make analytics-up

.PHONY: analytics-clean
analytics-clean: ## Clean analytics (remove volumes)
	@$(call log_warning,"Removing analytics volumes...")
	@docker-compose -p $(PROJECT_NAME)-analytics -f $(ANALYTICS_COMPOSE_FILE) --env-file $(ENV_FILE) down --volumes
	@$(call log_success,"Analytics cleaned")

##@ Analytics - Application

.PHONY: analytics-shell
analytics-shell: ## Access app container shell
	@docker exec -it pocket-url-analytics-api bash

.PHONY: analytics-artisan
analytics-artisan: ## Run artisan command (usage: make analytics-artisan CMD="migrate")
	@docker exec -it pocket-url-analytics-api php artisan $(CMD)

.PHONY: analytics-test
analytics-test: ## Run tests
	@docker exec pocket-url-analytics-api php artisan test --coverage

.PHONY: analytics-logs
analytics-logs: ## Show analytics logs
	@docker-compose -p $(PROJECT_NAME)-analytics -f $(SHORTENER_COMPOSE_FILE) --env-file $(ENV_FILE) logs
