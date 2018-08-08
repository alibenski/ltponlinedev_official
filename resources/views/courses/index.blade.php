@extends('admin.admin')

@section('content')
<div class="container">
	<div class="row">
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
			<table class="table">
				<thead>
					{{-- <th>#</th> --}}
					<th>Code</th>
					<th>Course Name</th>
					<th>Language</th>
					<th>Operation</th>
				</thead>

				<tbody>
					@foreach($courses as $course)
						
						<tr>
							{{-- <th>{{ $course->id }}</th> --}}
							<td>{{ $course->Te_Code_New }}</td>
							<td>{{ $course->Description }}</td>
							<td>{{ $course->language->name }}</td>
							<td><a href="{{ route('courses.edit', $course->id)}}" class="btn btn-default btn-sm">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>
			{{ $courses->links() }}		
		</div>
	</div>
</div>
@endsection