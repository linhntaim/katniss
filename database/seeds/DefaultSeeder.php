<?php

use Illuminate\Database\Seeder;
use Katniss\Everdeen\Models\AppOption;
use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Models\Link;
use Katniss\Everdeen\Models\Permission;
use Katniss\Everdeen\Models\Role;
use Katniss\Everdeen\Models\ThemeWidget;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Models\UserApp;
use Katniss\Everdeen\Models\UserSetting;

class DefaultSeeder extends Seeder
{
    public function run()
    {
        $admin_access_permission = Permission::create([
            'name' => 'access-admin',
            'display_name' => 'Access admin',
            'description' => 'Access admin pages'
        ]);

        $owner_role = Role::create(array(
            'name' => 'owner',
            'display_name' => 'Owner',
            'description' => 'Owner of the system',
            'status' => Role::STATUS_HIDDEN,
        ));

        $owner_role->attachPermission($admin_access_permission);

        $admin_role = Role::create(array(
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Manage operation of the system and important modules'
        ));
        $admin_role->attachPermission($admin_access_permission);

        $tester_role = Role::create(array(
            'name' => 'tester',
            'display_name' => 'Tester',
            'description' => 'Tester of the system',
            'status' => Role::STATUS_HIDDEN,
        ));
        $tester_role->attachPermission($admin_access_permission);

        $user_role = Role::create(array(
            'name' => 'user',
            'display_name' => 'User',
            'description' => 'Normal user'
        ));

        // TODO: Add 1 administrator
        $setting = UserSetting::create();
        $owner = User::create(array(
            'display_name' => 'Owner',
            'name' => 'owner',
            'email' => 'owner@katniss.linhntaim.com',
            'password' => bcrypt(')^KM$bB-W7:Z@8eG'),
            'url_avatar' => appDefaultUserProfilePicture(),
            'url_avatar_thumb' => appDefaultUserProfilePicture(),
            'activation_code' => str_random(32),
            'active' => true,
            'setting_id' => $setting->id,
        ));
        $owner->attachRole($owner_role);

        $setting = UserSetting::create();
        $admin = User::create(array(
            'display_name' => 'Administrator',
            'name' => 'admin',
            'email' => 'admin@katniss.linhntaim.com',
            'password' => bcrypt('123456'),
            'url_avatar' => appDefaultUserProfilePicture(),
            'url_avatar_thumb' => appDefaultUserProfilePicture(),
            'activation_code' => str_random(32),
            'active' => true,
            'setting_id' => $setting->id,
        ));
        $admin->attachRoles([$admin_role, $owner_role]);

        UserApp::create([
            'user_id' => $admin->id,
            'secret' => str_random(32),
            'name' => 'Katniss Web',
            'version' => 'v1',
        ]);

        $setting = UserSetting::create();
        $tester = User::create(array(
            'display_name' => 'Tester',
            'name' => 'tester',
            'email' => 'tester@katniss.linhntaim.com',
            'password' => bcrypt('123456'),
            'url_avatar' => appDefaultUserProfilePicture(),
            'url_avatar_thumb' => appDefaultUserProfilePicture(),
            'activation_code' => str_random(32),
            'active' => true,
            'setting_id' => $setting->id,
        ));
        $tester->attachRole($tester_role);

        $locales = ['en', 'vi'];
        $category = new Category();
        $category->type = Category::TYPE_ARTICLE;
        $data = [
            'en' => [
                'name' => 'Uncategorized',
                'slug' => 'uncategorized',
                'description' => 'For uncategorized articles',
            ],
            'vi' => [
                'name' => 'Chuyên mục chung',
                'slug' => 'chuyen-muc-chung',
                'description' => 'Chứa các chuyên đề không có chuyên mục',
            ]
        ];
        foreach ($locales as $locale) {
            $transData = $data[$locale];
            $trans = $category->translateOrNew($locale);
            $trans->name = $transData['name'];
            $trans->slug = $transData['slug'];
            $trans->description = $transData['description'];
        }
        $category->save();
        AppOption::create([
            'key' => 'extension_app_settings',
            'rawValue' => '{"register_enable":"1","default_article_category":"' . $category->id . '"}',
            'data_type' => 'array',
            'registered_by' => 'ext:app_settings',
        ]);

        $locales = ['en', 'vi'];
        $category = new Category();
        $category->type = Category::TYPE_LINK;
        $data = [
            'en' => [
                'name' => 'Example link category',
                'slug' => 'example-link-category',
                'description' => 'Example link category',
            ],
            'vi' => [
                'name' => 'Chuyên mục liên kết ví dụ',
                'slug' => 'chuyen-muc-lien-ket-vi-du',
                'description' => 'Chuyên mục liên kết ví dụ',
            ]
        ];
        foreach ($locales as $locale) {
            $transData = $data[$locale];
            $trans = $category->translateOrNew($locale);
            $trans->name = $transData['name'];
            $trans->slug = $transData['slug'];
            $trans->description = $transData['description'];
        }
        $category->save();
        $data = [
            [
                'en' => [
                    'name' => 'Author on Facebook',
                    'url' => 'https://www.facebook.com/linhntaim',
                    'description' => 'Author on Facebook',
                ],
                'vi' => [
                    'name' => 'Hồ sơ tác giả trên Facebook',
                    'url' => 'https://www.facebook.com/linhntaim',
                    'description' => 'Hồ sơ tác giả trên Facebook',
                ]
            ],
            [
                'en' => [
                    'name' => 'Author\'s Personal Site',
                    'url' => 'http://linhntaim.com',
                    'description' => 'Author\'s Personal Site',
                ],
                'vi' => [
                    'name' => 'Trang cá nhân của tác giả',
                    'url' => 'http://linhntaim.com',
                    'description' => 'Trang cá nhân của tác giả',
                ]
            ],
            [
                'en' => [
                    'name' => 'Katniss on Github',
                    'url' => 'https://github.com/linhntaim/katniss',
                    'description' => 'Katniss on Github',
                ],
                'vi' => [
                    'name' => 'Katniss trên Github',
                    'url' => 'https://github.com/linhntaim/katniss',
                    'description' => 'Katniss trên Github',
                ]
            ]
        ];
        foreach ($data as $item) {
            $link = new Link();
            foreach ($locales as $locale) {
                $transData = $item[$locale];
                $trans = $link->translateOrNew($locale);
                $trans->name = $transData['name'];
                $trans->description = $transData['description'];
                $trans->url = $transData['url'];
            }
            $link->save();
            $link->categories()->attach($category->id);
        }

        ThemeWidget::create([
            'widget_name' => 'base_links',
            'theme_name' => '',
            'placeholder' => 'default_placeholder',
            'constructing_data' => '{"category_id":"' . $category->id . '","x_locale":{"en":{"name":"PollWidget named Base Links","description":""},"vi":{"name":"Ví dụ về Công cụ hiển thị Base Links","description":""}}}',
            'active' => true,
            'order' => 1
        ]);
        ThemeWidget::create([
            'widget_name' => 'extra_html',
            'theme_name' => '',
            'placeholder' => 'default_placeholder',
            'constructing_data' => '{"x_locale":{"en":{"name":"PollWidget named Extra HTML","description":"","content":"<p><strong>Lorem Ipsum<\/strong><sup>&reg;<\/sup> is simply dummy text of the <em>printing and typesetting industry<\/em>. <span style=\"color:#FFFF00\"><span style=\"background-color:#000000\">Lorem Ipsum<\/span><\/span> has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like <span style=\"font-size:16px\">Aldus PageMaker <\/span>including versions of <em><strong><u>Lorem Ipsum<\/u><\/strong><\/em>. <img alt=\"smiley\" src=\"http:\/\/katniss.linhntaim.com\/assets\/libraries\/ckeditor-4.5.5\/plugins\/smiley\/images\/regular_smile.png\" style=\"height:23px; width:23px\" title=\"smiley\"><\/p>\r\n\r\n<p><img alt=\"\" src=\"http:\/\/katniss.linhntaim.com\/files\/user_2\/katniss.png\" style=\"height:120px; width:120px\"><\/p>\r\n"},"vi":{"name":"V\u00ed d\u1ee5 v\u1ec1 C\u00f4ng c\u1ee5 hi\u1ec3n th\u1ecb Extra HTML","description":"","content":"<p><strong>Lorem Ipsum<\/strong><sup>&reg;<\/sup> is simply dummy text of the <em>printing and typesetting industry<\/em>. <span style=\"color:#FFFF00\"><span style=\"background-color:#000000\">Lorem Ipsum<\/span><\/span> has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like <span style=\"font-size:16px\">Aldus PageMaker <\/span>including versions of <em><strong><u>Lorem Ipsum<\/u><\/strong><\/em>. <img alt=\"smiley\" src=\"http:\/\/katniss.linhntaim.com\/assets\/libraries\/ckeditor-4.5.5\/plugins\/smiley\/images\/regular_smile.png\" style=\"height:23px; width:23px\" title=\"smiley\"><\/p>\r\n\r\n<p><img alt=\"\" src=\"http:\/\/katniss.linhntaim.com\/files\/user_2\/katniss.png\" style=\"height:120px; width:120px\"><\/p>\r\n"}}}',
            'active' => true,
            'order' => 2
        ]);
    }
}
