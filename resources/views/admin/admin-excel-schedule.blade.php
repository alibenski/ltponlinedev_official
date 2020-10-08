@extends('admin.admin')
@section('tabtitle')
Schedule
@stop
@section('customcss')
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css"/>
	<link rel="stylesheet" type="text/css" href="https://nightly.datatables.net/buttons/css/buttons.dataTables.min.css" />
@stop
@section('content')
<div class="row">
	<div class="col-md-12">
		@include('admin.partials._termSessionMsg')
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<span><h2><i class="fa fa-calendar-o"></i> Generate Schedule Table </h2></span>
		</div>
		<div class="col-md-12">
			<hr>
		</div>
	</div>
</div>
@if (Session::has('Term'))
<div class="row">
	<div class="col-md-12">
		<div class="preloader2"><h3 class="text-center"><strong>Please wait... Fetching data from the database...</strong></h3></div>
		<div class="filtered-table table-responsive">
			<table id="sampol" class="table table-bordered table-striped">
				<thead>
					<th>Level or Name of course <br>Niveau ou nom du cours</th>
					<th>Time <br>Horaire</th>
					<th>Days <br>Jours</th>
					<th>Format</th>
					<th>Price <br>Prix</th>
				</thead>

				<tbody>
					@foreach($course_schedule as $class)
						<tr>
                            <td>
                                {{ $class->course->Description }} <br>{{ $class->course->FDescription }}
                            </td>
                            <td>
                                @if(empty( $class->schedule_id ))
                                null
                                @else 
                                {{ $class->scheduler->time_combination }}
                                @endif
                            </td>
							<td>
								@if(empty( $class->schedule_id ))
								null
								@else 
                                {{ $class->scheduler->begin_day }} <br>{{ $class->scheduler->begin_day_fr }} 
								@endif
							</td>
							<td>
								@if ($class->courseformat)
								{{ $class->courseformat->format_name_en }}
								@endif
							</td>
							{{-- <td>
								@if ($class->courseduration)
								{{ $class->courseduration->duration_name_en}}
								@endif
							</td> --}}
							<td>
								@if ($class->prices)
									CHF {{ $class->prices->price }}.-	
								@endif
							</td>
						</tr>
					@endforeach

				</tbody>
			</table>
		</div>	
	</div>
</div>
@endif

@stop

@section('java_script')
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://nightly.datatables.net/buttons/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.2.0/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://nightly.datatables.net/buttons/js/buttons.html5.min.js"></script>
<script>
$(document).ready(function() {
	function remove_tags(html)
	 {
	   html = html.replace(/<br>/g,"$br$"); 
	   html = html.replace(/(?:\r\n|\r|\n)/g, '$n$');
	   var tmp = document.createElement("DIV");
	   tmp.innerHTML = html;
	   html = tmp.textContent||tmp.innerText;

	   html = html.replace(/\$br\$/g,"<br>");  
	   html = html.replace(/\$n\$/g,"<br>");
	
	   return html;
	 }

    var buttonCommon = {
        exportOptions: {
			stripHtml: false,
            format: {
				header: function ( data, row, column, node ) {
                    return data.replace(/<br\s*\/?>/ig, "\n");
                },
                body: function ( data, row, column, node ) {
                    data = data.replace(/&amp;/g, '&').replace(/<br\s*\/?>/ig, "\n");

					// if (column === 0) {
                    // 	data = data.replace( /"/g, "'" );
					// 	data = remove_tags(data);
					// 	//split at each new line
					// 	splitData = data.split('<br>');
						
					// 	//remove empty string
					// 	splitData = splitData.filter(function(v){return v!==''});
						
					// 	data = '';
					// 	for (i=0; i < splitData.length; i++) {
					// 				//add escaped double quotes around each line
					// 				data += '\"' + splitData[i] + '\"';
					// 				//if its not the last line add CHAR(13)
					// 				if (i + 1 < splitData.length) {
					// 					data += '& CHAR(10) &';
					// 				}
					// 	}
					// 	//Add concat function
               		// 	data = '=CONCAT(' + data + ')';
					// }
					return data;
                },
            }
        }
    };
 
    $('#sampol').DataTable({
		"fixedHeader": true,
		"deferRender": true,
		"dom": 'B<"clear">lfrtip',
		"buttons": [
				'copyHtml5', 'csvHtml5', 
				$.extend( true, {}, buttonCommon, {
					extend: 'pdfHtml5'
				} ),
				$.extend( true, {}, buttonCommon, {
					extend: 'excelHtml5'
				} ),
			],
		"oLanguage": {
			"sSearch": "Search Filter:"
			},
		
	});
	$(".preloader2").fadeOut(600);
} );
</script>
@stop