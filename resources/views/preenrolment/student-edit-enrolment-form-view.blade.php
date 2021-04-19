@extends('main')
@section('tabtitle', 'Edit Form')
@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
@stop
@section('content')

@stop
@section('scripts_code')

<script src="{{ asset('js/select2.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script> --}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.27.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
@stop