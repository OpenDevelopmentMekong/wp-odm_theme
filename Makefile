USER=$(shell id -u)
GROUP=$(shell id -g)
NODE_OPTS=-w "/usr/src" --rm -v "$(realpath .):/usr/src" -ti -u $(USER):$(GROUP)
NODE_IMAGE=node:8

DOCKER=docker run $(NODE_OPTS) $(NODE_IMAGE)
NPM=$(DOCKER) npm

help:
	@echo "init: install all of the node_module dependencies"
	@echo "build: build js/css"

build:
	$(DOCKER) node_modules/.bin/gulp

init:
	$(NPM) install .
	$(NPM) install bower
	$(DOCKER) ./node_modules/.bin/bower install

bash:
	$(DOCKER) bash
