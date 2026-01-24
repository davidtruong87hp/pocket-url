# ============================================
# LOAD TESTING COMMANDS
# ============================================
.PHONY: load-test load-test-simple load-test-realistic load-test-spike load-test-stress load-test-soak

load-test: ## Run load test (interactive menu)
	@cd infrastructures/load-tests/scripts && ./run-load-test.sh

load-test-simple: ## Run simple load test (2min)
	@cd infrastructures/load-tests && k6 run k6/simple-load.js

load-test-realistic: ## Run realistic traffic test (5min)
	@cd infrastructures/load-tests && k6 run k6/realistic-traffic.js

load-test-spike: ## Run spike test (1min)
	@cd infrastructures/load-tests && k6 run k6/spike-test.js

load-test-stress: ## Run stress test (12min)
	@cd infrastructures/load-tests && k6 run k6/stress-test.js

load-test-soak: ## Run soak test (34min)
	@cd infrastructures/load-tests && k6 run k6/soak-test.js
