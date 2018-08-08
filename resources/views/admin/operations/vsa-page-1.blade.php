@extends('admin.admin')

@section('content')
<h2><strong>Validate-Sort-Assign</strong></h2>
<h3>Step 1:</h3>
<form method="POST" action="{{ route('validate-page') }}">
	{{ csrf_field() }}
	<div class="form-group col-sm-12 add-margin">
		<select class="col-sm-8 form-control select2-filter" name="Term" autocomplete="off" required="required" style="width: 100%">
		    <option value="">--- Select Term ---</option>
		    @foreach ($terms as $value)
		        <option value="{{$value->Term_Code}}">{{$value->Term_Code}} {{$value->Comments}} - {{$value->Term_Name}}</option>
		    @endforeach
		</select>
	</div>

	<div class="form-group col-sm-12 add-margin">           
        <button type="submit" class="btn btn-success button-prevent-multi-submit">Validate Forms</button>
		<input type="hidden" name="_token" value="{{ Session::token() }}">
	</div>
</form>


@stop