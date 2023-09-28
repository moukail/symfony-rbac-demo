#!/usr/bin/env bash

# create new project
if [ ! -d "./src" ]; then
  . /home/install.sh
fi

mkcert -install
#mkcert -key-file symfony_key.pem -cert-file symfony_cert.pem symfony.dev.local localhost 127.0.0.1 ::1
#openssl pkcs12 -export -out certificate.p12 -inkey symfony_key.pem -in symfony_cert.pem

#rm -rf node_modules package-lock.json
npm install --silent --no-progress --non-interactive
#npm audit fix
#npm install --global browser-sync
#npm run dev

#export APP_ENV=dev
#rm -rf var vendor composer.lock symfony.lock
#cp .env.local .env
#echo "8.2" > .php-version

#symfony --no-interaction self:update
#symfony self:version
#symfony self:cleanup

echo "-----------------------------------------------------------------------------------------------------------------"
echo "-                                                composer                                                       -"
echo "-----------------------------------------------------------------------------------------------------------------"
symfony composer update --no-interaction
symfony composer -n check-platform-reqs
symfony check:security
symfony console about

echo "-------------------------------------------------------------------"
echo "-                        waiting for DB                           -"
echo "-------------------------------------------------------------------"
while ! nc -z rbac-database 3306; do sleep 1; done
echo "-------------------------------------------------------------------"
echo "-                        prepare the DB                           -"
echo "-------------------------------------------------------------------"
#symfony console doctrine:database:drop --if-exists --force
symfony console doctrine:database:create --if-not-exists
symfony console doctrine:migrations:migrate --no-interaction
symfony console doctrine:fixtures:load --no-interaction -vvv

#symfony console doctrine:database:drop --if-exists --force
#symfony console doctrine:database:create --if-not-exists
#symfony console doctrine:migrations:up-to-date
#symfony console doctrine:migrations:migrate --allow-no-migration --no-interaction
#symfony console doctrine:schema:update --force
#symfony console doctrine:schema:validate
#symfony console doctrine:fixtures:load --no-interaction
#nohup symfony console thruway:router:start >/dev/null 2>&1 &

#export PHP_IDE_CONFIG="serverName=your-server-name-configured-in-php-storm"
#export XDEBUG_CONFIG="remote_host=host.docker.internal idekey=PHPSTORM"
#export XDEBUG_SESSION_START=10219

echo "-------------------------------------------------------------------"
echo "-                        php-cs-fixer                             -"
echo "-------------------------------------------------------------------"
#php-cs-fixer fix ./src --rules=@Symfony --verbose --show-progress=estimating

echo "-------------------------------------------------------------------"
echo "-                        phpstan                                  -"
echo "-------------------------------------------------------------------"
#vendor/bin/phpstan analyse src
#./vendor/bin/psalm
echo "-------------------------------------------------------------------"
echo "-                        php-doc-check                            -"
echo "-------------------------------------------------------------------"
#vendor/bin/php-doc-check ./src

echo "-------------------------------------------------------------------"
echo "-                        phpcpd                                   -"
echo "-------------------------------------------------------------------"
#phpcpd --no-interaction src

echo "-------------------------------------------------------------------"
echo "-                        PHPMD                                    -"
echo "-------------------------------------------------------------------"
#phpmd ./src text codesize,unusedcode,naming,design,controversial,cleancode

echo "-------------------------------------------------------------------"
echo "-                            benchmarks                           -"
echo "-------------------------------------------------------------------"
#phpbench run --report=default --report=env
#phpbench xdebug:profile
#phpbench xdebug:trace

echo "-------------------------------------------------------------------"
echo "-                        website is ready                         -"
echo "-------------------------------------------------------------------"
#symfony proxy:start
#symfony proxy:domain:attach my-domain
#HTTPS_PROXY=http://127.0.0.1:7080 curl https://my-domain.wip
chmod -R a+rw ./
symfony local:php:list
symfony local:php:refresh
symfony check:requirements
symfony security:check
#echo | symfony server:ca:install
symfony serve --daemon --p12=certificate.p12

echo "-------------------------------------------------------------------"
echo "-                        testing                                  -"
echo "-------------------------------------------------------------------"
#codecept clean
#codecept run --steps --debug -vvv --coverage --coverage-xml --coverage-html

echo "-------------------------------------------------------------------"
echo "-                        yarn watch                               -"
echo "-------------------------------------------------------------------"
npm run watch

tail -f /dev/null