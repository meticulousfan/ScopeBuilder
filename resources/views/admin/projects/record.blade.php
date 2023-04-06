@extends('admin.layout.main')
@section('page-title')
    Recordings
@endsection

@section('page-caption')
Recordings
@endsection

@section('content')
        <iframe id="call-iframe" allow="geolocation *; microphone *; camera *; display-capture *;" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" sandbox="allow-same-origin allow-scripts allow-modals allow-forms" scrolling="no" src="{{$url}}"></iframe>
        <style>
            #call-iframe {
                width:100%;
                height:100vh;
                border:0
            }
            .loader {
                display: inline-block;
                position: relative;
                width: 80px;
                height: 80px;
            }
            .loader div {
                position: absolute;
                top: 33px;
                width: 13px;
                height: 13px;
                border-radius: 50%;
                background: var(--blue-1000);
                animation-timing-function: cubic-bezier(0, 1, 1, 0);
            }
            .loader div:nth-child(1) {
                left: 8px;
                animation: loader1 0.6s infinite;
            }
            .loader div:nth-child(2) {
                left: 8px;
                animation: loader2 0.6s infinite;
            }
            .loader div:nth-child(3) {
                left: 32px;
                animation: loader2 0.6s infinite;
            }
            .loader div:nth-child(4) {
                left: 56px;
                animation: loader3 0.6s infinite;
            }
            @keyframes loader1 {
                0% {
                    transform: scale(0);
                }
                100% {
                    transform: scale(1);
                }
            }
            @keyframes loader3 {
                0% {
                    transform: scale(1);
                }
                100% {
                    transform: scale(0);
                }
            }
            @keyframes loader2 {
                0% {
                    transform: translate(0, 0);
                }
                100% {
                    transform: translate(24px, 0);
                }
            }
        </style>
@endsection