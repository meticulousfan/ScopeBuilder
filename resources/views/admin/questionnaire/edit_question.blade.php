@extends('admin.layout.main')
@section('page-title')
    Questionnaires
@endsection

@section('page-caption')
    Questionnaires
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <div id="displayquestionnairesadmin">
        <edit-question count={{ $countQuestion }} question="{{ $question }}" id="{{ $id }}" />
    </div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="{{ asset('js/form-builder.min.js') }}"></script>



@push('ui.script')
    <script src="{{ asset('js/admin-questionnaire-display.js') }}"></script>
    <script src="{{ asset('js/form-builder.min.js') }}"></script>

    </html>
@endpush