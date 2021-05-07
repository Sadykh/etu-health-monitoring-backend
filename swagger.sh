#!/bin/sh

docker-compose exec php ./vendor/bin/openapi modules/api --format json --output web/swagger.json