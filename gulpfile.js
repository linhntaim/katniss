const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

elixir(function (mix) {
    // mix.scripts([
    //     'js.cookie.js',
    //     'helpers.js',
    //     'class.number_format_helper.js',
    //     'class.katniss_api.js',
    //     'refresh_session.js',
    //     'gui.jquery.js'
    // ], 'public/assets/libraries/katniss.home.js');
    //
    // mix.scripts([
    //     'js.cookie.js',
    //     'helpers.js',
    //     'slug.js',
    //     'slug.jquery.js',
    //     'class.number_format_helper.js',
    //     'class.katniss_api.js',
    //     'refresh_session.js',
    //     'gui.jquery.js'
    // ], 'public/assets/libraries/katniss.admin.js');

    // mix.scripts([
    //     'realtime.pusher.js',
    //     'sounds.js',
    //     'conversation.js'
    // ], 'public/assets/libraries/katniss.conversation.js');
    // mix.styles('conversation.css', 'public/assets/libraries/katniss.conversation.css');

    // mix.styles('modal_cropper_image.css', 'public/assets/libraries/modal_cropper_image.css');
    // mix.scripts('modal_cropper_image.js', 'public/assets/libraries/modal_cropper_image.js');
    //
    // mix.scripts([
    //     'google_maps_markers.js'
    // ], 'public/assets/libraries/google_maps_markers.js');

    mix.styles([
        'wow_skype/common.css',
        'wow_skype/nav_wizard.css',
        'wow_skype/header.css',
        'wow_skype/main.css',
        'wow_skype/footer.css',
        'wow_skype/page.sign_up.css',
        'wow_skype/page.teacher_sign_up.css',
        'wow_skype/page.profile_account_information.css',
        'wow_skype/page.teachers.css',
        'wow_skype/page.student_sign_up.css',
        'wow_skype/page.classrooms.css'
    ], 'public/assets/home_themes/wow_skype/css/style.css');
});