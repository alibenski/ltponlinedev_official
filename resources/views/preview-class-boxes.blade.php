{{-- @foreach ($classrooms->chunk(4) as $element) --}}
{{-- Group by Course Name per row --}}
@foreach ($classrooms as $element)
@foreach ($element->groupBy('Te_Code_New') as $x)
<div class="row">
	<h3><strong>{{ $x->first()->course->Description }}</strong></h3>
	@foreach ($x->chunk(4) as $items)
	@foreach($items as $data)
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
		<p class="count-no-show-{{$data->Code}}"></p>
                
		<input type="hidden" name="Code" value="{{$data->Code}}">
		<input type="hidden" name="Te_Code_New" value="{{$data->Te_Code_New}}">
		<input type="hidden" name="box-L" value="{{$data->L}}">
        <input type="hidden" name="_token" value="{{ Session::token() }}">             
          
        </div>
        <div class="icon">
          <i class="ion ion-university"></i>
        </div>
        
        <a href="{{ route('view-classrooms-per-section', $data->Code) }}" target="_blank" class="small-box-footer">
              More info  <i class="fa fa-arrow-circle-right"></i>
            </a>

      </div>
      </div>
    </div> 
  	@endforeach
</div>
	@endforeach
<div class="row">
	<div class="col-lg-3 col-xs-6">
		<input type="hidden" name="Te_Code" value="{{$x->first()->Te_Code_New}}" />
		<div id="" class="small-box bg-red" data-teacher="0">
        <div class="inner">
        <h3 class="count-waitlist-{{$x->first()->Te_Code_New}}">--</h3>
        <h4>{{ $x->first()->course->Description }}</h4>
        <p>Total Waitlisted Students</p>
                
        </div>
        <div class="icon">
          <i class="ion ion-android-hand"></i>
        </div>
        
        <a href="{{ route('waitListOneList', $x->first()->Te_Code_New) }}" target="_blank" class="small-box-footer">
              More info  <i class="fa fa-arrow-circle-right"></i>
            </a>

      </div>
	</div>
	
	<div class="col-lg-3 col-xs-6">
		<input type="hidden" name="Te_Code" value="{{$x->first()->Te_Code_New}}" />
		<div id="" class="small-box bg-teal" data-teacher="0">
        <div class="inner">
        <h3 class="count-noclass-{{$x->first()->Te_Code_New}}">--</h3>
        <h4>{{ $x->first()->course->Description }}</h4>
        <p>Total No Class Students</p>
                
        </div>
        <div class="icon">
          <i class="ion ion-alert-circled"></i>
        </div>
        
        {{-- <a href="{{ route('waitListOneList', $x->first()->Te_Code_New) }}" target="_blank" class="small-box-footer">
              More info  <i class="fa fa-arrow-circle-right"></i>
            </a> --}}

      </div>
	</div>
</div>
@endforeach
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

		if (arr.length > 0) {

			$.ajax({
				url: '{{ route('ajax-get-student-count-per-class') }}',
				type: 'POST',
				data: {arr:arr,_token:token},
			})
			.done(function(data) {
				console.log(data);
				if (data.status == "fail") {
					alert(data.message);
				}
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

			$.ajax({
				url: '{{ route('ajax-get-no-show-count-per-class') }}',
				type: 'POST',
				data: {arr:arr,_token:token},
			})
			.done(function(data) {
				console.log(data);
				if (data.status == "fail") {
					alert(data.message);
				}
				$.each(data, function(x, y) {
					$("input[name='Code']").each(function() {
						if ($(this).val() == x) {
							$('p.count-no-show-'+x).html('<h4><strong><span class="label label-warning">'+y+'</span> No-Show!</strong></h4>')
						}
					});
				});
			})
			.fail(function() {
				console.log("error no-show function");
			})
			.always(function() {
				console.log("end of no-show function");
			});
		}

		var arrTeCode = [];
		$("input[name='Te_Code']").each(function() {
			var Te_Code = $(this).val();
			arrTeCode.push(Te_Code); //insert values to array per iteration
		});
		console.log(arrTeCode)

		if (arrTeCode.length > 0) {

			$.ajax({
				url: '{{ route('waitListOneListCount') }}',
				type: 'GET',
				data: {arrTeCode: arrTeCode, _token: token},
			})
			.done(function(data) {
				console.log(data);
				if (data.status == "fail") {
					alert(data.message);
				}
				$.each(data, function(x, y) {
					$("input[name='Te_Code']").each(function() {
						if ($(this).val() == x) {
							$('h3.count-waitlist-'+x).html(y+' Waitlisted')
						}
					});
				});
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete waitlist count");
			});
			
			$.ajax({
				url: '{{ route('noClassStudentCount') }}',
				type: 'GET',
				data: {arrTeCode: arrTeCode, _token: token},
			})
			.done(function(data) {
				console.log(data);
				if (data.status == "fail") {
					alert(data.message);
				}
				$.each(data, function(x, y) {
					$("input[name='Te_Code']").each(function() {
						if ($(this).val() == x) {
							$('h3.count-noclass-'+x).html(y+' No Class')
						}
					});
				});
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete no class count");
			});
		}
			
	});
</script>