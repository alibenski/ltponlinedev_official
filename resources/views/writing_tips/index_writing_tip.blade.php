@extends('shared_template')

@section('customcss')

@stop

@section('content')

<div class='col-md-12'>
    <h3>
        Writing Tip Entries
    </h3>
    <div class="form-group">
        <a href="{{ route('writing-tips.create') }}" class="btn btn-success"><i class="fa fa-pencil"></i> Create a new entry</a>
    </div>

    <table class="table table-bordered table-striped">
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
                <a href="{{ route('writing-tips.show', $record->id) }}" class="btn btn-link">View</a>
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