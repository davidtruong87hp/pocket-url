SHORTENER_COMPOSE_FILE := infrastructures/docker-compose/apps/shortener/docker-compose.yml

##@ Shortener Service

.PHONY: shortener-setup
shortener-setup: ## Setup shortener service
	@$(call log_info,Setting up shortener service...)
	@if [ ! -f apps/shortener/.env ]; then \
		cp apps/shortener/.env.example apps/shortener/.env; \
		$(call log_success,Created apps/shortener/.env); \
	fi
	@$(call log_success,Environment setup complete)

.PHONY: shortener-up
shortener-up: ## Start shortener service
	@$(call log_info,"Starting shortener service...")
	@docker-compose -p $(PROJECT_NAME)-shortener -f $(SHORTENER_COMPOSE_FILE) --env-file $(ENV_FILE) up -d
	@$(call log_success,"Shortener started")

.PHONY: shortener-down
shortener-down: ## Stop shortener service
	@$(call log_info,"Stopping shortener service...")
	@docker-compose -p $(PROJECT_NAME)-shortener -f $(SHORTENER_COMPOSE_FILE) --env-file $(ENV_FILE) down
	@$(call log_success,"Shortener stopped")

.PHONY: shortener-restart
shortener-restart: shortener-down shortener-up ## Restart shortener service

.PHONY: shortener-build
shortener-build: ## Rebuild shortener containers
	@$(call log_info,"Building shortener containers...")
	@docker-compose -p $(PROJECT_NAME)-shortener  -f $(SHORTENER_COMPOSE_FILE) --env-file $(ENV_FILE) build
	make shortener-up
	@$(call log_success,"Build complete")

.PHONY: shortener-clean
shortener-clean: ## Clean shortener (remove volumes)
	@$(call log_warning,"Removing shortener volumes...")
	@docker-compose -p $(PROJECT_NAME)-shortener -f $(SHORTENER_COMPOSE_FILE) --env-file $(ENV_FILE) down --volumes
	@$(call log_success,"Shortener cleaned")

##@ Shortener - Application

.PHONY: shortener-shell
shortener-shell: ## Access app container shell
	@docker exec -it pocket-url-shortener-api sh

.PHONY: shortener-artisan
shortener-artisan: ## Run artisan command (usage: make shortener-artisan CMD="migrate")
	@docker exec -it pocket-url-shortener-api php artisan $(CMD)

.PHONY: shortener-test
shortener-test: ## Run tests
	@docker exec pocket-url-shortener-api php artisan test --coverage

.PHONY: shortener-logs
shortener-logs: ## Show shortener logs
	@docker-compose -p $(PROJECT_NAME)-shortener -f $(SHORTENER_COMPOSE_FILE) --env-file $(ENV_FILE) logs

##@ Shortener - Workers

.PHONY: shortener-worker-shell
shortener-worker-shell: ## Access worker container shell
	@docker exec -it pocket-url-shortener-worker bash

.PHONY: shortener-worker-status
shortener-worker-status: ## Show worker status
	@docker exec pocket-url-shortener-worker supervisorctl status

.PHONY: shortener-worker-restart
shortener-worker-restart: ## Restart all workers
	@$(call log_info,"Restarting workers...")
	@docker exec pocket-url-shortener-worker supervisorctl restart shortener-api:*
	@$(call log_success,"Workers restarted")

.PHONY: shortener-worker-restart-queue
shortener-worker-restart-queue: ## Restart queue workers only
	@docker exec pocket-url-shortener-worker supervisorctl restart shortener-worker:*
	@$(call log_success,"Queue workers restarted")

.PHONY: shortener-worker-restart-scheduler
shortener-worker-restart-scheduler: ## Restart scheduler only
	@docker exec pocket-url-shortener-worker supervisorctl restart shortener-scheduler
	@$(call log_success,"Scheduler restarted")

.PHONY: shortener-urls
shortener-urls: ## Show shortener URLs
	@echo "$(BOLD)=== Shortener API Service ===$(NC)"
	@echo "API: http://localhost:$(SHORTENER_API_PORT)"
