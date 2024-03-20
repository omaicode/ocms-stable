@php
    $title = isset($title) ? $title : (get_theme_option('seo_title') ?: get_theme_option('site_title'));
    $desc  = isset($short_description) ? $short_description : (get_theme_option('seo_description') ?: get_theme_option('site_description'));
    $image = isset($image_url) ? $image_url : get_theme_image('seo_og_image', theme_asset('img/og.png'));
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="description" content="{{ $desc }}">
        <meta name="keywords" content="{{ get_theme_option('site_keywords') }}">

        <!-- Twitter Meta Data -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:site" content="{{ get_theme_option('site_name') }}" />
        <meta name="twitter:title" content="{{ $title }}" />
        <meta name="twitter:description" content="{{ $desc }}" />
        <meta name="twitter:image" content="{{ $image }}" />        

        <!-- Facebook Metadata -->
        <meta property="og:title" content="{{ $title }}" />
        <meta property="og:type" content="article" />
        <meta property="og:description" content="{{ $desc }}" />
        <meta property="og:image" content="{{ $image }}" />        

        <title>{{ $title }} | {{ get_theme_option('site_name') }}</title>

        <!-- Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
        
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('vendor/bootstrap4/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ theme_asset('css/main.css') }}">

        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        {!! app("Popup")->style() !!}
        {!! analytics_script() !!}
    </head>
    <body>
        @yield('content')
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap4/js/bootstrap.min.js') }}"></script>
        <script src="{{ theme_asset('js/main.js') }}"></script>
        <script src="{{ theme_asset('js/theme.js') }}"></script>
        <script src="{{ theme_asset('js/jqBootstrapValidation.js') }}"></script>
        {!! app("Popup")->script() !!}
    </body>
</html>
