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

    'documents'                                     => 'documents',
    'documents/connector'                           => 'documents/connector',
    'documents/for/ckeditor'                        => 'documents/for/ckeditor',
    'documents/for/popup/{input_id}'                => 'documents/for/popup/{input_id}',

    'admin'                                         => 'admin',
    'admin/my-documents'                            => 'admin/my-documents',
    'admin/user-roles'                              => 'admin/user-roles',
    'admin/users'                                   => 'admin/users',
    'admin/users/add'                               => 'admin/users/add',
    'admin/users/{id}/edit'                         => 'admin/users/{id}/edit',
    'admin/users/update'                            => 'admin/users/update',
    'admin/users/{id}/delete'                       => 'admin/users/{id}/delete',
    'admin/app-options'                             => 'admin/app-options',
    'admin/extensions'                              => 'admin/extensions',
    'admin/extensions/{name}/edit'                  => 'admin/extensions/{name}/edit',
    'admin/extensions/update'                       => 'admin/extensions/update',
    'admin/extensions/{name}/activate'              => 'admin/extensions/{name}/activate',
    'admin/extensions/{name}/deactivate'            => 'admin/extensions/{name}/deactivate',
    'admin/widgets'                                 => 'admin/widgets',
    'admin/widgets/{id}/edit'                       => 'admin/widgets/{id}/edit',
    'admin/widgets/update'                          => 'admin/widgets/update',
    'admin/widgets/{id}/delete'                     => 'admin/widgets/{id}/delete',
    'admin/widgets/{id}/activate'                   => 'admin/widgets/{id}/activate',
    'admin/widgets/{id}/deactivate'                 => 'admin/widgets/{id}/deactivate',
    'admin/widgets/clone'                           => 'admin/widgets/clone',
    'admin/ui-lang/php'                             => 'admin/ui-lang/php',
    'admin/ui-lang/email'                           => 'admin/ui-lang/email',
    'admin/link-categories'                         => 'admin/link-categories',
    'admin/link-categories/add'                     => 'admin/link-categories/add',
    'admin/link-categories/{id}/edit'               => 'admin/link-categories/{id}/edit',
    'admin/link-categories/update'                  => 'admin/link-categories/update',
    'admin/link-categories/{id}/delete'             => 'admin/link-categories/{id}/delete',
    'admin/link-categories/{id}/sort'               => 'admin/link-categories/{id}/sort',
    'admin/links'                                   => 'admin/links',
    'admin/links/{id}/delete'                       => 'admin/links/{id}/delete',
    'admin/links/add'                               => 'admin/links/add',
    'admin/links/{id}/edit'                         => 'admin/links/{id}/edit',
    'admin/links/update'                            => 'admin/links/update',
];