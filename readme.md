## Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

## Road Map 1

### Vendors

#### zizaco/entrust:1.4.1

- Resolve conflict between EntrustUserTrait and Authorizable
- Create a middleware based on this to authorize routes

### App Options:

To store/retrieve runtime application's options in database

### Theme & Plugin Definition

#### Theme

##### Admin Theme

Sample Themes: 
- Admin LTE

##### Home Theme
    
Sample Themes:
- Egret

#### Plugin
    
##### Extension

Sample Extensions:

##### Widget

Sample Widgets:

### User Roles, User Permissions

Based on zizaco/entrust 1.4.1.

Default seeder:

- Administrators role
- Access admin permission
- 1 Administrator User

### Authentication:

Contains:

- Registration
- Activation
- Login
- Logout
- Forgot/Reset Password
- Register/Login using Accounts on Social Networks (Facebook, Google)
- Email supported (for registering, activating & password resetting)

### File Manager:

To easily upload & manage files.