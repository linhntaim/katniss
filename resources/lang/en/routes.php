<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-09-02
 * Time: 20:25
 */

return [
    'extra'                                         => 'extra',
    'admin/extra'                                   => 'admin/extra',
    'errors/{code}'                                 => 'errors/{code}',
    'admin/errors/{code}'                           => 'admin/errors/{code}',

    'user/sign-up'                                  => 'user/sign-up',
    'teacher/sign-up'                               => 'teacher/sign-up',
    'teacher/sign-up/step/{step}'                   => 'teacher/sign-up/step/{step}',
    'student/sign-up'                               => 'student/sign-up',
    'student/sign-up/step/{step}'                   => 'student/sign-up/step/{step}',
    'teachers'                                      => 'teachers',
    'teachers/{id}'                                 => 'teachers/{id}',
    'opening-classrooms'                            => 'opening-classrooms',
    'closed-classrooms'                             => 'closed-classrooms',
    'classrooms/{id}'                               => 'classrooms/{id}',
    'helps'                                         => 'helps',
    'helps/{slug}'                                  => 'helps/{slug}',

    'profile/account-information'                   => 'profile/account-information',
    'profile/user-information'                      => 'profile/user-information',
    'profile/educations-and-works'                  => 'profile/educations-and-works',
    'profile/professional-skills'                   => 'profile/professional-skills',
    'profile/user-works'                            => 'profile/user-works',
    'profile/user-works/{id}'                       => 'profile/user-works/{id}',
    'profile/user-educations'                       => 'profile/user-educations',
    'profile/user-educations/{id}'                  => 'profile/user-educations/{id}',
    'profile/user-certificates'                     => 'profile/user-certificates',
    'profile/user-certificates/{id}'                => 'profile/user-certificates/{id}',
    'profile/teacher-information'                   => 'profile/teacher-information',
    'profile/teaching-time'                         => 'profile/teaching-time',
    'profile/payment-information'                   => 'profile/payment-information',

    'me'                                            => 'me',
    'me/settings'                                   => 'me/settings',
    'me/account'                                    => 'me/account',
    'me/documents'                                  => 'me/documents',
    'me/documents/connector'                        => 'me/documents/connector',
    'me/documents/for/ckeditor'                     => 'me/documents/for/ckeditor',
    'me/documents/for/popup/{input_id}'             => 'me/documents/for/popup/{input_id}',

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
    'admin/my-documents'                            => 'admin/my-documents',
    'admin/user-roles'                              => 'admin/user-roles',
    'admin/users'                                   => 'admin/users',
    'admin/users/create'                            => 'admin/users/create',
    'admin/users/{id}'                              => 'admin/users/{id}',
    'admin/users/{id}/edit'                         => 'admin/users/{id}/edit',
    'admin/app-options'                             => 'admin/app-options',
    'admin/app-options/{id}'                        => 'admin/app-options/{id}',
    'admin/app-options/{id}/edit'                   => 'admin/app-options/{id}/edit',
    'admin/extensions'                              => 'admin/extensions',
    'admin/extensions/{name}'                       => 'admin/extensions/{name}',
    'admin/extensions/{name}/edit'                  => 'admin/extensions/{name}/edit',
    'admin/widgets'                                 => 'admin/widgets',
    'admin/widgets/{id}'                            => 'admin/widgets/{id}',
    'admin/widgets/{id}/edit'                       => 'admin/widgets/{id}/edit',
    'admin/ui-lang/php'                             => 'admin/ui-lang/php',
    'admin/ui-lang/email'                           => 'admin/ui-lang/email',
    'admin/link-categories'                         => 'admin/link-categories',
    'admin/link-categories/create'                  => 'admin/link-categories/create',
    'admin/link-categories/{id}'                    => 'admin/link-categories/{id}',
    'admin/link-categories/{id}/edit'               => 'admin/link-categories/{id}/edit',
    'admin/link-categories/{id}/sort'               => 'admin/link-categories/{id}/sort',
    'admin/links'                                   => 'admin/links',
    'admin/links/create'                            => 'admin/links/create',
    'admin/links/{id}'                              => 'admin/links/{id}',
    'admin/links/{id}/edit'                         => 'admin/links/{id}/edit',
//    'admin/pages'                                   => 'admin/pages',
//    'admin/pages/create'                            => 'admin/pages/create',
//    'admin/pages/{id}'                              => 'admin/pages/{id}',
//    'admin/pages/{id}/edit'                         => 'admin/pages/{id}/edit',
    'admin/help-categories'                         => 'admin/help-categories',
    'admin/help-categories/create'                  => 'admin/help-categories/create',
    'admin/help-categories/{id}'                    => 'admin/help-categories/{id}',
    'admin/help-categories/{id}/edit'               => 'admin/help-categories/{id}/edit',
    'admin/help-categories/{id}/sort'               => 'admin/help-categories/{id}/sort',
    'admin/helps'                                   => 'admin/helps',
    'admin/helps/create'                            => 'admin/helps/create',
    'admin/helps/{id}'                              => 'admin/helps/{id}',
    'admin/helps/{id}/edit'                         => 'admin/helps/{id}/edit',
    'admin/article-categories'                      => 'admin/article-categories',
    'admin/article-categories/create'               => 'admin/article-categories/create',
    'admin/article-categories/{id}'                 => 'admin/article-categories/{id}',
    'admin/article-categories/{id}/edit'            => 'admin/article-categories/{id}/edit',
    'admin/articles'                                => 'admin/articles',
    'admin/articles/create'                         => 'admin/articles/create',
    'admin/articles/{id}'                           => 'admin/articles/{id}',
    'admin/articles/{id}/edit'                      => 'admin/articles/{id}/edit',
//    'admin/media-categories'                        => 'admin/media-categories',
//    'admin/media-categories/create'                 => 'admin/media-categories/create',
//    'admin/media-categories/{id}'                   => 'admin/media-categories/{id}',
//    'admin/media-categories/{id}/edit'              => 'admin/media-categories/{id}/edit',
//    'admin/media-categories/{id}/sort'              => 'admin/media-categories/{id}/sort',
//    'admin/media-items'                             => 'admin/media-items',
//    'admin/media-items/create'                      => 'admin/media-items/create',
//    'admin/media-items/{id}'                        => 'admin/media-items/{id}',
//    'admin/media-items/{id}/edit'                   => 'admin/media-items/{id}/edit',
    'admin/professional-skills'                     => 'admin/professional-skills',
    'admin/professional-skills/create'              => 'admin/professional-skills/create',
    'admin/professional-skills/{id}'                => 'admin/professional-skills/{id}',
    'admin/professional-skills/{id}/edit'           => 'admin/professional-skills/{id}/edit',
    'admin/topics'                                  => 'admin/topics',
    'admin/topics/create'                           => 'admin/topics/create',
    'admin/topics/{id}'                             => 'admin/topics/{id}',
    'admin/topics/{id}/edit'                        => 'admin/topics/{id}/edit',
    'admin/approved-teachers'                       => 'admin/approved-teachers',
    'admin/registering-teachers'                    => 'admin/registering-teachers',
    'admin/teachers/create'                         => 'admin/teachers/create',
    'admin/teachers/{id}'                           => 'admin/teachers/{id}',
    'admin/teachers/{id}/edit'                      => 'admin/teachers/{id}/edit',
    'admin/approved-students'                       => 'admin/approved-students',
    'admin/registering-students'                    => 'admin/registering-students',
    'admin/students/create'                         => 'admin/students/create',
    'admin/students/{id}'                           => 'admin/students/{id}',
    'admin/students/{id}/edit'                      => 'admin/students/{id}/edit',
    'admin/opening-classrooms'                      => 'admin/opening-classrooms',
    'admin/closed-classrooms'                       => 'admin/closed-classrooms',
    'admin/classrooms/create'                       => 'admin/classrooms/create',
    'admin/classrooms/{id}'                         => 'admin/classrooms/{id}',
    'admin/classrooms/{id}/edit'                    => 'admin/classrooms/{id}/edit',
    'admin/salary-report'                           => 'admin/salary-report',
];