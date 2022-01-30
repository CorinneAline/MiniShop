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
