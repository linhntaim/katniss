## Installation

### PHP

PHP >= 5.6

### Database

MySQL >= 5.5

Configuration (`my.ini` or `my.cnf`):

```php
innodb_file_format=BARRACUDA
innodb_large_prefix=ON
```

Create database `katniss` with `default character set utf8mb4` and `default collate utf8mb4_unicode_ci`

Initialize database

`php artisan migrate`

`php artisan db:seed --class=DefaultSeeder`

## Components and Modules

### Vendors

#### laravel/laravel:5.1.26

[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)

Base framework.

#### zizaco/entrust:1.4.1

[![Version](https://img.shields.io/packagist/v/Zizaco/entrust.svg)](https://packagist.org/packages/zizaco/entrust)

- To authorize users with roles and permissions
- Conflict between EntrustUserTrait and Authorizable was resolved
- Middleware to authorize routes was created

#### barryvdh/laravel-debugbar:2.0.6

[![Latest Stable Version](https://poser.pugx.org/barryvdh/laravel-debugbar/version.png)](https://packagist.org/packages/barryvdh/laravel-debugbar)

For debugging.

#### barryvdh/laravel-elfinder:0.3.4

[![Latest Stable Version](https://poser.pugx.org/barryvdh/laravel-elfinder/version.png)](https://packagist.org/packages/barryvdh/laravel-elfinder)

For file managing/uploading.

#### jenssegers/agent:2.3.1

[![Latest Stable Version](http://img.shields.io/packagist/v/jenssegers/agent.svg)](https://packagist.org/packages/jenssegers/agent) 

For detecting client.

#### laravel/socialite:2.0.14

For logging in & registering from social networks.

#### mcamara/laravel-localization:1.0.12

[![Latest Stable Version](https://poser.pugx.org/mcamara/laravel-localization/version.png)](https://packagist.org/packages/mcamara/laravel-localization) 

For localizing (esp. with URL).

#### dimsav/laravel-translatable:5.4

[![Latest Stable Version](http://img.shields.io/packagist/v/dimsav/laravel-translatable.svg)](https://packagist.org/packages/dimsav/laravel-translatable)

For multilingual models (Database Entities & App Models)

#### mews/purifier:2.0.3

For filtering HTML content.

### App Options:

To store/retrieve runtime application's options in database.

### Theme and Plugin Definition

**How-to-Develop-Theme-and-Plugin-with-Katniss is coming soon**

#### Theme

To organize templates into themes for easily developing/extending.

##### Admin Theme

Themes for administration.

Sample Themes: 
- Admin LTE (base on [AdminLTE](https://github.com/almasaeed2010/AdminLTE))

##### Home Theme

Themes for business.

Home themes are easily extended with plugins. 
    
Sample Themes:
- Default Theme (base on [Start Bootstrap - Scrolling Nav](https://github.com/IronSummitMedia/startbootstrap-scrolling-nav))

#### Plugins
    
##### Extension

Define extensions for adding extra functions/features to themes (or even the system).

Sample Extensions:

- Open Graph Tags: add open graph tags into website
- Analytic Services: add website analytics (Google, MixPanel)
- Social Integration: integrate website with social networks (Facebook, Twitter, Google, LinkedIn)

##### Widget

Define widgets of content for inserting into placeholders of any theme.

Widgets in a placeholder are sortable.

Sample Widgets:

- Extra HTML: add HTML content to website
- Base Links: add collection of links to website

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

Mainly based on zizaco/entrust:1.4.1.

Database seeding:

- Access admin permission
- Owner role (has permission of Accessing Admin)
- Administrators role (has permission of Accessing Admin)
- Tester role (has permission of Accessing Admin)
- User role
- 3 starting users: owner (Owner role), admin (Owner & Administrator role), tester (Tester role)

Anonymous users will get the role of User after registering.

### Settings

To store customized settings of each user.

### File Manager:

To easily upload & manage files.

Based on barryvdh/laravel-elfinder:0.3.4.

Ready for integrating with:

- CKEditor
- Input field

### Links

Manage links and categories of links.