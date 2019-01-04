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
					<th>U</th>
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
							<td>@if($teacher->users) <i class="fa fa-check text-success"></i>@endif</td>
							<td class="input-IndexNo">{{ $teacher->IndexNo }}</td>
							<td class="input-Tch_Lastname">{{ $teacher->Tch_Lastname }}</td>
							<td class="input-Tch_Firstname">{{ $teacher->Tch_Firstname }}</td>
							<td class="input-email">{{ $teacher->email }}</td>
							<td class="input-Tch_L">{{ $teacher->Tch_L }}</td>
							<td>
								<input value="" type="checkbox" name="In_Out" @if($teacher->In_Out == 1) checked="checked" @else @endif>
							</td>
							<td>
								<button type="button" class="btn btn-warning btn-sm quick-edit">Quick edit</button>
								<button type="button" class="btn btn-success btn-sm quick-save hidden" disabled="">Quick Save</button>
								<a href="#"  class="btn btn-default btn-sm" disabled>Edit</a>
								<input type="hidden" name="_token" value="{{ Session::token() }}">
								</form>
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
$(".quick-edit").click(function(){
	$(this).attr('disabled', 'true');
	var textIndexNo = $(this).closest("tr").find(".input-IndexNo").text();
	var textTch_Lastname = $(this).closest("tr").find(".input-Tch_Lastname").text();
	var textTch_Firstname = $(this).closest("tr").find(".input-Tch_Firstname").text();
	var textemail = $(this).closest("tr").find(".input-email").text();
	console.log(textIndexNo)
	$(this).closest("tr").find(".input-IndexNo").html('<input type="text" name="IndexNo" value="" placeholder="'+textIndexNo+'">');
	$(this).closest("tr").find(".input-Tch_Lastname").html('<input type="text" name="Tch_Lastname" value="" placeholder="'+textTch_Lastname+'">');
	$(this).closest("tr").find(".input-Tch_Firstname").html('<input type="text" name="Tch_Firstname" value="" placeholder="'+textTch_Firstname+'">');
	$(this).closest("tr").find(".input-email").html('<input type="email" name="email" value="" placeholder="'+textemail+'">');
	$(this).closest("tr").find(".input-Tch_L").html('<select class="form-control" style="width: 100%;" name="Tch_L" autocomplete="off"><option value="">--- Select ---</option><option value="A">A</option><option value="C">C</option><option value="E">E</option><option value="F">F</option><option value="R">R</option><option value="S">S</option></select>');
	$(this).closest("tr").find("button.quick-save").removeClass('hidden');
	$(this).closest("tr").find("button.quick-save").removeAttr('disabled');

}); 
</script>
<script>
$("input[name='In_Out']").change(function() {
	var In_Out_chk = $(this).prop('checked');
	console.log(In_Out_chk)		
	if (In_Out_chk) {
		var In_Out = $(this).val('1');
	} else{
		var In_Out = $(this).val('0');
	}
});
	
</script>
<script>
$(".quick-save").click(function(){
  $(this).attr('disabled', 'true');
  var Tch_ID = $(this).closest("tr").attr('id');
  var Tch_L = $(this).closest("tr").find("select[name='Tch_L']").val();
  var IndexNo = $(this).closest("tr").find("input[name='IndexNo']").val();
  var Tch_Lastname = $(this).closest("tr").find("input[name='Tch_Lastname']").val();
  var Tch_Firstname = $(this).closest("tr").find("input[name='Tch_Firstname']").val();
  var email = $(this).closest("tr").find("input[name='email']").val();
  var In_Out = $(this).closest("tr").find("input[name='In_Out']").val();
  var token = $("input[name='_token']").val();
  console.log(Tch_ID)
  $.ajax({
      url: "{{ route('ajax-teacher-update') }}", 
      method: 'PUT',
      data: { Tch_ID:Tch_ID, _token:token, Tch_L:Tch_L, IndexNo:IndexNo, In_Out:In_Out, Tch_Lastname:Tch_Lastname, Tch_Firstname:Tch_Firstname, email:email},
      success: function(data, status) {
        console.log(data)
        setTimeout(function(){
	       	location.reload();
	   		},500);
        // $(".preview-here").html(data);
        // $(".preview-here").html(data.options);
      }
  });
}); 	
</script>



@stop