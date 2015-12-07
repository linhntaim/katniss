<!DOCTYPE html>
<html lang="{{ $site_locale }}">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>{{ trans('auth.forgot_head') }} <a href="{{ homeURL($site_locale) }}">{{ $site_name }}</a></h2>

<div>
    {{ trans('auth.forgot_click') }}: {{ localizedURL('password/reset/{token}', ['token' => $token], $site_locale) }}
</div>
</body>
</html>
