@extends('admin.layout.main')
@section('page-title')
    Questionnaires
@endsection

@section('page-caption')
    Questionnaires
@endsection

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha256-DF7Zhf293AJxJNTmh5zhoYYIMs2oXitRfBjY+9L//AY=" crossorigin="anonymous">

    <div id="displayquestionnairesadmin">
        <display-questionnaires />
    </div>
@endsection

@push('ui.script')
    <script src="{{ asset('js/admin-questionnaire-display.js') }}"></script>

    </html>
@endpush
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
