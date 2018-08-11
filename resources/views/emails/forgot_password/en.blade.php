<!DOCTYPE html>
<html lang="{{ $site_locale }}">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>You confirmed forgetting your password at <a href="{{ $site_url }}">{{ $site_name }}</a></h2>
<p>
    Click here to reset your password:<br>
    <a href="{{ $reset_url }}">{{ $reset_url }}</a>
</p>
<p>If you don't recognize this action, please don't follow the link above.</p>
<br>
<br>
<p>Best regards,<br>
From {{ $site_name }} Team</p>
</body>
</html>
