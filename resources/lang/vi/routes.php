<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-09-02
 * Time: 20:25
 */

return [
    'extra'                                         => 'chuc-nang-bo-sung',
    'admin/extra'                                   => 'quan-tri/chuc-nang-bo-sung',
    'errors/{code}'                                 => 'loi-truy-cap/{code}',
    'admin/errors/{code}'                           => 'quan-tri/loi-truy-cap/{code}',

    'user/sign-up'                                  => 'tai-khoan/dang-ky',
    'teacher/sign-up'                               => 'giao-vien/dang-ky',
    'teacher/sign-up/step/{step}'                   => 'giao-vien/dang-ky/buoc/{step}',
    'student/sign-up'                               => 'hoc-vien/dang-ky',
    'student/sign-up/step/{step}'                   => 'hoc-vien/dang-ky/buoc/{step}',
    'teachers'                                      => 'danh-sach-giao-vien',
    'teachers/{id}'                                 => 'giao-vien/{id}',
    'opening-classrooms'                            => 'lop-dang-hoc',
    'closed-classrooms'                             => 'lop-da-hoc-xong',
    'classrooms/{id}'                               => 'lop-hoc/{id}',
    'helps'                                         => 'tro-giup',
    'helps/{slug}'                                  => 'tro-giup/{slug}',

    'profile/account-information'                   => 'tai-khoan/thong-tin-tai-khoan',
    'profile/user-information'                      => 'tai-khoan/thong-tin-nguoi-dung',
    'profile/educations-and-works'                  => 'tai-khoan/hoc-van-va-nghe-nghiep',
    'profile/professional-skills'                   => 'tai-khoan/ky-nang-chuyen-mon',
    'profile/user-works'                            => 'tai-khoan/nghe-nghiep',
    'profile/user-works/{id}'                       => 'tai-khoan/nghe-nghiep/{id}',
    'profile/user-educations'                       => 'tai-khoan/hoc-van',
    'profile/user-educations/{id}'                  => 'tai-khoan/hoc-van/{id}',
    'profile/user-certificates'                     => 'tai-khoan/chung-chi',
    'profile/user-certificates/{id}'                => 'tai-khoan/chung-chi/{id}',
    'profile/teacher-information'                   => 'tai-khoan/thong-tin-giao-vien',
    'profile/teaching-time'                         => 'tai-khoan/thoi-gian-giang-day',
    'profile/payment-information'                   => 'tai-khoan/thong-tin-thanh-toan',

    'me'                                            => 'ca-nhan-toi',
    'me/settings'                                   => 'ca-nhan-toi/thiet-lap',
    'me/account'                                    => 'ca-nhan-toi/tai-khoan',
    'me/documents'                                  => 'ca-nhan-toi/tai-lieu',
    'me/documents/connector'                        => 'ca-nhan-toi/tai-lieu/ket-noi',
    'me/documents/for/ckeditor'                     => 'ca-nhan-toi/tai-lieu/danh-cho/ckeditor',
    'me/documents/for/popup/{input_id}'             => 'ca-nhan-toi/tai-lieu/danh-cho/popup/{input_id}',

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
    'admin/my-documents'                            => 'quan-tri/tai-lieu-cua-toi',
    'admin/user-roles'                              => 'quan-tri/vai-tro-nguoi-dung',
    'admin/users'                                   => 'quan-tri/nguoi-dung',
    'admin/users/create'                            => 'quan-tri/nguoi-dung/them-moi',
    'admin/users/{id}'                              => 'quan-tri/nguoi-dung/{id}',
    'admin/users/{id}/edit'                         => 'quan-tri/nguoi-dung/{id}/chinh-sua',
    'admin/app-options'                             => 'quan-tri/thiet-lap',
    'admin/app-options/{id}'                        => 'quan-tri/thiet-lap/{id}',
    'admin/app-options/{id}/edit'                   => 'quan-tri/thiet-lap/{id}/chinh-sua',
    'admin/extensions'                              => 'quan-tri/tien-ich-mo-rong',
    'admin/extensions/{name}'                       => 'quan-tri/tien-ich-mo-rong/{name}',
    'admin/extensions/{name}/edit'                  => 'quan-tri/tien-ich-mo-rong/{name}/chinh-sua',
    'admin/widgets'                                 => 'quan-tri/cong-cu-hien-thi',
    'admin/widgets/{id}'                            => 'quan-tri/cong-cu-hien-thi/{id}',
    'admin/widgets/{id}/edit'                       => 'quan-tri/cong-cu-hien-thi/{id}/chinh-sua',
    'admin/ui-lang/php'                             => 'quan-tri/ngon-ngu-cho-giao-dien/tap-tin-php',
    'admin/ui-lang/email'                           => 'quan-tri/ngon-ngu-cho-giao-dien/mau-email',
    'admin/link-categories'                         => 'quan-tri/chuyen-muc-lien-ket',
    'admin/link-categories/create'                  => 'quan-tri/chuyen-muc-lien-ket/them-moi',
    'admin/link-categories/{id}'                    => 'quan-tri/chuyen-muc-lien-ket/{id}',
    'admin/link-categories/{id}/edit'               => 'quan-tri/chuyen-muc-lien-ket/{id}/chinh-sua',
    'admin/link-categories/{id}/sort'               => 'quan-tri/chuyen-muc-lien-ket/{id}/sap-xep',
    'admin/links'                                   => 'quan-tri/lien-ket',
    'admin/links/create'                            => 'quan-tri/lien-ket/them-moi',
    'admin/links/{id}'                              => 'quan-tri/lien-ket/{id}',
    'admin/links/{id}/edit'                         => 'quan-tri/lien-ket/{id}/chinh-sua',
//    'admin/pages'                                   => 'quan-tri/chuyen-trang',
//    'admin/pages/create'                            => 'quan-tri/chuyen-trang/them-moi',
//    'admin/pages/{id}'                              => 'quan-tri/chuyen-trang/{id}',
//    'admin/pages/{id}/edit'                         => 'quan-tri/chuyen-trang/{id}/chinh-sua',
    'admin/help-categories'                         => 'quan-tri/chuyen-muc-tro-giup',
    'admin/help-categories/create'                  => 'quan-tri/chuyen-muc-tro-giup/them-moi',
    'admin/help-categories/{id}'                    => 'quan-tri/chuyen-muc-tro-giup/{id}',
    'admin/help-categories/{id}/edit'               => 'quan-tri/chuyen-muc-tro-giup/{id}/chinh-sua',
    'admin/helps'                                   => 'quan-tri/tro-giup',
    'admin/helps/create'                            => 'quan-tri/tro-giup/them-moi',
    'admin/helps/{id}'                              => 'quan-tri/tro-giup/{id}',
    'admin/helps/{id}/edit'                         => 'quan-tri/tro-giup/{id}/chinh-sua',
    'admin/article-categories'                      => 'quan-tri/chuyen-muc-chuyen-de',
    'admin/article-categories/create'               => 'quan-tri/chuyen-muc-chuyen-de/them-moi',
    'admin/article-categories/{id}'                 => 'quan-tri/chuyen-muc-chuyen-de/{id}',
    'admin/article-categories/{id}/edit'            => 'quan-tri/chuyen-muc-chuyen-de/{id}/chinh-sua',
    'admin/articles'                                => 'quan-tri/chuyen-de',
    'admin/articles/create'                         => 'quan-tri/chuyen-de/them-moi',
    'admin/articles/{id}'                           => 'quan-tri/chuyen-de/{id}',
    'admin/articles/{id}/edit'                      => 'quan-tri/chuyen-de/{id}/chinh-sua',
//    'admin/media-categories'                        => 'quan-tri/chuyen-muc-nghe-nhin',
//    'admin/media-categories/create'                 => 'quan-tri/chuyen-muc-nghe-nhin/them-moi',
//    'admin/media-categories/{id}'                   => 'quan-tri/chuyen-muc-nghe-nhin/{id}',
//    'admin/media-categories/{id}/edit'              => 'quan-tri/chuyen-muc-nghe-nhin/{id}/chinh-sua',
//    'admin/media-categories/{id}/sort'              => 'quan-tri/chuyen-muc-nghe-nhin/{id}/sap-xep',
//    'admin/media-items'                             => 'quan-tri/nghe-nhin',
//    'admin/media-items/create'                      => 'quan-tri/nghe-nhin/them-moi',
//    'admin/media-items/{id}'                        => 'quan-tri/nghe-nhin/{id}',
//    'admin/media-items/{id}/edit'                   => 'quan-tri/nghe-nhin/{id}/chinh-sua',
    'admin/professional-skills'                     => 'quan-tri/ky-nang-chuyen-mon',
    'admin/professional-skills/create'              => 'quan-tri/ky-nang-chuyen-mon/them-moi',
    'admin/professional-skills/{id}'                => 'quan-tri/ky-nang-chuyen-mon/{id}',
    'admin/professional-skills/{id}/edit'           => 'quan-tri/ky-nang-chuyen-mon/{id}/chinh-sua',
    'admin/topics'                                  => 'quan-tri/chu-de',
    'admin/topics/create'                           => 'quan-tri/chu-de/them-moi',
    'admin/topics/{id}'                             => 'quan-tri/chu-de/{id}',
    'admin/topics/{id}/edit'                        => 'quan-tri/chu-de/{id}/chinh-sua',
    'admin/approved-teachers'                       => 'quan-tri/giao-vien-da-duyet',
    'admin/registering-teachers'                    => 'quan-tri/giao-vien-dang-ky',
    'admin/teachers/create'                         => 'quan-tri/giao-vien/them-moi',
    'admin/teachers/{id}'                           => 'quan-tri/giao-vien/{id}',
    'admin/teachers/{id}/edit'                      => 'quan-tri/giao-vien/{id}/chinh-sua',
    'admin/approved-students'                       => 'quan-tri/hoc-vien-da-duyet',
    'admin/registering-students'                    => 'quan-tri/hoc-vien-dang-ky',
    'admin/students/create'                         => 'quan-tri/hoc-vien/them-moi',
    'admin/students/{id}'                           => 'quan-tri/hoc-vien/{id}',
    'admin/students/{id}/edit'                      => 'quan-tri/hoc-vien/{id}/chinh-sua',
    'admin/opening-classrooms'                      => 'quan-tri/lop-dang-hoc',
    'admin/closed-classrooms'                       => 'quan-tri/lop-da-hoc-xong',
    'admin/classrooms/create'                       => 'quan-tri/lop-hoc/them-moi',
    'admin/classrooms/{id}'                         => 'quan-tri/lop-hoc/{id}',
    'admin/classrooms/{id}/edit'                    => 'quan-tri/lop-hoc/{id}/chinh-sua',
    'admin/salary-report'                           => 'quan-tri/tinh-luong',
];