@extends('client.layout.main')

@section('page-title')
    Questionnaires
@endsection

@section('page-caption')
    Questionnaires
@endsection

@push('ui.style')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css?ver=' . time()) }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100&family=Roboto:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">
@endpush

@section('content')
    <div class="bg-light">
        <div class="bg-light">
            @if(!auth()->check())
            <div class="fixed-top top-notif-wrap">
                <div class="top-notif">
                    <a href="{{ route('client.register.form') }}">{{ __('frontend.signup') }}</a>&nbsp;&nbsp;{{ __('frontend.tocontinue_and_save_later') }}
                </div>
            </div>
            @endif
        <div id="questionnairesclient">
            <form action="{{ route('client.questionnaires.response', ['id' => $id]) }}" method="post" enctype="application/json">
                <questionnaire-show is_connected="{{ $is_connected }}" _questionnaire="{{ $questionnaire }}"
                    questions="{{ $questions }}" id="{{ $id }}" />
            </form>
        </div>

    </div>
@endsection

@push('ui.script')
    <script src="{{ asset('js/client-questionnaire.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/form.js?ver=' . time()) }}"></script>

    <style scoped>
        /* Loader */
        .card.loading-active {
            max-height: 600px;
            overflow: hidden;
        }

        .loader-con {
            background: #fff;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 9;
            border-radius: 0.25rem;
        }


        .loader-con #loading {
            margin: 0 auto;
            display: inline-block;
            width: 70px;
            height: 70px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #002766;
            animation: spin 1s ease-in-out infinite;
            -webkit-animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                -webkit-transform: rotate(360deg);
            }
        }

        @-webkit-keyframes spin {
            to {
                -webkit-transform: rotate(360deg);
            }
        }
    </style>
@endpush
