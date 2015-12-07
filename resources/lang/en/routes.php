<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-09-02
 * Time: 20:25
 */

return [
    'auth'                                          => 'auth',
    'auth/login'                                    => 'auth/login',
    'auth/logout'                                   => 'auth/logout',
    'auth/register'                                 => 'auth/register',
    'auth/register/social'                          => 'auth/register/social',
    'auth/inactive'                                 => 'auth/inactive',
    'auth/activate'                                 => 'auth/activate',
    'auth/activate/{id}/{activation_code}'          => 'auth/activate/{id}/{activation_code}',
    'auth/social'                                   => 'auth/social',
    'auth/social/{provider}'                        => 'auth/social/{provider}',
    'auth/social/callback'                          => 'auth/social/callback',
    'auth/social/callback/{provider}'               => 'auth/social/callback/{provider}',
    'password'                                      => 'password',
    'password/email'                                => 'password/email',
    'password/reset'                                => 'password/reset',
    'password/reset/{token}'                        => 'password/reset/{token}',

    'admin'                                         => 'admin',
];