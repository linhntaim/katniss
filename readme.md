## Road Map 1

### Vendors

#### laravel/laravel:5.1.26

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

#### zizaco/entrust:1.4.1

[![Build Status](https://travis-ci.org/Zizaco/entrust.svg)](https://travis-ci.org/Zizaco/entrust)
[![Version](https://img.shields.io/packagist/v/Zizaco/entrust.svg)](https://packagist.org/packages/zizaco/entrust)
[![License](https://poser.pugx.org/zizaco/entrust/license.svg)](https://packagist.org/packages/zizaco/entrust)
[![ProjectStatus](http://stillmaintained.com/Zizaco/entrust.png)](http://stillmaintained.com/Zizaco/entrust)
[![Total Downloads](https://img.shields.io/packagist/dt/zizaco/entrust.svg)](https://packagist.org/packages/zizaco/entrust)

- Conflict between EntrustUserTrait and Authorizable was resolved
- Middleware to authorize routes was created

#### barryvdh/laravel-debugbar:2.0.6

[![Packagist License](https://poser.pugx.org/barryvdh/laravel-debugbar/license.png)](http://choosealicense.com/licenses/mit/)
[![Latest Stable Version](https://poser.pugx.org/barryvdh/laravel-debugbar/version.png)](https://packagist.org/packages/barryvdh/laravel-debugbar)
[![Total Downloads](https://poser.pugx.org/barryvdh/laravel-debugbar/d/total.png)](https://packagist.org/packages/barryvdh/laravel-debugbar)

#### barryvdh/laravel-elfinder:0.3.4

[![Packagist License](https://poser.pugx.org/barryvdh/laravel-elfinder/license.png)](http://choosealicense.com/licenses/mit/)
[![Latest Stable Version](https://poser.pugx.org/barryvdh/laravel-elfinder/version.png)](https://packagist.org/packages/barryvdh/laravel-elfinder)
[![Total Downloads](https://poser.pugx.org/barryvdh/laravel-elfinder/d/total.png)](https://packagist.org/packages/barryvdh/laravel-elfinder)

#### jenssegers/agent:2.3.1

[![Latest Stable Version](http://img.shields.io/packagist/v/jenssegers/agent.svg)](https://packagist.org/packages/jenssegers/agent) 
[![Total Downloads](http://img.shields.io/packagist/dm/jenssegers/agent.svg)](https://packagist.org/packages/jenssegers/agent) 
[![Build Status](http://img.shields.io/travis/jenssegers/agent.svg)](https://travis-ci.org/jenssegers/agent) 
[![Coverage Status](http://img.shields.io/coveralls/jenssegers/agent.svg)](https://coveralls.io/r/jenssegers/agent) 
[![Donate](https://img.shields.io/badge/donate-paypal-blue.svg)](https://www.paypal.me/jenssegers)

#### laravel/socialite:2.0.14

#### mcamara/laravel-localization:1.0.12

[![Latest Stable Version](https://poser.pugx.org/mcamara/laravel-localization/version.png)](https://packagist.org/packages/mcamara/laravel-localization) 
[![Total Downloads](https://poser.pugx.org/mcamara/laravel-localization/d/total.png)](https://packagist.org/packages/mcamara/laravel-localization) 
[![Build Status](https://travis-ci.org/mcamara/laravel-localization.png)](https://travis-ci.org/mcamara/laravel-localization)

### App Options:

To store/retrieve runtime application's options in database

### Theme & Plugin Definition

#### Theme

##### Admin Theme

Sample Themes: 
- Admin LTE (base on [AdminLTE](https://github.com/almasaeed2010/AdminLTE))

##### Home Theme
    
Sample Themes:
- Default Theme (base on [Start Bootstrap - Scrolling Nav](https://github.com/IronSummitMedia/startbootstrap-scrolling-nav))

#### Plugin
    
##### Extension

Sample Extensions:

- Open Graph Tags
- Analytic Services
- Social Integration

##### Widget

Sample Widgets:

### Authentication:

Functions/Features:

- Registration
- Activation
- Login
- Logout
- Forgot/Reset Password
- Register/Login using Accounts on Social Networks (Facebook, Google)
- Email supported (for registering, activating & password resetting)

### Authorization

Mainly based on zizaco/entrust 1.4.1.

Default seeder:

- Access admin permission
- Owner role (has permission of Accessing Admin)
- Administrators role (has permission of Accessing Admin)
- Tester role (has permission of Accessing Admin)
- User role
- 3 starting users: owner (Owner role), admin (Owner & Administrator role), tester (Tester role)

Anonymous users will get the role of User after registering.

### File Manager:

To easily upload & manage files.

Ready for integrating with:

- CKEditor
- Input field