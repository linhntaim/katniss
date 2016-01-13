# Katniss (A Laravel-based Framework)

Katniss extends the Laravel Framework by:

- Integrating with useful external solutions from communities
- Developing some custom modules 

The destination of Katniss is to help to build website more faster:

- Many common problems are solved and solutions are integrated.
- Lots of business flows, functions and features are ready to use and easily to modify

## Installation

### Environment file

Duplicate the file named `.env.example` at the root directory of the application and change the name of the duplicated file to `.env`.

### PHP

Require PHP >= `5.6`.

### Database

Require MySQL >= `5.5`.

Current database settings (in the `.env` file) of the framework require you to make those configuration in MySQL (`my.ini` or `my.cnf` file):

```php
innodb_file_format=BARRACUDA
innodb_large_prefix=ON
```

Create database `katniss` with `default character set utf8mb4` and `default collate utf8mb4_unicode_ci`

Initialize database by running these commands when in root directory of the application:

`php artisan migrate`

`php artisan db:seed --class=DefaultSeeder`

**Notice:**

If you change the current database settings (in the `.env` file) to:

```php
DB_CHARSET=utf8
DB_COLLATION=utf8_unicode_ci
```

Then, you don't have to change MySQL Configuration.

In addition, you must create database `katniss` with `default character set utf8` and `default collate utf8_unicode_ci`

Furthermore, you must go through all the migrating files in directory `root\database\migrations` and delete these lines when you see them in the code:

```php
$table->engine = 'InnoDB';
$table->rowFormat = 'DYNAMIC';
```

## Components and Modules

### Vendors

#### laravel/laravel:5.2.8

[Laravel PHP Framework](https://github.com/laravel/laravel)

[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)

Base framework.

#### zizaco/entrust:1.4.1

[ENTRUST (Laravel 5 Package)](https://github.com/Zizaco/entrust)

[![Version](https://img.shields.io/packagist/v/Zizaco/entrust.svg)](https://packagist.org/packages/zizaco/entrust)

- To authorize users with roles and permissions
- Middleware to authorize routes was created

#### barryvdh/laravel-debugbar:2.1.1

[Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar)

[![Latest Stable Version](https://poser.pugx.org/barryvdh/laravel-debugbar/version.png)](https://packagist.org/packages/barryvdh/laravel-debugbar)

For debugging.

#### barryvdh/laravel-elfinder:0.3.5

[elFinder Package for Laravel 5](https://github.com/barryvdh/laravel-elfinder)

[![Latest Stable Version](https://poser.pugx.org/barryvdh/laravel-elfinder/version.png)](https://packagist.org/packages/barryvdh/laravel-elfinder)

For file managing/uploading.

#### jenssegers/agent:2.3.1

[Agent](https://github.com/jenssegers/agent)

[![Latest Stable Version](http://img.shields.io/packagist/v/jenssegers/agent.svg)](https://packagist.org/packages/jenssegers/agent) 

For detecting client.

#### laravel/socialite:2.0.14

[Laravel Socialite](https://github.com/laravel/socialite)

For logging in & registering from social networks.

#### mcamara/laravel-localization:1.1.1

[Laravel Localization](https://github.com/mcamara/laravel-localization)

- Some bugs were fixed

[![Latest Stable Version](https://poser.pugx.org/mcamara/laravel-localization/version.png)](https://packagist.org/packages/mcamara/laravel-localization) 

For localizing (esp. with URL).

#### dimsav/laravel-translatable:5.4

[Laravel-Translatable](https://github.com/dimsav/laravel-translatable)

[![Latest Stable Version](http://img.shields.io/packagist/v/dimsav/laravel-translatable.svg)](https://packagist.org/packages/dimsav/laravel-translatable)

For multilingual models (Database Entities & App Models)

#### mews/purifier:2.0.3

[HTMLPurifier for Laravel 5](https://github.com/mewebstudio/Purifier)

For filtering HTML content.

#### almasaeed2010/AdminLTE:2.3.2

[AdminLTE](https://github.com/almasaeed2010/AdminLTE)

For admin template.

#### IronSummitMedia/startbootstrap-scrolling-nav:1.0.4

[Start Bootstrap - Scrolling Nav](https://github.com/IronSummitMedia/startbootstrap-scrolling-nav)

For home template.

### App Options:

To store/retrieve runtime application's options in database.

### Theme and Plugin Definition

#### Theme

To organize templates into themes for easily developing/extending.

##### Admin Theme

Themes for administration.

Sample Themes:

- Admin LTE (base on [AdminLTE](#almasaeed2010adminlte232)).

##### Home Theme

Themes for business.

Home themes are easily extended with plugins. 
    
Sample Themes:

- Default Theme (base on [Start Bootstrap - Scrolling Nav](#ironsummitmediastartbootstrap-scrolling-nav104))

#### Plugins
    
##### Extension

Define extensions for adding extra functions/features to themes (or even the system).

Sample Extensions:

- Open Graph Tags 
    - Add open graph tags into website
- Analytic Services
    - Add website analytics
    - Current: Google, MixPanel
- Social Integration:
    - Integrate website with social networks
    - Current: Facebook, Twitter, Google, LinkedIn

##### Widget

Define widgets of content for inserting into placeholders of any theme.

Widgets in a placeholder are sortable.

Sample Widgets:

- Extra HTML
    - Add HTML content to website
- Base Links
    - Add collection of links to website

### Authentication:

Functions/Features:

- Registration
- Activation
- Login
- Logout
- Forgot/Reset Password
- Register/Login using Accounts on Social Networks (Facebook, Google)
- Email supported (for registering, resending activation & password resetting & password changed)
    - Emails for registering & password changed are queued before sending
    - Configure a queue listener or `php artisan queue:listen` for sending queued emails (see [Running The Queue Listener](http://laravel.com/docs/5.1/queues#running-the-queue-listener)) 

### Authorization

To authorize users with roles & permissions.

Mainly based on [zizaco/entrust:1.4.1](#zizacoentrust141).

Database seeding:

- Access admin permission
- Owner role (has permission of Accessing Admin)
- Administrators role (has permission of Accessing Admin)
- Tester role (has permission of Accessing Admin)
- User role
- 3 starting users (with default settings): owner (Owner role), admin (Owner & Administrator role), tester (Tester role)

Anonymous users will get the role of User after registering.

### Settings

To store customized settings of each user.

For anonymous users, settings are saved in cookie & session.

For authenticated users, settings are saved in database & session & cookie.

### File Manager:

To easily upload & manage files.

Based on [barryvdh/laravel-elfinder:0.3.4](#barryvdhlaravel-elfinder034).

Ready for integrating with:

- CKEditor
- Input field

### Links

Manage links and categories of links.