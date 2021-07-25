export CURRENT_USER_ID=$(id -u)
DOCKER_FILE='docker-compose.yml'

if [ ! -f ./src/.env.docker ]; then
  cp ./src/.env.docker.example ./src/.env.docker
fi

docker network inspect copmusic >/dev/null 2>&1 || docker network create copmusic

docker-compose -f ${DOCKER_FILE} stop
docker-compose -f ${DOCKER_FILE} build --pull --force-rm
docker-compose -f ${DOCKER_FILE} up -d --force-recreate

docker exec -it cop-php-fpm php bin/composer.phar install

cd front && npm i
