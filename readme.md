# Katniss (A Laravel-based Framework)

Katniss extends the Laravel Framework by:

- Integrating with existed useful external solutions from communities
- Developing new helpful modules for customization 

The destination of Katniss is to help to build website more faster:

- Many common problems are solved and solutions are integrated.
- Lots of business flows, functions and features are ready to use and easily to modify

## Installation

### Environment file

Duplicate the file named `.env.example` at the root directory of the application and change the name of the duplicated file to `.env`.

Change some configuration as you want.

### PHP

Require PHP >= `5.6.4`.

Current version: `7.0.9`.

### Database

MySQL is recommended.

Require MySQL >= `5.5`.

Current version: `MariaDB 10.1.16`.

Current database settings (in the `.env` file) of the framework require you to make those configuration in MySQL (`my.ini` or `my.cnf` file):

```php
innodb_file_format=BARRACUDA
innodb_large_prefix=ON
```

Those settings require to create database `katniss` with `default character set utf8mb4` and `default collate utf8mb4_unicode_ci`

After creating, you should initialize database by running these commands when in root directory of the application:

`composer install`

`php artisan migrate --seed`

**Notice:**

If you change the current database settings (in the `.env` file) to:

```php
DB_CHARSET=utf8
DB_COLLATION=utf8_unicode_ci
```

Then, you don't have to change MySQL configuration.

Therefore, you must create database `katniss` with `default character set utf8` and `default collate utf8_unicode_ci`

Furthermore, you must go through all the migrating files in directory `root\database\migrations` and delete these lines when you see them in the code:

```php
$table->engine = 'InnoDB';
$table->rowFormat = 'DYNAMIC';
```

## Components and Modules

### Vendors

#### laravel/laravel

