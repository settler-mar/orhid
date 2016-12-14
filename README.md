Yii 2 Orhid
============================

Проект для сайта знакомств.

### Участники проекта

- Матюхин Максим (беккенд, фронтен)
- Александр Галайда (дизайн, верстка)

Структура папок
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      components/         contains user components
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources
      db/                 start DB



REQUIREMENTS
------------

- PHP 5.4.0.
- composer


INSTALLATION
------------

- clone from github
```sh 
git clone https://github.com/settler-mar/orhid orhid
```
- change dir
```sh
cd orhid
```
- install composer module
```sh
composer install
```
- make all config in @app/config
- make migrate RBAC
```sh
php yii migrate/up --migrationPath=@yii/rbac/migrations
```
- make migrate site
```sh
php yii migrate
```
- import db/yii2orhid.sql to mysql datebase

CONFIGURATION
-------------

### Database

Create the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2orhid',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

### Params

Rename params.example.php to params.php and config

### Personal

Rename personal.example.php to personal.php and config

START TEST WEB SERVER
-------------

```sh
php yii serve
```