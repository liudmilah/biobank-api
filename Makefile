init: docker-down-clear api-clear docker-pull docker-build docker-up \
    api-permissions api-composer-install \
    api-wait-db api-wait-cache \
    api-migrations-up api-fixtures
init-ci: docker-create-traefik-network init
up: docker-up
down: docker-down
restart: down up
lint: api-lint api-analyze api-validate-schema
update-deps: api-composer-update restart

api-test:
	docker-compose run --rm bb-api-php-cli composer test

api-test-unit:
	docker-compose run --rm bb-api-php-cli composer test -- --testsuite=unit

api-test-functional:
	docker-compose run --rm bb-api-php-cli composer test -- --testsuite=functional

api-coverage:
	docker-compose run --rm bb-api-php-cli composer test-coverage

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-create-traefik-network:
	docker network create traefik-public

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build --pull

api-clear:
	docker run --rm -v ${PWD}:/app -w /app alpine sh -c 'rm -rf var/cache/* var/log/* var/test/*'

api-permissions:
	docker run --rm -v ${PWD}:/app -w /app alpine chmod 777 var/cache var/log var/test || true

api-composer-install:
	docker-compose run --rm bb-api-php-cli composer install

api-composer-update:
	docker-compose run --rm bb-api-php-cli composer update

api-wait-db:
	docker-compose run --rm bb-api-php-cli wait-for-it bb-api-postgres:5432 -t 30

api-wait-cache:
	docker-compose run --rm bb-api-php-cli wait-for-it bb-cache:6379 -t 30

api-migrations-up:
	docker-compose run --rm bb-api-php-cli composer app migrations:migrate -- --no-interaction

api-migrations-diff:
	docker-compose run --rm bb-api-php-cli composer app migrations:diff -- --no-interaction

api-migrations-rollup:
	docker-compose run --rm bb-api-php-cli composer app migrations:rollup -- --no-interaction

api-fixtures:
	docker-compose run --rm bb-api-php-cli composer app fixtures:load

api-validate-schema:
	docker-compose run --rm bb-api-php-cli composer app orm:validate-schema

api-drop-schema:
	docker-compose run --rm bb-api-php-cli composer app orm:schema-tool:drop -- --force

api-lint:
	docker-compose run --rm bb-api-php-cli composer lint
	docker-compose run --rm bb-api-php-cli composer php-cs-fixer fix -- --dry-run --diff

api-cs-fix:
	docker-compose run --rm bb-api-php-cli composer php-cs-fixer fix

api-analyze:
	docker-compose run --rm bb-api-php-cli composer psalm -- --no-diff

api-analyze-diff:
	docker-compose run --rm bb-api-php-cli composer psalm

build: 
	DOCKER_BUILDKIT=1 docker --log-level=debug build --pull --build-arg BUILDKIT_INLINE_CACHE=1 \
    --cache-from ${REGISTRY}/bb-api:cache \
    --tag ${REGISTRY}/bb-api:cache \
	--tag ${REGISTRY}/bb-api:${IMAGE_TAG} \
	--file docker/production/nginx/Dockerfile .

	DOCKER_BUILDKIT=1 docker --log-level=debug build --pull --build-arg BUILDKIT_INLINE_CACHE=1 \
	--target builder \
	--cache-from ${REGISTRY}/bb-api-php-fpm:cache-builder \
	--tag ${REGISTRY}/bb-api-php-fpm:cache-builder \
	--file docker/production/php-fpm/Dockerfile .

	DOCKER_BUILDKIT=1 docker --log-level=debug build --pull --build-arg BUILDKIT_INLINE_CACHE=1 \
	--cache-from ${REGISTRY}/bb-api-php-fpm:cache-builder \
	--cache-from ${REGISTRY}/bb-api-php-fpm:cache \
	--tag ${REGISTRY}/bb-api-php-fpm:cache \
	--tag ${REGISTRY}/bb-api-php-fpm:${IMAGE_TAG} \
	--file docker/production/php-fpm/Dockerfile .

	DOCKER_BUILDKIT=1 docker --log-level=debug build --pull --build-arg BUILDKIT_INLINE_CACHE=1 \
	--target builder \
	--cache-from ${REGISTRY}/bb-api-php-cli:cache-builder \
	--tag ${REGISTRY}/bb-api-php-cli:cache-builder \
	--file docker/production/php-cli/Dockerfile .

	DOCKER_BUILDKIT=1 docker --log-level=debug build --pull --build-arg BUILDKIT_INLINE_CACHE=1 \
	--cache-from ${REGISTRY}/bb-api-php-cli:cache-builder \
	--cache-from ${REGISTRY}/bb-api-php-cli:cache \
	--tag ${REGISTRY}/bb-api-php-cli:cache \
	--tag ${REGISTRY}/bb-api-php-cli:${IMAGE_TAG} \
	--file docker/production/php-cli/Dockerfile .

