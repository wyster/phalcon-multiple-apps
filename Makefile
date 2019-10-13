help:
	@echo ""
	@echo "usage: make COMMAND"
	@echo ""
	@echo "Commands:"
	@echo "  setup               Setup default setting for simple run"
	@echo "  logs                Follow log output"

setup:
	@bash -c "cp -n ./.env.example ./.env"

logs:
	@docker-compose logs -f

site-unit-test:
	@bash -c "cd site && make unit-test env-file=`pwd`/.env"

users-unit-test:
	@bash -c "cd users && make unit-test env-file=`pwd`/.env"

site-coverage:
	@bash -c "cd site && make coverage"

users-coverage:
	@bash -c "cd users && make coverage"
