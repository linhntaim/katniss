@section('lib_styles')
    <link rel="stylesheet" href="{{ _kExternalLink('select2-css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ _kExternalLink('select2-js') }}"></script>
@endsection
@section('extended_scripts')
    @include('file_manager.open_documents_script')
    <script>
        $(function () {
            $('.select2').select2();
        });
    </script>
@endsection
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('form.action_edit') }}</h3>
            </div>
            <form method="post" action="{{ currentFullUrl() }}">
                {{ csrf_field() }}
                {{ method_field('put') }}
                <div class="box-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="inputHomeDescription">{{ trans('wow_skype_theme.home_description') }}</label>
                        <input class="form-control" id="inputHomeDescription" name="home_description"
                               placeholder="{{ trans('wow_skype_theme.home_description') }}" type="text" value="{{ $home_description }}">
                    </div>
                    <div class="form-group">
                        <label for="inputHomeEmail">{{ trans('wow_skype_theme.home_email') }}</label>
                        <input class="form-control" id="inputHomeEmail" name="home_email"
                               placeholder="{{ trans('wow_skype_theme.home_email') }}" type="text" value="{{ $home_email }}">
                    </div>
                    <div class="form-group">
                        <label for="inputHomeHotLine">{{ trans('wow_skype_theme.home_hot_line') }}</label>
                        <input class="form-control" id="inputHomeHotLine" name="home_hot_line"
                               placeholder="{{ trans('wow_skype_theme.home_hot_line') }}" type="text" value="{{ $home_hot_line }}">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="inputSocialFacebook">{{ trans('wow_skype_theme.social_facebook') }}</label>
                        <input class="form-control" id="inputSocialFacebook" name="social_facebook"
                               placeholder="{{ trans('wow_skype_theme.social_facebook') }}" type="text" value="{{ $social_facebook }}">
                    </div>
                    <div class="form-group">
                        <label for="inputSocialTwitter">{{ trans('wow_skype_theme.social_twitter') }}</label>
                        <input class="form-control" id="inputSocialTwitter" name="social_twitter"
                               placeholder="{{ trans('wow_skype_theme.social_twitter') }}" type="text" value="{{ $social_twitter }}">
                    </div>
                    <div class="form-group">
                        <label for="inputSocialInstagram">{{ trans('wow_skype_theme.social_instagram') }}</label>
                        <input class="form-control" id="inputSocialInstagram" name="social_instagram"
                               placeholder="{{ trans('wow_skype_theme.social_instagram') }}" type="text" value="{{ $social_instagram }}">
                    </div>
                    <div class="form-group">
                        <label for="inputSocialGooglePlus">{{ trans('wow_skype_theme.social_gplus') }}</label>
                        <input class="form-control" id="inputSocialGooglePlus" name="social_gplus"
                               placeholder="{{ trans('wow_skype_theme.social_gplus') }}" type="text" value="{{ $social_gplus }}">
                    </div>
                    <div class="form-group">
                        <label for="inputSocialYoutube">{{ trans('wow_skype_theme.social_youtube') }}</label>
                        <input class="form-control" id="inputSocialYoutube" name="social_youtube"
                               placeholder="{{ trans('wow_skype_theme.social_youtube') }}" type="text" value="{{ $social_youtube }}">
                    </div>
                    <div class="form-group">
                        <label for="inputSocialSkype">{{ trans('wow_skype_theme.social_skype') }}</label>
                        <input class="form-control" id="inputSocialSkype" name="social_skype"
                               placeholder="{{ trans('wow_skype_theme.social_skype') }}" type="text" value="{{ $social_skype }}">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="inputTeacherSupportSkypeId">{{ trans('wow_skype_theme.ts_skype_id') }}</label>
                        <input class="form-control" id="inputTeacherSupportSkypeId" name="ts_skype_id"
                               placeholder="{{ trans('wow_skype_theme.ts_skype_id') }}" type="text" value="{{ $ts_skype_id }}">
                    </div>
                    <div class="form-group">
                        <label for="inputTeacherSupportSkypeName">{{ trans('wow_skype_theme.ts_skype_name') }}</label>
                        <input class="form-control" id="inputTeacherSupportSkypeName" name="ts_skype_name"
                               placeholder="{{ trans('wow_skype_theme.ts_skype_name') }}" type="text" value="{{ $ts_skype_name }}">
                    </div>
                    <div class="form-group">
                        <label for="inputTeacherSupportEmail">{{ trans('wow_skype_theme.ts_email') }}</label>
                        <input class="form-control" id="inputTeacherSupportEmail" name="ts_email"
                               placeholder="{{ trans('wow_skype_theme.ts_email') }}" type="text" value="{{ $ts_email }}">
                    </div>
                    <div class="form-group">
                        <label for="inputTeacherSupportHotLine">{{ trans('wow_skype_theme.ts_hot_line') }}</label>
                        <input class="form-control" id="inputTeacherSupportHotLine" name="ts_hot_line"
                               placeholder="{{ trans('wow_skype_theme.ts_hot_line') }}" type="text" value="{{ $ts_hot_line }}">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="inputKnowledgeCoverImage">{{ trans('wow_skype_theme.knowledge_cover_image') }}</label>
                        <div class="input-group">
                            <input class="form-control" id="inputKnowledgeCoverImage" name="knowledge_cover_image"
                                   placeholder="{{ trans('wow_skype_theme.knowledge_cover_image') }}" type="text" value="{{ $knowledge_cover_image }}">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary image-from-documents"
                                        data-input-id="inputKnowledgeCoverImage">
                                    <i class="fa fa-server"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputKnowledgeDefaultArticleImage">{{ trans('wow_skype_theme.knowledge_default_article_image') }}</label>
                        <div class="input-group">
                            <input class="form-control" id="inputKnowledgeDefaultArticleImage" name="knowledge_default_article_image"
                                   placeholder="{{ trans('wow_skype_theme.knowledge_default_article_image') }}" type="text" value="{{ $knowledge_default_article_image }}">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary image-from-documents"
                                        data-input-id="inputKnowledgeDefaultArticleImage">
                                    <i class="fa fa-server"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">{{ trans('form.action_save') }}</button>
                    <div class="pull-right">
                        <button class="btn btn-default" type="reset">{{ trans('form.action_reset') }}</button>
                    </div>
                </div>
            </form>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>