@extends('shared_template')

@section('customcss')

@stop

@section('content')

<div class='col-md-12'>
<h3>
    Writing Tip Entries
</h3>

<table class="table">
    <tr>
        <th>#</th>
        <th>Language</th>
        <th>Subject</th>
        <th>Text</th>
    </tr>
    
    @foreach ($records as $record)
    <tr>
        <td>
            {{$record->id}}
        </td>
        <td>
            {{$record->L}}
        </td>
        <td>
            {{$record->subject}}
        </td>
        <td>
            {!!$record->text!!}
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