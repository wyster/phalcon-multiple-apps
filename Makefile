include .env
export

help:
	@echo ""
	@echo "usage: make COMMAND"
	@echo ""
	@echo "Commands:"
	@echo "  logs                Follow log output"


logs:
	@docker-compose logs -f
