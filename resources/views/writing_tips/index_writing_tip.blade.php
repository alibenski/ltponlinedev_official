@extends('writing_tips.template_writing_tip')

@section('customcss')
<link href="https://fonts.googleapis.com/css?family=Lobster&display=swap" rel="stylesheet">
@stop

@section('content')

<div class='col-md-12'>
    <h3 style="font-family: 'Lobster', cursive; text-shadow: 4px 4px 4px #aaa; font-size: 48px;">
        Writing Tips Entries
    </h3>
    <div class="form-group">
        <a href="{{ route('writing-tips.create') }}" class="btn btn-default"><i class="fa fa-pencil"></i> Create a new entry</a>
    </div>

    <table class="table table-bordered table-striped" style="background-color: white;">
        <tr>
            <th>#</th>
            <th>Language</th>
            <th>Subject</th>
            <th>Operation</th>
        </tr>
        
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
<script>

</script>
@stop