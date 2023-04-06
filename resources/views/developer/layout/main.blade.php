<!doctype html>
<html lang="en">

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

    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ \App\Models\Settings::first() ? \App\Models\Settings::first()->metaTitle : '' }}</title>
    <link rel="icon" href="{{ asset('ui_assets/img/favicon.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('ui_assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('ui_assets/css/common.css?ver=' . time()) }}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('ui.style')
</head>

@php
$audio = 'ring.mp3';
$settings_data = DB::table('settings')->first();
if ($settings_data) {
    $audio = $settings_data->alertSound ?? 'ring.mp3';
}
@endphp

<body allow="autoplay">

    <div class="wrapper" id="top">
        <div class="page">
            @yield('content')
            <div class="footer-call-notification" id="call_notification" style="display: none;">

                <div class="phone-icon-wrap">
                    <div class="icon-circle-wrap">
                        <img src="/ui_assets/img/icons/phone-white.svg" alt="call-icon">
                    </div>
                </div>
                <div class="notif-text"><span id="call_client_name"></span>{{ __('frontend.notifText') }}</div>
                <div class="call-join-btn-wrap">
                    <a id="call_link" href="javascript:void(0)"
                        class="btn custom-btn-primary btn-block paybtn-lg">{{ __('frontend.joinNow') }}</a>
                </div>
            </div>
            @include('developer.layout.sidebar')
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
    <audio id="call_audio" src="{{ asset('upload/' . $audio) }}"></audio>
    <script>
        window.PUSHER_APP_KEY = '{{ config('broadcasting.connections.pusher.key') }}';
        window.APP_DEBUG = {{ config('app.debug') ? 'true' : 'false' }};
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function play() {
            console.log('play');
            const audio = $('#call_audio')[0];
            audio.loop = true;
            audio.play();
            setTimeout(function() {
                console.log('stop');
                const audio = $('#call_audio')[0];
                audio.pause();
            }, 15000);
        }
        Echo.channel('developer_room.{{ Auth::user()->id }}')
            .listen('MeetingAlert', (e) => {
                var url = "{{ env('APP_URL') }}/freelancer/call/" + e.meeting_uid;
                if (window.location.href != url) {
                    $('#call_notification').show();
                    play()
                    setTimeout(function() {
                        play();
                        setTimeout(function() {
                            play();
                        }, 30000);
                    }, 30000);
                    $('#call_link').attr('href', "{{ env('APP_URL') }}/freelancer/call/" + e.meeting_uid);
                    document.getElementById('call_client_name').textContent = e.client_name;
                }
            })

        $('#call_link').click(function() {
            $('#call_notification').hide();
        })
    </script>
    <script src="{{ asset('ui_assets/js/scripts.js') }}"></script>
    @stack('ui.script')
</body>

</html>
