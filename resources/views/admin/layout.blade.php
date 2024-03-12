<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <link rel="icon" type = "image/png" href="{{ asset('storage/logo.png') }}" sizes="16x16">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/dashboard.css') }}">
        @yield('styles')
        <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>
    </head>
    <body>
        <div class="wrap-dashboard-header">
            <div class="dashboard-header">
                @include ('admin.breadcrumbs')
                <div class="dashboard-header__buttons">
                    @yield ('panel')
                </div>
            </div>
        </div>
        <div class="wrap-content">
            @yield('content')
        </div>
        <script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
        @yield('scripts')
        <div class="wrap-modal wrap-modal--hidden"></div>
    </body>
</html>