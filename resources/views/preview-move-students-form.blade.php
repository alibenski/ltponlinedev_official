<div class="col-sm-12">
@include('admin.partials._termSessionMsg')
	@foreach ($student_to_move as $student)
		<p>	{{ $student->users->name}} </p>
	@endforeach

	<form action="{{ route('ajax-move-students') }}" method="POST" class="form-horizontal" role="form">{{ csrf_field() }}
		<div class="form-group col-sm-12">
	      <label for="L" class="control-label"> Language:</label>
	      <div class="col-sm-12">
	        @foreach ($languages as $id => $name)
	        <div class="col-sm-4">
	            <div class="input-group"> 
	              <span class="input-group-addon">       
	                <input type="radio" name="L" value="{{ $id }}" >                 
	              </span>
	                <label type="text" class="form-control">{{ $name }}</label>
	            </div>
	        </div>
	        @endforeach 
	      </div>
	    </div>

		<div class="form-group col-sm-12">
	        <label for="Te_Code" class="control-label"> Course: </label>
	        <div class="form-group col-sm-12">
	          <div class="dropdown">
	            <select class="col-md-10 form-control select2-basic-single" style="width: 100%;" name="Te_Code" autocomplete="off">
	                <option value="">--- Select ---</option>
	            </select>
	          </div>
	        </div>
	    </div>

		<button type="submit" class="btn btn-success"><span class='glyphicon glyphicon-check'></span> 
			Save
		</button>
		<input type="hidden" name="_token" value="{{ Session::token() }}">
		<input type="hidden" name="term_id" value="{{ Session::get('Term') }}">
		{{ method_field('PUT') }}

	</form>
</div>
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
            $("select[name='Te_Code']").html('');
            $("select[name='Te_Code']").html(data.options);
          }
      });
  }); 

  $("select[name='Te_Code']").on('change',function(){
      var course_id = $(this).val();
      var term = $("input[name='term_id']").val();
      var token = $("input[name='_token']").val();

      console.log(course_id);
      // $.ajax({
      //     url: "{{ route('select-ajax2') }}", 
      //     method: 'POST',
      //     data: {course_id:course_id, term_id:term, _token:token},
      //     success: function(data) {
      //       $("select[name='schedule_id[]']").html('');
      //       $("select[name='schedule_id[]']").html(data.options);
      //     }
      // });
  }); 
</script>



