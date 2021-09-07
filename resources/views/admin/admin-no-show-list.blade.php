@extends('admin.no_sidebar_admin')
@section('tabtitle')
    NO-SHOW List
@stop
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
<div class="row">
    <div class="col-md-3">
        @include('admin.partials._termSessionMsg')
    </div>
    <div class="col-md-3">
        <div class="alert alert-info text-center">
            <i class="fa fa-info-circle"></i> You have <span class="badge"> {{ $no_show_records->count() }} </span> Students marked as No-Show
        </div>
</div>
<div class="r    </div> ow">
    <div class="table-responsive col-sm-12 filtered-table">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Email</th>
                    <th>Contact No.</th>
                    <th>Marked No-Show By</th>
                    <th>Class</th>
                </tr>
            </thead>
            <tbody>
                @foreach($no_show_records as $record)
                <tr>
                    <td>
                        {{ $record->users->nameLast }}
                    </td>
                    <td>
                        {{ $record->users->nameFirst }}
                    </td>
                    <td>
                        {{ $record->users->email }}
                    </td>
                    <td>
                        {{ $record->users->sddextr->PHONE }}
                    </td>
                    <td>
                        @if ($record->noShowBy)
                            {{ $record->noShowBy->name }}
                        @endif
                    </td>
                    <td>
                        @if(empty($record->classrooms->course->Description)) 
                            Error! 
                        @else 
                            <a href="{{ route('view-classrooms-per-section', [$record->CodeClass]) }}" target="_blank">
                                {{$record->classrooms->course->Description }} - {{$record->classrooms->scheduler->name}} 
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop