@if (Session::has('Term'))
<div class="card card-success collapsed-card">
@else
<div class="card card-outline card-success">
@endif
  <div class="card-header">
    <h3 class="card-title">Set the <b>Term</b> for your session:</h3>

    <div class="card-tools">
      <!-- This will cause the card to maximize when clicked -->
      <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
      <!-- This will cause the card to collapse when clicked -->
      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="@if (Session::has('Term'))fas fa-plus @else fas fa-minus @endif"></i></button>
    </div>
    <!-- /.card-tools -->
  </div>
  <!-- /.card-header -->
 	<div class="card-body">
  		<form id="set-term" method="GET" action="{{ route('set-session-term') }}">
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
			<div class="card-footer">
				<div class="form-group">           
					<button type="submit" class="btn btn-success filter-submit-btn">Set Term</button>
					{{-- <a href="/admin" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a> --}}
				</div>
			</div>
		</form>
	</div>
  	<!-- /.card-body -->
</div>
<!-- /.card -->