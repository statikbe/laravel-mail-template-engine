<html>
<head>
    <title>{{ isset($senderName) ? $senderName : '' }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
{{--Edit as you see fit--}}
    @if(file_exists(app_path() . '/../resources/views/vendor/statikbe/mail/designs/default/styles.css'))
        <style type="text/css">{{ file_get_contents(app_path() . '/../resources/views/vendor/statikbe/mail/designs/default/styles.css') }}</style>
    @else
        <style type="text/css">{{ file_get_contents(app_path() . '/../vendor/statikbe/laravel-mail-template-engine/resources/views/mail/designs/default/styles.css') }}</style>
    @endif
    @if(isset($css))
        <style type="text/css">
            {{ $css }}
        </style>
    @endif
</head>
<body>
    @include($design.'.'.$contentStyle)
</body>
</html>