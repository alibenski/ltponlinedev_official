@extends('admin.admin')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<h1><i class="fa fa-pied-piper-alt"></i> <span>Teachers</span></h1>
		</div>
		
		<div class="col-md-2 pull-right">
			<a href="#" class="btn btn-block btn-primary btn-h1-spacing">Create Teacher</a>
		</div>
	</div>

	<div class="row">
		<form method="GET" action="{{ route('teachers.index',['L' => \Request::input('L')]) }}">
			<div class="form-group col-sm-12">
		      <label for="Tch_L" class="control-label"> Language:</label>
		      <div class="col-sm-12">
		        @foreach ($languages as $id => $name)
		        <div class="col-sm-4">
		            <div class="input-group"> 
		              <span class="input-group-addon">       
		                <input type="radio" name="Tch_L" value="{{ $id }}" >                 
		              </span>
		                <label type="text" class="form-control">{{ $name }}</label>
		            </div>
		        </div>
		        @endforeach 
		      </div>
		    </div>

		    <div class="form-group col-sm-12">           
	                <button type="submit" class="btn btn-success filter-submit-btn">Submit</button>
	                <a href="{{ route('teachers.index') }}" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
	        </div>
		</form>
	</div>

	<div class="row">
		<div class="filtered-table table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<th>Index No</th>
					<th>Last Name</th>
					<th>First Name</th>
					<th>Email</th>
					<th>Language</th>
					<th>Active</th>
					<th>Operation</th>
				</thead>

				<tbody>
					@foreach($teachers as $teacher)
						
						<tr id="{{ $teacher->Tch_ID}}" @if($teacher->In_Out == 0) style="background-color: #eed5d2;" @endif>
							<td>@if(is_null($teacher->IndexNo))
								<input type="text" name="IndexNo" value=""> 
								@else {{ $teacher->IndexNo }}
								@endif
							</td>
							<td>{{ $teacher->Tch_Lastname }}</td>
							<td>{{ $teacher->Tch_Firstname }}</td>
							<td>{{ $teacher->email }}</td>
							<td>@if(is_null($teacher->Tch_L))
								<select class="form-control" style="width: 100%;" name="Tch_L" autocomplete="off">
					                <option value="">--- Select ---</option>
					                <option value="A">A</option>
					                <option value="C">C</option>
					                <option value="E">E</option>
					                <option value="F">F</option>
					                <option value="R">R</option>
					                <option value="S">S</option>
					            </select>
								@else {{ $teacher->Tch_L }}
								@endif
							</td>
							<td>
								<input id="chk-{{ $teacher->Tch_ID}}" value="" type="checkbox" name="In_Out" @if($teacher->In_Out == 1) checked="checked" @else @endif>
							</td>
							<td>
								<button id="{{ $teacher->Tch_ID}}" type="button" class="btn btn-success btn-sm quick-save">Quick Save</button>
								<input type="hidden" name="_token" value="{{ Session::token() }}">
								<a href="#" class="btn btn-warning btn-sm">Edit</a>
							</td>
						</tr>
					@endforeach

				</tbody>
			</table>	
		</div>
	</div>
</div>
@endsection

@section('java_script')
<script>
$("input[name='In_Out']").change(function() {
		var In_Out_chk = $(this).prop('checked');
		if (In_Out_chk) {
			var In_Out = $(this).val('1');
		} else{
			var In_Out = $(this).val('0');
		}
	console.log(In_Out)		
});
	
</script>
<script>
$(".quick-save").click(function(){
  
  var Tch_ID = $(this).attr('id');

  var token = $("input[name='_token']").val();

  $("tr[id="+Tch_ID+"]").find("td select[name='Tch_L'],td input[name='IndexNo'],td input[name='In_Out']").each(function() {
  			  Tch_L = $("select[name='Tch_L']").val();
			  var IndexNo = $("input[name='IndexNo']").val();
			   In_Out = $("input[name='In_Out']").val();
            	console.log(In_Out)
        });


  // $.ajax({
  //     url: "", 
  //     method: 'PUT',
  //     data: { Tch_ID:Tch_ID, _token:token, Tch_L:Tch_L, IndexNo:IndexNo, In_Out:In_Out},
  //     success: function(data, status) {
  //       console.log(data)
  //   //     setTimeout(function(){
	 //   //     location.reload();
	 //   // },500);
  //       // $(".preview-here").html(data);
  //       // $(".preview-here").html(data.options);
  //     }
  // });
}); 	
</script>



@stop