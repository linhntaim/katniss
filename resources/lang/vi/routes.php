<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-09-02
 * Time: 20:25
 */

return [
    'my-settings'                               => 'thiet-lap-nguoi-dung',

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

    'documents'                                     => 'tai-lieu',
    'documents/connector'                           => 'tai-lieu/ket-noi',
    'documents/for/ckeditor'                        => 'tai-lieu/danh-cho/ckeditor',
    'documents/for/popup/{input_id}'                => 'tai-lieu/danh-cho/popup/{input_id}',

    'admin'                                         => 'quan-tri',
    'admin/my-documents'                            => 'quan-tri/tai-lieu-cua-toi',
    'admin/user-roles'                              => 'quan-tri/vai-tro-nguoi-dung',
    'admin/users'                                   => 'quan-tri/nguoi-dung',
    'admin/users/add'                               => 'quan-tri/nguoi-dung/them-moi',
    'admin/users/{id}/edit'                         => 'quan-tri/nguoi-dung/{id}/chinh-sua',
    'admin/users/update'                            => 'quan-tri/nguoi-dung/cap-nhat',
    'admin/users/{id}/delete'                       => 'quan-tri/nguoi-dung/{id}/xoa-bo',
    'admin/app-options'                             => 'quan-tri/thiet-lap',
    'admin/extensions'                              => 'quan-tri/tien-ich-mo-rong',
    'admin/extensions/{name}/edit'                  => 'quan-tri/tien-ich-mo-rong/{name}/chinh-sua',
    'admin/extensions/update'                       => 'quan-tri/tien-ich-mo-rong/cap-nhat',
    'admin/extensions/{name}/activate'              => 'quan-tri/tien-ich-mo-rong/{name}/kich-hoat',
    'admin/extensions/{name}/deactivate'            => 'quan-tri/tien-ich-mo-rong/{name}/bo-kich-hoat',
    'admin/widgets'                                 => 'quan-tri/cong-cu-hien-thi',
    'admin/widgets/{id}/edit'                       => 'quan-tri/cong-cu-hien-thi/{id}/chinh-sua',
    'admin/widgets/update'                          => 'quan-tri/cong-cu-hien-thi/cap-nhat',
    'admin/widgets/{id}/delete'                     => 'quan-tri/cong-cu-hien-thi/{id}/xoa-bo',
    'admin/widgets/{id}/activate'                   => 'quan-tri/cong-cu-hien-thi/{id}/kich-hoat',
    'admin/widgets/{id}/deactivate'                 => 'quan-tri/cong-cu-hien-thi/{id}/bo-kich-hoat',
    'admin/widgets/clone'                           => 'quan-tri/cong-cu-hien-thi/tao-ban-sao',
    'admin/ui-lang/php'                             => 'quan-tri/ngon-ngu-cho-giao-dien/tap-tin-php',
    'admin/ui-lang/email'                           => 'quan-tri/ngon-ngu-cho-giao-dien/mau-email',
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