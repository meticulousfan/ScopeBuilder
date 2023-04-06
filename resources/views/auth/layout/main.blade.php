<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="UTF-8">
    <meta name="title" content="{{ \App\Models\Settings::first() ? \App\Models\Settings::first()->metaTitle : '' }}">
    <meta name="description" content="{{ \App\Models\Settings::first() ? \App\Models\Settings::first()->metaDescription : '' }}">
    <meta name="image" content="{{ asset('upload/' .(\App\Models\Settings::first() ? \App\Models\Settings::first()->metaImage : '')) }}">
    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="{{ \App\Models\Settings::first() ? \App\Models\Settings::first()->metaTitle : '' }}">
    <meta property="og:description" content="{{ \App\Models\Settings::first() ? \App\Models\Settings::first()->metaDescription : '' }}">
    <meta property="og:image" content="{{ asset('upload/' .(\App\Models\Settings::first() ? \App\Models\Settings::first()->metaImage : '')) }}">

    <!-- Twitter -->
    <meta property="twitter:title" content="{{ \App\Models\Settings::first() ? \App\Models\Settings::first()->metaTitle : '' }}">
    <meta property="twitter:description" content="{{ \App\Models\Settings::first() ? \App\Models\Settings::first()->metaDescription : '' }}">
    <meta property="twitter:image" content="{{ asset('upload/' .(\App\Models\Settings::first() ? \App\Models\Settings::first()->metaImage : '')) }}">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ \App\Models\Settings::first() ? \App\Models\Settings::first()->metaTitle : '' }}</title>
    <link rel="icon" href="{{ asset('ui_assets/img/favicon.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{asset('ui_assets/css/main.css')}}">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    @stack('ui.style')
</head>
<body>
	<div class="wrapper" id="top">
		@yield('content')
	</div>

    <script src="{{asset('ui_assets/js/scripts.js')}}"></script>
    @stack('ui.script')
</body>
</html>