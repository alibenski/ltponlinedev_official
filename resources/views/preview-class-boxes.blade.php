@foreach ($classrooms->chunk(4) as $element)
<div class="row">
  @foreach($element as $data)
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="row">
      	
      <div id="{{$data->Code}}" class="small-box" data-teacher="@if(empty($data->Tch_ID)) 0 @elseif($data->Tch_ID == 'TBD') 0 @else {{ $data->Tch_ID }} @endif">
        <div class="inner">
        <h3 class="count-students-{{$data->Code}}">--</h3>
        <h4>{{$data->course->Description}}</h4>
        <p>{{ $data->Code }}</p>
        <p>{{ $data->scheduler->name }}</p>
        <p>@if(empty($data->teachers)) <span class="text-danger">No Teacher: Waitlist/Class Cancelled</span> @elseif($data->Tch_ID == 'TBD') <span class="text-danger">No Teacher: Waitlist/Class Cancelled</span> @else {{ $data->teachers->Tch_Name }} @endif</p>
                
		<input type="hidden" name="Code" value="{{$data->Code}}">
		<input type="hidden" name="Te_Code_New" value="{{$data->Te_Code_New}}">
		<input type="hidden" name="box-L" value="{{$data->L}}">
        <input type="hidden" name="_token" value="{{ Session::token() }}">             
          
        </div>
        <div class="icon">
          <i class="ion ion-university"></i>
        </div>
        
        <a href="{{ route('view-classrooms-per-section', ['Code' => $data->Code]) }}" target="_blank" class="small-box-footer">
              More info  <i class="fa fa-arrow-circle-right"></i>
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

		$("div.small-box").each(function() {
			var code = $(this).attr('id');
			var teacher = $(this).attr('data-teacher');
			
			if (teacher == 0) {
				$(this).addClass('bg-gray');
			}
		});

		var arr = [];
		var token = $("input[name='_token']").val();
		$("input[name='Code']").each(function() {
			var Code = $(this).val();
			arr.push(Code); //insert values to array per iteration
		});
		console.log(arr)

		if (arr) {

			$.ajax({
				url: '{{ route('ajax-get-student-count-per-class') }}',
				type: 'GET',
				data: {arr:arr,_token:token},
			})
			.done(function(data) {
				console.log(data);
				$.each(data, function(x, y) {
					$("input[name='Code']").each(function() {
						if ($(this).val() == x) {
							$('h3.count-students-'+x).html(y+' Students')
						}
					});
				});
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
		}
			
	});
</script>