@extends('writing_tips.template_writing_tip')

@section('customcss')
<link href="https://fonts.googleapis.com/css?family=Lobster&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
@stop

@section('content')

<div class='col-md-12'>
    <h3 style="font-family: 'Lobster', cursive; text-shadow: 4px 4px 4px #aaa; font-size: 48px;">
        Writing Tips Entries
    </h3>
    <div class="form-group">
        <a href="{{ route('writing-tips.create') }}" class="btn btn-default"><i class="fa fa-pencil"></i> Create a new entry</a>
    </div>

    <table id="sampol" class="table table-bordered table-striped" style="background-color: white;">
        <thead>
        <tr>
            <th>#</th>
            <th>Language</th>
            <th>Subject</th>
            <th>Operation</th>
        </tr>
        </thead>
        @foreach ($records as $record)
        <tr>
            <td>
                {{$record->id}}
            </td>
            <td>
                {{$record->languages->name}}
            </td>
            <td>
                {{$record->subject}}
            </td>
            <td>
                <a href="{{ route('writing-tips.show', $record->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> View</a>
            </td>
        </tr>
        @endforeach

    </table>
</div>

@stop

@section('java_script')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
<script>
$('#sampol').DataTable({
    "fixedHeader": true,
    "deferRender": true,
    "dom": 'B<"clear">lfrtip',
    "buttons": [
            'copy', 'csv', 'excel', 'pdf'
        ],
});
</script>
@stop