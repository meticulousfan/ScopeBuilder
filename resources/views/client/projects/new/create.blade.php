@extends('client.layout.main')

@section('page-title')
    Questionnaires
@endsection

@section('page-caption')
    Questionnaires
@endsection
@push('ui.style')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
@endpush
@section('content')
    <div id="questionnairesclient">
        <questionnaire-questions id="{{ $id }}" />
    </div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="{{ asset('js/form-builder.min.js') }}"></script>



@push('ui.script')
    {{-- <script src="{{ asset('js/form.js?ver=' . time()) }}"></script> --}}
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('js/client-questionnaire.js') }}"></script>
    </html>
@endpush
<script>
    jQuery(function($) {
        var fbEditor = document.getElementById('fb-editor');
        var options = {
            showActionButtons: false // defaults: `true`
        };
        window.formBuilder = $(fbEditor).formBuilder(options);
        //window.formBuilder = $(fbEditor).formBuilder();
    });
</script>
