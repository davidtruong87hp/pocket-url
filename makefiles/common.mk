# ============================================
# Configuration
# ============================================

# Colors for terminal output
BLUE := \033[0;34m
GREEN := \033[0;32m
YELLOW := \033[0;33m
RED := \033[0;31m
NC := \033[0m  # No Color

# Common functions
define log_info
	echo "$(BLUE)ℹ $(1)$(NC)"
endef

define log_success
	echo "$(GREEN)✅ $(1)$(NC)"
endef

define log_warning
	echo "$(YELLOW)⚠ $(1)$(NC)"
endef

define log_error
	echo "$(RED)❌ $(1)$(NC)"
endef