[Laravel PHP Framework](https://github.com/laravel/laravel)

Current version: `5.3.24`.

Latest version:

[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)

Base framework.

Customization:

- Database:
    - Enable schema for MySQL database to support row format when creating tables.
- Session:
    - Extend database session handler for further purpose (currently session is based on this handler).
    - Fix file session handler bugs may remove session of user when happening many concurrent AJAX requests.
- Support:
    - Extend Str class for new methods to operate string.

#### barryvdh/laravel-debugbar

[Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar)

Current version: `2.3.0`.

Latest version:

[![Latest Stable Version](https://poser.pugx.org/barryvdh/laravel-debugbar/version.png)](https://packagist.org/packages/barryvdh/laravel-debugbar)

For debugging.

#### barryvdh/laravel-elfinder

[elFinder Package for Laravel 5](https://github.com/barryvdh/laravel-elfinder)

Current version: `0.3.8`.

Latest version:

[![Latest Stable Version](https://poser.pugx.org/barryvdh/laravel-elfinder/version.png)](https://packagist.org/packages/barryvdh/laravel-elfinder)

For file managing/uploading.

#### jenssegers/agent

[Agent](https://github.com/jenssegers/agent)

Current version: `2.3.3`.

Latest version:

[![Latest Stable Version](http://img.shields.io/packagist/v/jenssegers/agent.svg)](https://packagist.org/packages/jenssegers/agent) 

For detecting client.

#### laravel/socialite

[Laravel Socialite](https://github.com/laravel/socialite)

Current version: `2.0.20`.

Latest version:

[![Latest Stable Version](https://poser.pugx.org/laravel/socialite/v/stable.svg)](https://packagist.org/packages/laravel/socialite)

For logging in & registering from social networks.

Customization:

- Change Facebook provider to get large avatar of authenticated user.

#### mews/purifier

[HTMLPurifier for Laravel 5](https://github.com/mewebstudio/Purifier)

Current version: `2.0.6`.

For filtering HTML content.

#### dimsav/laravel-translatable

[Laravel-Translatable](https://github.com/dimsav/laravel-translatable)

Current version: `6.0.1`.

Latest version:

[![Latest Stable Version](http://img.shields.io/packagist/v/dimsav/laravel-translatable.svg)](https://packagist.org/packages/dimsav/laravel-translatable)

For multilingual models (Database Entities & App Models).

#### zizaco/entrust

[ENTRUST (Laravel 5 Package)](https://github.com/Zizaco/entrust)

Current version: `1.7.0`.

Latest version:

[![Version](https://img.shields.io/packagist/v/Zizaco/entrust.svg)](https://packagist.org/packages/zizaco/entrust)

To authorize users with roles and permissions.

Customization:

- Middleware to authorize routes was created.
- Force not to use the method `Cache::tags` in workflow.
    - If you plan to use the cache drivers different from file and database, you should remove this customization.
    - See more at [Cache Tags](https://laravel.com/docs/5.3/cache#cache-tags).

#### mcamara/laravel-localization

[Laravel Localization](https://github.com/mcamara/laravel-localization)

Current version: `1.1.9`.

Latest version:

[![Latest Stable Version](https://poser.pugx.org/mcamara/laravel-localization/version.png)](https://packagist.org/packages/mcamara/laravel-localization) 

For localizing (esp. with URL).

Customization:

- Fix some bugs:
    - Localizing url gets wrong result when the url contains query or hash string.
    - Setting wrong locale when locale from browser is not similar to locale from path.

#### larabros/elogram

[elogram](https://github.com/larabros/elogram/)

Current version: `1.2.2`.

Latest version:

[![Latest Version on Packagist](https://img.shields.io/packagist/v/larabros/elogram.svg?style=flat-square
)](https://packagist.org/packages/larabros/elogram)

For fetching data from Instagram API.

#### almasaeed2010/AdminLTE

[AdminLTE](https://github.com/almasaeed2010/AdminLTE)

Current version: `2.3.2`.

Latest version:

![Bower version](https://img.shields.io/bower/v/adminlte.svg)

For admin template.

#### IronSummitMedia/startbootstrap-scrolling-nav

[Start Bootstrap - Scrolling Nav](https://github.com/IronSummitMedia/startbootstrap-scrolling-nav)

Current version: `1.0.4`.

For home template.

### API Definition

Applications get data in JSON format by making requests to API urls.

#### (App) API

For data requesting from external applications (external requests).

Requests have no any sessions & cookies for storing (stateless).

An application which makes requests must be registered then gets its own secret key.

Applications access data by providing their known secret keys in every sending requests.

Request flow:

- Request > API Middleware (`ApiMiddleware`) > API Controller > Response (JSON format).

There's always an default application needed to register with `id = 1`:

- See `database\seeds\DefaultSeeder.php`, line 77.

#### Web API

For data requesting from internal applications like AJAX requests, .. (internal requests).

Requests share sessions & cookies between connections (stateful).

Request flow:

- Request > Web Middleware (`ViewMiddleware`) > Web API Controller > Response (JSON format).

### App Options

To store/retrieve runtime application's options in database.

### Theme and Plugin Definition

#### Theme

To organize templates into themes for easily developing/extending.

##### Admin Theme

Themes for administration.

Sample Themes:

- Admin LTE (base on [AdminLTE](#almasaeed2010adminlte)).

##### Home Theme

Themes for business.

Home themes are easily extended with plugins. 
    
Sample Themes:

- Default Theme (base on [Start Bootstrap - Scrolling Nav](#ironsummitmediastartbootstrap-scrolling-nav)).

#### Plugins
    
##### Extension

Define extensions for adding extra functions/features to themes (or even the system).

Sample Extensions:

- Open Graph Tags:
    - Add open graph tags into website.
- Analytic Services:
    - Add website analytics.
    - Current: Google, MixPanel.
- Social Integration:
    - Integrate website into social networks.
    - Current: Facebook, Twitter, Google, LinkedIn, Instagram.
- Currency Exchange:
    - Allow user to configure the exchange rates to automatically convert to/from any currencies.

##### Widget

Define widgets of content for inserting into placeholders of any theme.

Widgets in a placeholder are sortable; their orders can be changed.

Sample Widgets:

- Extra HTML:
    - Add HTML content to website.
- Base Links:
    - Add collection of links to website.
- Instagram Wall:
    - Only available when Social Integration extension is activated and integration with Instagram is enabled.
    - Display wall of images from Instagram user.

### Authentication

Functions/Features:

- Registration.
- Activation.
- Login.
- Logout.
- Forgot/Reset Password.
- Register/Login using Accounts on Social Networks (Facebook, Google).
- Email supported (for registering, resending activation & password resetting & password changed):
    - Emails for registering & password changed are queued before sending.
    - Configure a queue worker or `php artisan queue:work` for sending queued emails (see [Running The Queue Worker](https://laravel.com/docs/5.3/queues#running-the-queue-worker)).
- Lock screen supported, when user is idle while accessing admin page.
- Update account information.

### Authorization

To authorize users with roles & permissions.

Mainly based on [zizaco/entrust](#zizacoentrust).

Database seeding:

- Access admin permission.
- Owner role (has permission of Accessing Admin).
- Administrators role (has permission of Accessing Admin).
- Tester role (has permission of Accessing Admin).
- User role.
- 3 starting users (with default settings): 
    - Name: `owner`. 
        - Password: `^KM$bB-W7:Z@8eG`.
        - Role: `Owner`.
    - Name: `admin`.
        - Password: `123456`.
        - Role: `Owner`, `Administrator`, 
    - Name: `tester`.
        - Password: `123456`.
        - Role: `Tester`.

Anonymous users will get the role of User after registering.

### Settings

To store customized settings of each user.

For anonymous users, settings are saved in cookie & session.

For authenticated users, settings are saved in database & session & cookie.

### File Manager

For users to easily upload & manage files.

Based on [barryvdh/laravel-elfinder](#barryvdhlaravel-elfinder).

Ready for integrating with:

- CKEditor.
- Input field.

### Links

Manage links and categories of links.
