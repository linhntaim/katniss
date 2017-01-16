@extends('home_themes.wow_skype.master.master_footer_placed')
@section('footer')
    <div class="wrapper">
        <div class="row">
            <div class="col-sm-4">
                <div class="margin-bottom-10">
                    <img class="logo" src="{{ themeImageAsset('logo.png') }}">
                </div>
                <div>
                    <p><em>Cộng đồng kết nối giữa các học viên và các giáo viên tiếng Anh hàng đầu trên thế giới.</em></p>
                    <p class="bold-700">Email: contact@wowskype.vn<br>
                        Hotline: 1900 222 333</p>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="pull-left margin-right-30">
                    <h5 class="color-master uppercase bold-700">{{ trans_choice('label.teacher', 1) }}</h5>
                    <ul class="list-unstyled">
                        <li class="margin-bottom-10">
                            <a href="#">
                                <span class="color-normal">Demo link</span>
                            </a>
                        </li>
                        <li class="margin-bottom-10">
                            <a href="#">
                                <span class="color-normal">Demo link</span>
                            </a>
                        </li>
                        <li class="margin-bottom-10">
                            <a href="#">
                                <span class="color-normal">Demo link</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="pull-left margin-right-30">
                    <h5 class="color-master uppercase bold-700">{{ trans_choice('label.student', 1) }}</h5>
                    <ul class="list-unstyled">
                        <li class="margin-bottom-10">
                            <a href="#">
                                <span class="color-normal">Demo link</span>
                            </a>
                        </li>
                        <li class="margin-bottom-10">
                            <a href="#">
                                <span class="color-normal">Demo link</span>
                            </a>
                        </li>
                        <li class="margin-bottom-10">
                            <a href="#">
                                <span class="color-normal">Demo link</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="pull-left margin-right-30">
                    <h5 class="color-master uppercase bold-700">WowSkype</h5>
                    <ul class="list-unstyled">
                        <li class="margin-bottom-10">
                            <a href="#">
                                <span class="color-normal">Demo link</span>
                            </a>
                        </li>
                        <li class="margin-bottom-10">
                            <a href="#">
                                <span class="color-normal">Demo link</span>
                            </a>
                        </li>
                        <li class="margin-bottom-10">
                            <a href="#">
                                <span class="color-normal">Demo link</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="pull-right text-center width-150 min-width-sm-full">
                    <div class="margin-bottom-5 margin-top-10">
                        <a class="btn btn-primary btn-block uppercase bold-700"
                           href="{{ homeUrl('auth/login') }}">{{ trans('form.action_login') }}</a>
                    </div>
                    <div class="margin-bottom-10">
                        <a href="{{ homeUrl('user/sign-up') }}">
                            <span class="color-normal">{!! trans('label.or_sign_up_here') !!}</span>
                        </a>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection