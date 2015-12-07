<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-09-02
 * Time: 20:25
 */

return [
    'auth'                                          => 'xac-thuc',
    'auth/login'                                    => 'xac-thuc/dang-nhap',
    'auth/logout'                                   => 'xac-thuc/dang-xuat',
    'auth/register'                                 => 'xac-thuc/dang-ky',
    'auth/register/social'                          => 'xac-thuc/dang-ky/mang-xa-hoi',
    'auth/inactive'                                 => 'xac-thuc/chua-kich-hoat',
    'auth/activate'                                 => 'xac-thuc/kich-hoat',
    'auth/activate/{id}/{activation_code}'          => 'xac-thuc/kich-hoat/{id}/{activation_code}',
    'auth/social'                                   => 'xac-thuc/lien-ket',
    'auth/social/{provider}'                        => 'xac-thuc/lien-ket/{provider}',
    'auth/social/callback'                          => 'xac-thuc/lien-ket/kiem-tra',
    'auth/social/callback/{provider}'               => 'xac-thuc/lien-ket/kiem-tra/{provider}',
    'password'                                      => 'quen-mat-khau',
    'password/email'                                => 'quen-mat-khau/thu-dien-tu',
    'password/reset'                                => 'quen-mat-khau/thiet-lap-lai',
    'password/reset/{token}'                        => 'quen-mat-khau/thiet-lap-lai/{token}',

    'admin'                                         => 'quan-tri',
];