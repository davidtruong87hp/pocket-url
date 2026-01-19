PORTAL_COMPOSE_FILE := infrastructures/docker-compose/apps/portal/docker-compose.yml

##@ Portal Service

.PHONY: portal-setup
portal-setup: ## Setup portal service
	@$(call log_info,Setting up portal service...)
	@if [ ! -f apps/portal/.env ]; then \
		cp apps/portal/.env.example apps/portal/.env; \
		$(call log_success,Created apps/portal/.env); \
	fi
	@$(call log_success,Environment setup complete)

.PHONY: portal-up
portal-up: ## Start portal service
	@$(call log_info,"Starting portal service...")
	@docker-compose -p $(PROJECT_NAME)-portal -f $(PORTAL_COMPOSE_FILE) --env-file $(ENV_FILE) up -d
	@$(call log_success,"Portal started")

.PHONY: portal-down
portal-down: ## Stop portal service
	@$(call log_info,"Stopping portal service...")
	@docker-compose -p $(PROJECT_NAME)-portal -f $(PORTAL_COMPOSE_FILE) --env-file $(ENV_FILE) down
	@$(call log_success,"Portal stopped")

.PHONY: portal-restart
portal-restart: portal-down portal-up ## Restart portal service

.PHONY: portal-build
portal-build: ## Rebuild portal containers
	@$(call log_info,"Building portal containers...")
	@docker-compose -p $(PROJECT_NAME)-portal  -f $(PORTAL_COMPOSE_FILE) --env-file $(ENV_FILE) build
	@$(call log_success,"Build complete")
	make portal-up

.PHONY: portal-clean
portal-clean: ## Clean portal (remove volumes)
	@$(call log_warning,"Removing portal volumes...")
	@docker-compose -p $(PROJECT_NAME)-portal -f $(PORTAL_COMPOSE_FILE) --env-file $(ENV_FILE) down --volumes
	@$(call log_success,"Portal cleaned")

.PHONY: portal-logs
portal-logs: ## Show portal logs
	@docker-compose -p $(PROJECT_NAME)-portal -f $(SHORTENER_COMPOSE_FILE) --env-file $(ENV_FILE) logs
