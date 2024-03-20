<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <!-- Primary Meta Tags -->
        <title>@yield('title') | {{ config('app.name') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ uploadPath(config('app.favicon'))}}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="theme-color" content="#ffffff">

        <!-- Styles -->
        <link type="text/css" href="{{ adminAsset('css/app.css?v=1.0.0')}}" rel="stylesheet">
        {!! \AdminAsset::renderStyles() !!}
        @stack('styles')

        <!-- Global script -->
        <script>
            window.OCMS = {!! isset($global_variables) ? json_encode($global_variables) : '{}' !!}
        </script>
    </head>
    <body>
        @include('admin.inc.sidebar')
        <main class="content">
            @include('admin.inc.header')
            @yield('content')
        </main>
        @stack('modal')

        <script src="{{ adminAsset('js/app.js') }}"></script>
        <script src="{{ adminAsset('js/theme.js') }}"></script>
        {!! \AdminAsset::renderScripts() !!}
        {!! \AdminAsset::renderCustomScripts() !!}

        @if(session('toast_success'))
            <script>
                Notyf.success(`{!! session('toast_success') !!}`)
            </script>
        @endif
        @if(session('toast_error'))
            <script>
                Notyf.error(`{!! session('toast_error') !!}`)
            </script>
        @endif
        @stack('scripts')
    </body>
</html>
