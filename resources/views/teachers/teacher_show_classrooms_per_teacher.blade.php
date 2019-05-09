@extends('admin.admin')

@section('customcss')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<h3><i class="fa fa-pied-piper-alt"></i> <span>Shows Teachers and their Courses</span></h3>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="box box-success" data-widget="box-widget">
			  <div class="box-header">
			    <h3 class="box-title">Set the <b>Term</b> for your session:</h3>
			    <div class="box-tools">
			      <!-- This will cause the box to be removed when clicked -->
			      {{-- <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button> --}}
			      <!-- This will cause the box to collapse when clicked -->
			      <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			    </div>
			  </div>
				<form id="set-term" method="GET" action="">
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
		</div>
	</div>
	<div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-yellow">
              <div class="widget-user-image">
                <img class="img-circle" src="../dist/img/user7-128x128.jpg" alt="User Avatar">
              </div>
              <!-- /.widget-user-image -->
              <h3 class="widget-user-username">Nadia Carmichael</h3>
              <h5 class="widget-user-desc">Lead Developer</h5>
            </div>
            <div class="box-footer no-padding">
              <ul class="nav nav-stacked">
                <li><a href="#">Projects <span class="pull-right badge bg-blue">31</span></a></li>
                <li><a href="#">Tasks <span class="pull-right badge bg-aqua">5</span></a></li>
                <li><a href="#">Completed Projects <span class="pull-right badge bg-green">12</span></a></li>
                <li><a href="#">Followers <span class="pull-right badge bg-red">842</span></a></li>
              </ul>
            </div>
          </div>
	<table class="table table-condensed table-hover">
		<thead>
			<tr>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td></td>
			</tr>
		</tbody>
	</table>
</div>
@endsection

@section('java_script')
<script src="{{ asset('js/select2.full.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "select here",
    });
});
</script>

@stop