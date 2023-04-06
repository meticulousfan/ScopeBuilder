<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="title" content="{{ \App\Models\Settings::first() ? \App\Models\Settings::first()->metaTitle : '' }}">
    <meta name="description" content="{{ \App\Models\Settings::first() ? \App\Models\Settings::first()->metaDescription : '' }}">
    <meta name="image" content="{{ asset('upload/' .(\App\Models\Settings::first() ? \App\Models\Settings::first()->metaImage : '')) }}">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Scope Builder | @yield('page-title')</title>
    <link rel="icon" href="{{ asset('ui_assets/img/favicon.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('ui_assets/css/main.css') }}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    @stack('ui.style')
</head>

<body>

    <div class="wrapper" id="top">
        <div class="page">
            @yield('content')
            @include('admin.layout.sidebar')
        </div>
    </div>

    <div class="modal video-modal" id="video-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" aria-label="Close modal"></button>
                <div class="modal-video">
                    <div id="modal-video-iframe"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('ui_assets/js/scripts.js') }}"></script>
    @stack('ui.script')
</body>

</html>
