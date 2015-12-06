## Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

## Road Map 1

- Theme & Plugin Definition
    - Theme:
        - Admin Theme
            - Sample Theme: Admin LTE
        - Home Theme
            - Sample Theme: ?, ?
    - Plugin:
        - Extension
            - Sample Extensions: ?
        - Widget
            - Sample Widgets: ?
    - Administrators can:
        - switch between themes
        - preview theme
        - activate/deactivate/edit Extensions
        - activate/deactivate/edit/add-to-placeholder Widgets
- File Manager module: to easily upload & manage files.
- Simple User module
    - Authentication Flow
        - Registration (full name, email, username, phone, password)
        - Activation (user id, email, activation token)
        - Login (email or username or phone, password)
        - Logout
        - Forgot Password (email)
        - Social Authentication supported (Register/Login from social accounts)
        - Email supported (send announcement emails after actions)
    - Roles, Permission
        - Administrators role
        - Access admin permission
    - Administrators can:
        - add/edit/list/activate/deactivate/ban Authenticated User or Anonymous User
        - edit email templates used for Authentication Flow
    - Authenticated User can: view/edit info
    - Anonymous User can view activated Authenticated Users' info
- App Options module: to store runtime variables/options in database (key => value)