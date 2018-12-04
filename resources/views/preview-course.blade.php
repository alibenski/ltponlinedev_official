@extends('admin.no_sidebar_admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="alert bg-black col-sm-12">
	<h4 class="text-center"><strong>Preview</strong></h4>
</div>

{{-- @include('admin.partials._termSessionMsg') --}}

<form method="POST" action="{{ route('preview-validate-page') }}">
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

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "Select Filter",
    });
});
</script>
<script type="text/javascript">
  $("input[name='L']").click(function(){
      var L = $(this).val();
      var term = $("input[name='term_id']").val();
      var token = $("input[name='_token']").val();

      $.ajax({
          url: "{{ route('select-ajax') }}", 
          method: 'POST',
          data: {L:L, term_id:term, _token:token},
          success: function(data, status) {
            $("select[name='Te_Code']").html('');
            $("select[name='Te_Code']").html(data.options);
          }
      });
  }); 
</script>
@stop