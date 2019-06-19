@extends('shared_template')

@section('customcss')
  {{-- <link href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet"> --}}
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
  <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop

@section('content')

@include('admin.partials._termSessionMsg')

<div class="row">
    <div class="col-sm-12">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"> <strong>Manage Placement Exams View</strong> </h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
	    <div class="box-body">
		    <form>				
				<div class="form-group col-sm-12">
			      <label for="L" class="control-label"> Choose Language:</label>
			      <div class="col-sm-12">
			        @foreach ($languages as $id => $name)
			        <div class="col-sm-4">
			            <div class="input-group"> 
			              <span class="input-group-addon">       
			                <input type="radio" name="L" value="{{ $id }}" >                 
			              </span>
			                <label for="L" type="text" class="form-control label-{{ $id }}">{{ $name }}</label>
			            </div>
			        </div>
			        @endforeach 
			      </div>
			    </div>

		        <div class="form-group">           
		          {{-- <button type="button" class="btn btn-success filter-submit-btn">Submit</button> --}}
		        	<a href="{{route('manage-exam-view')}}" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
		        </div>
		    </form>
		</div>
	</div>
	</div>
</div>

<div class="insert-exam-table">
	
</div>

@stop

@section('java_script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>

<script>
  $(document).ready(function() {
    
    $("input[name='L']").on('click', function(){
      var L = $("input[name='L']:checked").val();

      $("label[for='L']").removeClass('bg-purple');
      $(".label-"+L).addClass('bg-purple');

      $.ajax({
        url: '{{ route('manage-exam-table') }}',
        type: 'GET',
        dataType: 'json',
        data: {L: L},
      })
      .done(function(data) {
        $(".insert-exam-table").html(data.options);

        $('table.manage-exam-table').DataTable({
          "deferRender": true,
          "paging":   false,
          "fixedHeader": true
          // "searching": false,
          // "order": [[ 2, "asc" ]]
        });

      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log(L);
      });
    });
  });
</script>


@stop