try-build:
	REGISTRY=localhost IMAGE_TAG=0 make build

push-dev-cache:
	docker-compose push

push-build-cache:
	docker push ${REGISTRY}/bb-api:cache
	docker push ${REGISTRY}/bb-api-php-fpm:cache-builder
	docker push ${REGISTRY}/bb-api-php-fpm:cache
	docker push ${REGISTRY}/bb-api-php-cli:cache-builder
	docker push ${REGISTRY}/bb-api-php-cli:cache

push:
	docker push ${REGISTRY}/bb-api:${IMAGE_TAG}
	docker push ${REGISTRY}/bb-api-php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY}/bb-api-php-cli:${IMAGE_TAG}

deploy:
	ssh deploy@${HOST} -p ${PORT} 'docker network create traefik-public || true'
	ssh deploy@${HOST} -p ${PORT} 'rm -rf api_${BUILD_NUMBER}'
	ssh deploy@${HOST} -p ${PORT} 'mkdir api_${BUILD_NUMBER}'
	scp -P ${PORT} docker-compose-production.yml deploy@${HOST}:api_${BUILD_NUMBER}/docker-compose.yml
	ssh deploy@${HOST} -p ${PORT} 'cd api_${BUILD_NUMBER} && echo "COMPOSE_PROJECT_NAME=bb-api" >> .env'
	ssh deploy@${HOST} -p ${PORT} 'cd api_${BUILD_NUMBER} && echo "REGISTRY=${REGISTRY}" >> .env'
	ssh deploy@${HOST} -p ${PORT} 'cd api_${BUILD_NUMBER} && echo "IMAGE_TAG=${IMAGE_TAG}" >> .env'
	ssh deploy@${HOST} -p ${PORT} 'cd api_${BUILD_NUMBER} && echo "DB_NAME=${DB_NAME}" >> .env'
	ssh deploy@${HOST} -p ${PORT} 'cd api_${BUILD_NUMBER} && echo "DB_USER=${DB_USER}" >> .env'
	ssh deploy@${HOST} -p ${PORT} 'cd api_${BUILD_NUMBER} && echo "DB_PASSWORD=${DB_PASSWORD}" >> .env'
	ssh deploy@${HOST} -p ${PORT} 'cd api_${BUILD_NUMBER} && echo "MAILER_HOST=${MAILER_HOST}" >> .env'
	ssh deploy@${HOST} -p ${PORT} 'cd api_${BUILD_NUMBER} && echo "MAILER_PORT=${MAILER_PORT}" >> .env'
	ssh deploy@${HOST} -p ${PORT} 'cd api_${BUILD_NUMBER} && echo "MAILER_USERNAME=${MAILER_USERNAME}" >> .env'
	ssh deploy@${HOST} -p ${PORT} 'cd api_${BUILD_NUMBER} && echo "MAILER_PASSWORD=${MAILER_PASSWORD}" >> .env'
	ssh deploy@${HOST} -p ${PORT} 'cd api_${BUILD_NUMBER} && echo "MAILER_FROM_EMAIL=${MAILER_FROM_EMAIL}" >> .env'
	ssh deploy@${HOST} -p ${PORT} 'cd api_${BUILD_NUMBER} && echo "JWT_SECRET=${JWT_SECRET}" >> .env'
	ssh deploy@${HOST} -p ${PORT} 'cd api_${BUILD_NUMBER} && docker-compose pull'
	ssh deploy@${HOST} -p ${PORT} 'cd api_${BUILD_NUMBER} && docker-compose up --build --remove-orphans -d'
	ssh deploy@${HOST} -p ${PORT} 'rm -f api'
	ssh deploy@${HOST} -p ${PORT} 'ln -sr api_${BUILD_NUMBER} api'

rollback:
	ssh deploy@${HOST} -p ${PORT} 'cd api_${BUILD_NUMBER} && docker-compose pull'
	ssh deploy@${HOST} -p ${PORT} 'cd api_${BUILD_NUMBER} && docker-compose up --build --remove-orphans -d'
	ssh deploy@${HOST} -p ${PORT} 'rm -f api'
	ssh deploy@${HOST} -p ${PORT} 'ln -sr api_${BUILD_NUMBER} api'
