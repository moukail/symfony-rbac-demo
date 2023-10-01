rm -rf ./*

echo "-------------------------------------------------------------------"
echo "-                create symfony project                           -"
echo "-------------------------------------------------------------------"
symfony self:version

symfony new my_project --no-git --version=5.4 --php=8.2 --docker=false

echo "-------------------------------------------------------------------"
echo "-                   require packages                              -"
echo "-------------------------------------------------------------------"
cd ./my_project
cp .env .env.local
cp .env .env.develop

symfony composer require php:^8.2.0
symfony composer require twig
symfony composer require --no-interaction doctrine/orm doctrine/doctrine-migrations-bundle

echo "-------------------------------------------------------------------"
echo "-               require dev packages                              -"
echo "-------------------------------------------------------------------"
symfony composer require --dev debug symfony/profiler-pack symfony/maker-bundle
symfony composer require --dev --no-interaction doctrine/doctrine-fixtures-bundle

rm -rf .git
cd ..

chmod -R a+rw my_project

rsync -a my_project/ ./
rm -rf my_project
