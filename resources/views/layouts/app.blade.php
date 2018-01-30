<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        {{ Html::style(mix('assets/app/css/app.css')) }}

        {{--Head--}}
        @yield('head')

        {{--Styles--}}
        @yield('styles')
    </head>
    <body class="@yield('body_class')">

            {{--Page--}}
            @yield('page')

            {{--Common Scripts--}}
            {{ Html::script(mix('assets/app/js/app.js')) }}

            {{--Scripts--}}
            @yield('scripts')
    </body>
</html>
