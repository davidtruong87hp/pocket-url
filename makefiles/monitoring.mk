MONITORING_COMPOSE_FILE := infrastructures/docker-compose/shared/monitoring/docker-compose.yml

.PHONY: monitoring-start
monitoring-start: ## Start monitoring stack
	@$(call log_info,"Starting monitoring stack...")
	@docker-compose -p $(PROJECT_NAME)-monitoring -f $(MONITORING_COMPOSE_FILE) up -d
	@$(call log_success,"Monitoring stack started")

.PHONY: monitoring-build
monitoring-build: ## Build monitoring stack
	@$(call log_info,"Building monitoring stack...")
	@docker-compose -p $(PROJECT_NAME)-monitoring -f $(MONITORING_COMPOSE_FILE) build
	@$(call log_success,"Monitoring stack built")
	make monitoring-start

.PHONY: monitoring-stop
monitoring-stop:
	@$(call log_info,"Stopping monitoring stack...")
	@docker-compose -p $(PROJECT_NAME)-monitoring -f $(MONITORING_COMPOSE_FILE) stop
	@$(call log_success,"Monitoring stack stopped")

.PHONY: monitoring-down
monitoring-down:
	@$(call log_info,"Removing monitoring stack...")
	@docker-compose -p $(PROJECT_NAME)-monitoring -f $(MONITORING_COMPOSE_FILE) down
	@$(call log_success,"Monitoring stack removed")

.PHONY: monitoring-restart
monitoring-restart: monitoring-down monitoring-start

.PHONY: monitoring-clean
monitoring-clean: ## Stop and remove everything including volumes
	@$(call log_warning,"This will delete all monitoring data!")
	@$(call log_info,"Cleaning up monitoring stack...")
	@docker-compose -p $(PROJECT_NAME)-monitoring -f $(MONITORING_COMPOSE_FILE) down -v
	@$(call log_success,"Cleanup completed")
