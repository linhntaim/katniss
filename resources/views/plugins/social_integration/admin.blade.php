@section('lib_styles')
    <link rel="stylesheet" href="{{ libraryAsset('iCheck/square/blue.css') }}">
@endsection
@section('lib_scripts')
    <script src="{{ libraryAsset('iCheck/icheck.min.js') }}"></script>
@endsection
@section('extended_scripts')
    <script>
        {!! cdataOpen() !!}
        function enableAlong(id) {
            jQuery(id).on('ifChanged', function () {
                jQuery('[data-enable-target="' + id + '"]').prop('disabled', !jQuery(this).is(':checked'))
            }).trigger('ifChanged');
        }
        jQuery(document).ready(function () {
            jQuery('[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            enableAlong('#inputFacebookCommentEnable');
            enableAlong('#inputFacebookLikeEnable');
            enableAlong('#inputFacebookShareEnable');
            enableAlong('#inputFacebookRecommendEnable');
            enableAlong('#inputLinkedInShareEnable')
        });
        {!! cdataClose() !!}
    </script>
@endsection

<div class="box">
    <div class="box-header">
        <h3 class="box-title">
            Facebook
        </h3>
    </div>
    <div class="box-body">
        <div class="form-group">
            <div class="checkbox icheck">
                <label for="inputFacebookEnable">
                    <input id="inputFacebookEnable" type="checkbox" name="facebook_enable"
                           value="1"{{ $facebook_enable ? ' checked' : '' }}>
                    &nbsp; {{ trans('social_integration.facebook_enable') }}
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="inputFacebookAppId">Facebook App ID</label>
            <input id="inputFacebookAppId" type="text" class="form-control" name="facebook_app_id" value="{{ $facebook_app_id }}">
        </div>
        <hr>
        <div class="form-group">
            <div class="checkbox icheck">
                <label for="inputFacebookCommentEnable">
                    <input id="inputFacebookCommentEnable" type="checkbox" name="facebook_comment_enable"
                           value="1"{{ $facebook_comment_enable ? ' checked' : '' }}>
                    &nbsp; {{ trans('social_integration.facebook_comment_enable') }}
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="inputFacebookCommentColorScheme">{{ trans('social_integration.facebook_comment_color_scheme') }}</label>
                    <select id="inputFacebookCommentColorScheme" name="facebook_comment_color_scheme"
                            class="form-control" data-enable-target="#inputFacebookCommentEnable">
                        @foreach($facebook_comment_color_scheme_values as $facebook_comment_color_scheme_value)
                            <option value="{{ $facebook_comment_color_scheme_value }}"{{ $facebook_comment_color_scheme_value == $facebook_comment_color_scheme ? ' selected' : '' }}>
                                {{ trans('social_integration.facebook_comment_color_scheme_'.$facebook_comment_color_scheme_value) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="inputFacebookCommentNumPosts">{{ trans('social_integration.facebook_comment_num_posts') }}</label>
                    <input id="inputFacebookCommentNumPosts" type="number" min="1"
                           name="facebook_comment_num_posts" value="{{ $facebook_comment_num_posts }}"
                           class="form-control" data-enable-target="#inputFacebookCommentEnable">
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="inputFacebookCommentOrderBy">{{ trans('social_integration.facebook_comment_order_by') }}</label>
                    <select id="inputFacebookCommentOrderBy" name="facebook_comment_order_by" class="form-control" data-enable-target="#inputFacebookCommentEnable">
                        @foreach($facebook_comment_order_by_values as $facebook_comment_order_by_value)
                            <option value="{{ $facebook_comment_order_by_value }}"{{ $facebook_comment_order_by_value == $facebook_comment_order_by ? ' selected' : '' }}>
                                {{ trans('social_integration.facebook_comment_order_by_'.$facebook_comment_order_by_value) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="checkbox icheck">
                <label for="inputFacebookLikeEnable">
                    <input id="inputFacebookLikeEnable" type="checkbox" name="facebook_like_enable"
                           value="1"{{ $facebook_like_enable ? ' checked' : '' }}>
                    &nbsp; {{ trans('social_integration.facebook_like_enable') }}
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="inputFacebookLikeLayout">{{ trans('social_integration.facebook_like_layout') }}</label>
                    <select id="inputFacebookLikeLayout" name="facebook_like_layout"
                            class="form-control" data-enable-target="#inputFacebookLikeEnable">
                        @foreach($facebook_like_layout_values as $facebook_like_layout_value)
                            <option value="{{ $facebook_like_layout_value }}"{{ $facebook_like_layout_value == $facebook_like_layout ? ' selected' : '' }}>
                                {{ trans('social_integration.facebook_like_layout_'.$facebook_like_layout_value) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="checkbox icheck">
                <label for="inputFacebookShareEnable">
                    <input id="inputFacebookShareEnable" type="checkbox" name="facebook_share_enable"
                           value="1"{{ $facebook_share_enable ? ' checked' : '' }}>
                    &nbsp; {{ trans('social_integration.facebook_share_enable') }}
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="inputFacebookShareLayout">{{ trans('social_integration.facebook_share_layout') }}</label>
                    <select id="inputFacebookShareLayout" name="facebook_share_layout"
                            class="form-control" data-enable-target="#inputFacebookShareEnable">
                        @foreach($facebook_share_layout_values as $facebook_share_layout_value)
                            <option value="{{ $facebook_share_layout_value }}"{{ $facebook_share_layout_value == $facebook_share_layout ? ' selected' : '' }}>
                                {{ trans('social_integration.facebook_share_layout_'.$facebook_share_layout_value) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="checkbox icheck">
                <label for="inputFacebookRecommendEnable">
                    <input id="inputFacebookRecommendEnable" type="checkbox" name="facebook_recommend_enable"
                           value="1"{{ $facebook_recommend_enable ? ' checked' : '' }}>
                    &nbsp; {{ trans('social_integration.facebook_recommend_enable') }}
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="inputFacebookRecommendLayout">{{ trans('social_integration.facebook_recommend_layout') }}</label>
                    <select id="inputFacebookRecommendLayout" name="facebook_recommend_layout"
                            class="form-control" data-enable-target="#inputFacebookRecommendEnable">
                        @foreach($facebook_recommend_layout_values as $facebook_recommend_layout_value)
                            <option value="{{ $facebook_recommend_layout_value }}"{{ $facebook_recommend_layout_value == $facebook_recommend_layout ? ' selected' : '' }}>
                                {{ trans('social_integration.facebook_recommend_layout_'.$facebook_recommend_layout_value) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="checkbox icheck">
                <label for="inputFacebookSendEnable">
                    <input id="inputFacebookSendEnable" type="checkbox" name="facebook_send_enable"
                           value="1"{{ $facebook_send_enable ? ' checked' : '' }}>
                    &nbsp; {{ trans('social_integration.facebook_send_enable') }}
                </label>
            </div>
        </div>
    </div>
</div>
<div class="box">
    <div class="box-header">
        <h3 class="box-title">
            Twitter
        </h3>
    </div>
    <div class="box-body">
        <div class="form-group">
            <div class="checkbox icheck">
                <label for="inputTwitterEnable">
                    <input id="inputTwitterEnable" type="checkbox" name="twitter_enable"
                           value="1"{{ $twitter_enable ? ' checked' : '' }}>
                    &nbsp; {{ trans('social_integration.twitter_enable') }}
                </label>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="checkbox icheck">
                <label for="inputTwitterShareEnable">
                    <input id="inputTwitterShareEnable" type="checkbox" name="twitter_share_enable"
                           value="1"{{ $twitter_share_enable ? ' checked' : '' }}>
                    &nbsp; {{ trans('social_integration.twitter_share_enable') }}
                </label>
            </div>
        </div>
    </div>
</div>
<div class="box">
    <div class="box-header">
        <h3 class="box-title">
            LinkedIn
        </h3>
    </div>
    <div class="box-body">
        <div class="form-group">
            <div class="checkbox icheck">
                <label for="inputLinkedInEnable">
                    <input id="inputLinkedInEnable" type="checkbox" name="linkedin_enable"
                           value="1"{{ $linkedin_enable ? ' checked' : '' }}>
                    &nbsp; {{ trans('social_integration.linkedin_enable') }}
                </label>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="checkbox icheck">
                <label for="inputLinkedInShareEnable">
                    <input id="inputLinkedInShareEnable" type="checkbox" name="linkedin_share_enable"
                           value="1"{{ $linkedin_share_enable ? ' checked' : '' }}>
                    &nbsp; {{ trans('social_integration.linkedin_share_enable') }}
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="inputLinkedInShareCountMode">{{ trans('social_integration.linkedin_share_count_mode') }}</label>
                    <select id="inputLinkedInShareCountMode" name="linkedin_share_count_mode"
                            class="form-control" data-enable-target="#inputLinkedInShareEnable">
                        @foreach($linkedin_share_count_mode_values as $linkedin_share_count_mode_value)
                            <option value="{{ $linkedin_share_count_mode_value }}"{{ $linkedin_share_count_mode_value == $linkedin_share_count_mode ? ' selected' : '' }}>
                                {{ trans('social_integration.linkedin_share_count_mode_'.$linkedin_share_count_mode_value) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>