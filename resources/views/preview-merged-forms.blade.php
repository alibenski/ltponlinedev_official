@extends('admin.no_sidebar_admin')

@section('customcss')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')

@include('admin.partials._termSessionMsg')

<div class="row">
    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border bg-aqua">
            	<h4>Live Preview (Merged View)</h4>
            </div>
    <div class="box-body">
		<form method="GET" action="">
			{{ csrf_field() }}

			<input type="hidden" name="term_id" value=@if (is_null($term)) ""  @else "{{$term}}" @endif>
			
				
			<div class="form-group">
		        <label class="col-md-3 control-label">Choose language:</label>
		          <div class="col-md-8">
		              @foreach ($languages as $id => $name)
		            <div class="input-group col-md-9">
		                      <input id="{{ $name }}" name="L" class="with-font lang_select_no" type="radio" value="{{ $id }}" required="">
		                      <label for="{{ $name }}" class="label-lang form-control-static">{{ $name }}</label>
		            </div>
		              @endforeach
		          </div>
		    </div>

			<div class="form-group col-sm-12 add-margin">           
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</div>
		</form>
	</div>
			@if (!Session::has('Term'))
				<div class="overlay"></div>
			@endif
        </div>
    </div>
</div>

<div class="row">
	<div class="col-md-12">
	    <div class="preview-boxes-here"></div>
	</div>	
</div>

@stop

@section('java_script')
<script type="text/javascript">
  $(document).ready(function(){
    $.ajaxSetup({ cache: false }); // or iPhones don't get fresh data
    $("input[name='L']").prop('checked', false);
  });

  $("input[name='L']").click(function(){
      var L = $(this).val();
      var term = $("input[name='term_id']").val();
      var token = $("input[name='_token']").val();

      $.ajax({
          url: "{{ route('ajax-preview-course-boxes') }}", 
          method: 'POST',
          data: {L:L, term_id:term, _token:token},
          success: function(data, status) {
            $("div.preview-boxes-here").html('');
            $("div.preview-boxes-here").html(data.options);
          }
      });
  }); 
</script>
@stop