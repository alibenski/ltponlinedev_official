@extends('admin.no_sidebar_admin')

@section('content')

<div class="alert bg-black col-sm-12">
  <h4 class="text-center"><strong>Preview</strong></h4>
</div>
{{-- @include('admin.partials._termSessionMsg') --}}

<h3>Step 2:</h3>
<h4>Term: @if (is_null($term)) TempSort Table empty!  @else {{$term->Term}} @endif</h4>
<a href="{{ route('preview-vsa-page-1') }}" class="btn btn-danger" @if (is_null($term)) @else style="display: none;" @endif>Back to Step 1</a>
<form method="GET" action="{{ route('preview-course-3') }}">
	{{ csrf_field() }}
	<input type="hidden" name="term_id" value=@if (is_null($term)) ""  @else "{{$term->Term}}" @endif>
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
	<div class="form-group">
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
	</div>
</form>


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
          url: "{{ route('select-ajax-admin') }}", 
          method: 'POST',
          data: {L:L, term_id:term, _token:token},
          success: function(data, status) {
            $("select[name='course_id']").html('');
            $("select[name='course_id']").html(data.options);
          }
      });
  }); 
</script>
@stop