<div class="total"></div>
<div class="pending-placement"></div>
@foreach ($select_courses->chunk(4) as $element)
<div class="row">
  @foreach($element as $data)
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="row">
      	
      <div class="small-box">
        <div class="inner">
        <h3 class="count-students-{{$data->Te_Code_New}}">--</h3>
        <h4>{{$data->course->Description}}</h4>
        {{-- <p>{{ $data->scheduler->name }}</p> --}}
                
		{{-- <input type="hidden" name="cs_unique" value="{{$data->cs_unique}}"> --}}
		<input type="hidden" name="Te_Code_New" value="{{$data->Te_Code_New}}">
		<input type="hidden" name="box-L" value="{{$data->L}}">
        <input type="hidden" name="_token" value="{{ Session::token() }}">             
          
        </div>
        <div class="icon">
          <i class="ion ion-person-stalker"></i>
        </div>
        
        <a href="{{ route('teacher-enrolment-preview',['Te_Code' => $data->Te_Code_New, 'Term' => Session::get('Term')]) }}" target="_blank" class="small-box-footer">
              Click here for more info <i class="fa fa-arrow-circle-right"></i>
            </a>

      </div>
      </div>
    </div> 
  @endforeach
</div>
@endforeach

<script>
	$(document).ready(function() {
		if ($("input[name='box-L']").val() == 'A') {
			$('.small-box').addClass('bg-arab')
		}
		if ($("input[name='box-L']").val() == 'F') {
			$('.small-box').addClass('bg-fr')
		}		
		if ($("input[name='box-L']").val() == 'C') {
			$('.small-box').addClass('bg-chi')
		}		
		if ($("input[name='box-L']").val() == 'E') {
			$('.small-box').addClass('bg-eng')
		}		
		if ($("input[name='box-L']").val() == 'R') {
			$('.small-box').addClass('bg-ru')
		}		
		if ($("input[name='box-L']").val() == 'S') {
			$('.small-box').addClass('bg-sp')
		}
		var arr = [];
		var token = $("input[name='_token']").val();
		$("input[name='Te_Code_New']").each(function() {
			var Te_Code_New = $(this).val();
			arr.push(Te_Code_New); //insert values to array per iteration
		});
		console.log(arr)

			$.ajax({
				url: '{{ route('ajax-preview-get-student-count') }}',
				type: 'POST',
				data: {arr:arr,_token:token},
			})
			.done(function(data) {
				console.log(data);
				var sum = 0;
				$.each(data, function(x, y) {
					$("input[name='Te_Code_New']").each(function() {
						if ($(this).val() == x) {
							$('h3.count-students-'+x).html(y+' Students')
						}
					});

					sum+=parseFloat(y) || 0;
				});
				$("div.total").html("<h3>Total Number of Students (Approved Enrolment Forms + Approved and Assigned Placement Forms): <span class='label label-default'>"+sum+"</span></h3>");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
			
		
		let languageSelected = $("input[name='box-L']").val();
			$.ajax({
				url: '{{ route('ajax-preview-get-pending-placement-count') }}',
				type: 'POST',
				data: {languageSelected:languageSelected,_token:token},
			})
			.done(function(countPendingPlacement) {
				console.log(countPendingPlacement);
				$("div.pending-placement").html("<h3>Pending Placement Tests (Not Included in Total): <span class='label label-default'>"+countPendingPlacement+"</span></h3>");
			})
			.fail(function() {
				console.log("error getting pending placement count.");
			})
			.always(function() {
				console.log("complete");
			});


		$("input[name='cs_unique']").on('click', function(event) {
			event.preventDefault();
			/* Act on the event */
		});
	});
</script>