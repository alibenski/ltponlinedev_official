@if (Session::has('Term'))
<div class="box box-success collapsed-box" data-widget="box-widget">
@else
<div class="box box-success" data-widget="box-widget">
@endif
  <div class="box-header">
    <h3 class="box-title">Set the <b>Term</b> for your session:</h3>
    <div class="box-tools">
      <!-- This will cause the box to be removed when clicked -->
      {{-- <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button> --}}
      <!-- This will cause the box to collapse when clicked -->
      <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="@if (Session::has('Term'))fa fa-plus @else fa fa-minus @endif"></i></button>
    </div>
  </div>
	<form id="set-term" method="GET" action="{{ route('set-session-term') }}">
	  	<div class="box-body">
			<div class="form-group">
			<label for="Term" class="col-md-12 control-label"></label>
			<div class="form-group col-sm-12">
			    <div class="dropdown">
			      <select id="Term" name="Term" class="col-md-8 form-control select2-basic-single" style="width: 100%;" required="required">
			        @foreach($terms as $value)
			            <option></option>
			            <option value="{{$value->Term_Code}}">{{$value->Term_Code}} - {{$value->Comments}} - {{$value->Term_Name}}</option>
			        @endforeach
			      </select>
			    </div>
			  </div>
			</div>
		</div>
		  <!-- /.box-body -->
		<div class="box-footer">
			<div class="form-group">           
			    <button type="submit" class="btn btn-success filter-submit-btn">Set Term</button>
			    {{-- <a href="/admin" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a> --}}
			</div>
		</div>
	</form>
</div>