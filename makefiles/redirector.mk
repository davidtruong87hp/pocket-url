REDIRECTOR_COMPOSE_FILE := infrastructures/docker-compose/apps/redirector-node/docker-compose.yml

##@ Redirector Service

.PHONY: redirector-setup
redirector-setup: ## Setup redirector service
	@$(call log_info,Setting up redirector service...)
	@if [ ! -f apps/redirector-node/.env ]; then \
		cp apps/redirector-node/.env.example apps/redirector-node/.env; \
		$(call log_success,Created apps/redirector/.env); \
	fi
	@$(call log_success,Environment setup complete)

.PHONY: redirector-up
redirector-up: ## Start redirector service
	@$(call log_info,"Starting redirector service...")
	@docker-compose -p $(PROJECT_NAME)-redirector -f $(REDIRECTOR_COMPOSE_FILE) --env-file $(ENV_FILE) up -d
	@$(call log_success,"Redirector started")

.PHONY: redirector-down
redirector-down: ## Stop redirector service
	@$(call log_info,"Stopping redirector service...")
	@docker-compose -p $(PROJECT_NAME)-redirector -f $(REDIRECTOR_COMPOSE_FILE) --env-file $(ENV_FILE) down
	@$(call log_success,"Redirector stopped")

.PHONY: redirector-restart
redirector-restart: redirector-down redirector-up ## Restart redirector service

.PHONY: redirector-build
redirector-build: ## Rebuild redirector containers
	@$(call log_info,"Building redirector containers...")
	@docker-compose -p $(PROJECT_NAME)-redirector -f $(REDIRECTOR_COMPOSE_FILE) --env-file $(ENV_FILE) build
	@$(call log_success,"Build complete")
	make redirector-up

.PHONY: redirector-clean
redirector-clean: ## Clean redirector (remove volumes)
	@$(call log_warning,"Removing redirector volumes...")
	@docker-compose -p $(PROJECT_NAME)-redirector -f $(REDIRECTOR_COMPOSE_FILE) --env-file $(ENV_FILE) down --volumes
	@$(call log_success,"Redirector cleaned")

##@ Redirector - Application

.PHONY: redirector-shell
redirector-shell: ## Access app container shell
	@docker exec -it pocket-url-redirector-web bash

.PHONY: redirector-artisan
redirector-artisan: ## Run artisan command (usage: make redirector-artisan CMD="migrate")
	@docker exec -it pocket-url-redirector-web php artisan $(CMD)

.PHONY: redirector-test
redirector-test: ## Run tests
	@docker exec pocket-url-redirector-web php artisan test --coverage

.PHONY: redirector-logs
redirector-logs: ## Show redirector logs
	@docker-compose -p $(PROJECT_NAME)-redirector -f $(REDIRECTOR_COMPOSE_FILE) --env-file $(ENV_FILE) logs
