@extends('client.layout.main')

@section('page-title')
    Questionnaires
@endsection

@section('page-caption')
    Questionnaires
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <div id="questionnairesclient">
        <questionnaires-list/>
    </div>
@endsection

@push('ui.script')
    <script src="{{ asset('js/client-questionnaire.js') }}"></script>

    </html>

    <style scoped>
        /* Loader */
        #loading,
        #extendLoading {
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
    </style>
@endpush
