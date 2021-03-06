@extends('admin.admin')

@section('content')
<div class="container">
	<div class="row">
		@include('admin.partials._termSessionMsg')
		<div class="col-md-12">
			<h1><i class="fa fa-book"></i> <span>Course Catalogue</span></h1>
		</div>
		
		<div class="col-md-2">
			<a href="{{ route('courses.create') }}" class="btn btn-block btn-primary btn-h1-spacing">Create Course</a>

		</div>
		<div class="col-md-12">
			<hr>
		</div>
	</div>

	<div class="form-group">
		<input type="hidden" name="term_id" value="{{Session::get('Term')}}">

        <div class="col-md-8">
          <div class="dropdown">
            <select class="col-md-8 form-control course_select_no wx" style="width: 100%;" name="course_id" autocomplete="off">
                <option value="">--- Select Course ---</option>
            </select>
          </div>
        </div>
    </div>

	<form method="GET" action="{{ route('courses.index',['L' => \Request::input('L')]) }}">
		<div class="form-group input-group col-sm-12">
			<h4><strong>Filter by Language:</strong></h4>
			<div class="form-group">
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
		</div> {{-- end filter div --}}
		
		<div class="form-group">           
		        <button type="submit" class="btn btn-success" value="UNOG">Submit</button>
	        	<a href="/admin/courses/" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
	    </div>		
	</form>

	<div class="row">
		<div class="col-md-12">
			<div class="filtered-table table-responsive">
				<table class="table table-bordered table-striped">
					<thead>
						{{-- <th>#</th> --}}
						<th>Operation</th>
						<th>Code</th>
						<th>Course Name</th>
						<th>Course Name (Fr)</th>
						<th>Language</th>
						<th>Created By</th>
					</thead>

					<tbody>
						@foreach($courses as $course)
							<tr>
								{{-- <th>{{ $course->id }}</th> --}}
								<td><a href="{{ route('courses.edit', $course->id)}}" class="btn btn-warning btn-sm">Edit</a></td>
								<td>{{ $course->Te_Code_New }}</td>
								<td>{{ $course->Description }}</td>
								<td>{{ $course->FDescription }}</td>
								<td>{{ $course->language->name }}</td>
								<td>@if(empty($course->users)) @else {{ $course->users->name }} @endif</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			{{ $courses->links() }}		
		</div>
	</div>
</div>
@endsection

@section('java_script')
<script type="text/javascript">
  $("input[name='L']").click(function(){
      var L = $(this).val();
      var term = $("input[name='term_id']").val();
      var token = $("input[name='_token']").val();
      console.log(term)
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