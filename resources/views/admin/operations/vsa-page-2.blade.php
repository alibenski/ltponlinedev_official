@extends('admin.admin')

@section('content')
<h2><strong>Validate-Sort-Assign</strong></h2>
<h3>Step 2:</h3>
<h4>Term: {{$term->Term}}</h4>
<form method="POST" action="{{ route('sort-page') }}">
	{{ csrf_field() }}
	<input type="hidden" name="term_id" value="{{$term->Term}}">
	<div class="form-group">
        <label class="col-md-3 control-label">Choose language:</label>
          <div class="col-md-8">
              @foreach ($languages as $id => $name)
            <div class="input-group col-md-9">
                      <input id="{{ $name }}" name="L" class="with-font lang_select_no" type="radio" value="{{ $id }}">
                      <label for="{{ $name }}" class="label-lang form-control-static">{{ $name }}</label>
            </div>
              @endforeach
          </div>
    </div>
	<div class="form-group">
        <label for="course_id" class="col-md-3 control-label">Choose course: </label>
        <div class="col-md-8">
          <div class="dropdown">
            <select class="col-md-8 form-control course_select_no wx" style="width: 100%;" name="course_id" autocomplete="off">
                <option value="">--- Select Course ---</option>
            </select>
          </div>
        </div>
    </div>
	<div class="form-group col-sm-12 add-margin">           
        <button type="submit" class="btn btn-success button-prevent-multi-submit">Sort and Assign</button>
		<input type="hidden" name="_token" value="{{ Session::token() }}">
	</div>
</form>


@stop

@section('java_script')
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
            $("select[name='course_id']").html('');
            $("select[name='course_id']").html(data.options);
          }
      });
  }); 
</script>
@stop