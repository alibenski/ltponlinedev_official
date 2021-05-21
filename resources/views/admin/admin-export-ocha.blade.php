@extends('admin.admin')

@section('customcss')
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
    	table { table-layout:fixed; }
		th, td { word-wrap:break-word; overflow:hidden; text-overflow: ellipsis; }
    </style>
@stop

@section('content')
    <h2 class="text-center"> OCHA Export </h2>

    @include('admin.partials._termSessionMsg')

    <div class="billing-section">
        <div class="preloader2"><h3 class="text-center"><strong>Please wait... Fetching data from the database...</strong></h3></div>
        <input type="hidden" name="_token" value="{{ Session::token() }}">
        <table id="sampol" class="table table-striped no-wrap" width="100%">
            <thead>
                <tr>
                    <th>Operation</th>
                    <th>Term</th>
                    <th>Language</th>
                    <th>Description</th>
                    <th>Price USD</th>
                    <th>Duration</th>
                    <th>Organization</th>
                    <th>Name</th>
                    <th>RESULT</th>
                    <th>Cancel Date</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Operation</th>
                    <th>Term</th>
                    <th>Language</th>
                    <th>Description</th>
                    <th>Price USD</th>
                    <th>Duration</th>
                    <th>Organization</th>
                    <th>Name</th>
                    <th>RESULT</th>
                    <th>Cancel Date</th>
                </tr>
            </tfoot>
        </table>
    </div>	
@stop

@section('java_script')
    <script>

    </script>
@stop