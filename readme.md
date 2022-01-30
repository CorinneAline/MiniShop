# Mini Shop
## eCommerce application with Symfony 5
### Requirements
PHP 8.1.0 or higher;
PDO-SQLite PHP extension enabled;
and the usual Symfony application requirements.
https://symfony.com/doc/current/setup.html#technical-requirements

### Check your requirement if necessary with
```symfony check:requirements```
### Create a directory for this project or use an existent one
```cd my_projects```
### Clone the project
```git clone https://github.com/CorinneAline/MiniShop.git```
### Make composer install the project's dependencies into vendor
```composer install```
### Create a .env.local at the root of the project with :
- config for the database
- config for user
```
# env.local
###> doctrine/doctrine-bundle ###
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
###< doctrine/doctrine-bundle ###

USER_PASSWORD="user"
ADMIN_PASSWORD="password"
```
### Create database
```php bin/console doctrine:create:database```
### Migrate database
```php bin/console doctrine:migrations:migrate```
### Load fixtures
```php bin/console doctrine:fixtures:load```
### Generate client assets with Yarn (dev environment)
```yarn run dev```
### Start the server
```symfony server:start```
### Begin to surf

Visit the site at https://127.0.0.1:8000

Click the right button in toolbar to login with
- username: admin
- password: password

As admin you can go to /admin (Dashboard) to manage the products with EasyAdmin

You can also change language (english or french) with the dedicated icon

## Api products

### Endpoints

From your_host (https://127.0.0.1:8000 for local)
- [GET] /api/products

Return json data for list products ordered by name

## Commands

Get a csv file of products ordered by name

The file will be stored in your_project/var/export, filename 'export-products.csv'

```php bin/console app:export-products-csv```

## Tests

### Before running the test
#### Create database
```php bin/console doctrine:create:database --env=test```
#### Migrate database
```php bin/console doctrine:migrations:migrate --env=test```
#### Run with a group
```php ./vendor/bin/phpunit --group=productController```
#### Run all tests
```php ./vendor/bin/phpunit```

## Credits
- Symfony: https://symfony.com/
- Bootstrap4: https://getbootstrap.com/docs/4.0/getting-started/introduction/
- Webpack: https://webpack.js.org/
- Yarn: https://yarnpkg.com/
- JQuery: https://jquery.com/
- Pexels: https://www.pexels.com/fr-fr/


