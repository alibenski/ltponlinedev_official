@extends('admin.no_sidebar_admin')

@section('customcss')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')

<div class="alert bg-black col-sm-12">
  <h4 class="text-center"><strong><i class="icon fa fa-gears"></i>Manage Classes [After Batch Run]</strong></h4>
</div>

<div class="preloader2 hidden"><h3 class="text-center"><strong>Please wait... Fetching data from the database...</strong></h3></div>

@include('admin.partials._termSessionMsg')

@if (Session::has('Term'))
  <a href="{{ route('admin-student-email-view') }}" class="btn btn-primary admin-student-email-view"><i class="fa fa-at"></i> View All Current Students</a>
@endif

<form method="GET" action="{{ route('preview-course-3') }}">
	{{ csrf_field() }}

	<input type="hidden" name="term_id" value=@if (is_null($term)) ""  @else "{{$term}}" @endif>

  <div class="form-group col-sm-12">
    <label for="L" class="control-label"> Language:</label>
    <div class="col-sm-12">
      @foreach ($languages as $id => $name)
      <div class="col-sm-4">
          <div class="input-group"> 
            <span class="input-group-addon">       
              <input id="{{ $name }}" type="radio" name="L" value="{{ $id }}">                 
            </span>
              <label for="{{ $name }}" type="text" class="form-control">{{ $name }}</label>
          </div>
      </div>
      @endforeach 
    </div>
  </div>
        
{{-- 	<div class="form-group">
        <label for="course_id" class="col-md-3 control-label">Choose course: </label>
        <div class="col-md-8">
          <div class="dropdown">
            <select class="col-md-8 form-control course_select_no wx" style="width: 100%;" name="course_id" autocomplete="off" required="">
                <option value="">--- Select Course ---</option>
            </select>
          </div>
        </div>
    </div>
	<div class="form-group col-sm-12 add-margin">           
        <button type="submit" class="btn btn-success button-prevent-multi-submit" @if (is_null($term)) disabled="" @endif>Preview</button>
		<input type="hidden" name="_token" value="{{ Session::token() }}">
	</div> --}}
</form>

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

  // $("input[name='L']").click(function(){
  //     var L = $(this).val();
  //     var term = $("input[name='term_id']").val();
  //     var token = $("input[name='_token']").val();

  //     $.ajax({
  //         url: "{{ route('select-ajax-admin') }}", 
  //         method: 'POST',
  //         data: {L:L, term_id:term, _token:token},
  //         success: function(data, status) {
  //           $("select[name='course_id']").html('');
  //           $("select[name='course_id']").html(data.options);
  //         }
  //     });
  // }); 

  $("input[name='L']").click(function(){
      var L = $(this).val();
      var term = $("input[name='term_id']").val();
      var token = $("input[name='_token']").val();

      if (term) {
        
        $.ajax({
            url: "{{ route('ajax-class-boxes') }}", 
            method: 'POST',
            data: {L:L, term_id:term, _token:token},
            success: function(data, status) {
              $("div.preview-boxes-here").html('');
              $("div.preview-boxes-here").html(data.options);
            }
        });
      }
  }); 

  $('a.admin-student-email-view').click(function() {
    $(".preloader2").removeClass('hidden');
    $(".preloader2").fadeIn('fast');
  });
</script>
@stop