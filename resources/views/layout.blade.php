<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title') / Ialama Art Gallery & Shop</title>
        <link rel="icon" type = "image/png" href="{{ asset('storage/logo.png') }}" sizes="16x16">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/style.css') }}">
        @yield('styles')
        <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>
    </head>
    <body>
        @include ('header')
        <div class="wrap-content">
            @yield('content')
        </div>
        @include ('footer')
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
        @yield('scripts')
        <div class="wrap-modal wrap-modal--hidden"></div>
    </body>
</html>