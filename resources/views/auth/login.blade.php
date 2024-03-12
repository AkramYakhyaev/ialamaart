<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Log in / Ialama Art Gallery & Shop</title>
        <link rel="icon" type = "image/png" href="{{ asset('storage/logo.png') }}" sizes="16x16">
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/style.css') }}">
        @yield('styles')
        <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>
    </head>
    <body>
        <div class="auth">
            <form class="login-form">
                <input class="login-form__input login-form__input--email" type="text" placeholder="Login">
                <input class="login-form__input login-form__input--password" type="password" placeholder="Password">
                <button class="login-form__submit">Log in</button>
            </form>
        </div>
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    </body>
</html>