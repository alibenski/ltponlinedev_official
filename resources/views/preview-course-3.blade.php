@extends('admin.no_sidebar_admin')

@section('content')

<div class="alert bg-black col-sm-12">
  <h4 class="text-center"><strong>Preview of {{$preview_course->courses->Description}}</strong></h4>
</div>
{{-- @include('admin.partials._termSessionMsg') --}}

<div class="form-group">
  <a href="{{ route('preview-vsa-page-2') }}" class="btn btn-danger btn-space"><i class="fa fa-arrow-left"></i> Back to Step 2</a>
</div>

<div class="row">
  @foreach($arr_count as $key => $value)
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{$value}} Students</h3>

          <p>{{ $key }}</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        
        {{-- <a href="#" class="small-box-footer">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a> --}}

      </div>
    </div> 
  @endforeach
</div>

<div class="row">
  @foreach($preview as $data)
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <span class="input-group-addon">       
                  <input type="radio" name="schedule_id" value="{{ $data->schedule_id }}" >    
                  <input type="hidden" name="Te_Code" value="{{ $preview_course->Te_Code }}" >    
                  <input type="hidden" name="Term" value="{{ $preview_course->Term }}" >    
                  <input type="hidden" name="L" value="{{ $preview_course->L }}" >    
                  <input type="hidden" name="_token" value="{{ Session::token() }}">             
          </span>
          <label type="text" class="form-control">{{ $data->schedules->name }}</label>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        
        <a href="{{ route('preview-classrooms', ['Code' => $data->Code]) }}" target="_blank" class="small-box-footer">
              More info on {{$data->Code}} <i class="fa fa-arrow-circle-right"></i>
            </a>

      </div>
    </div> 
  @endforeach
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="preview-here"></div>
  </div>
</div>

@stop

@section('java_script')
<script type="text/javascript">
  $("input[name='schedule_id']").click(function(){
      var schedule_id = $(this).val();
      var Te_Code = $("input[name='Te_Code']").val();
      var Term = $("input[name='Term']").val();
      var L = $("input[name='L']").val();
      var token = $("input[name='_token']").val();
      
      $.ajax({
          url: "{{ route('ajax-preview') }}", 
          method: 'POST',
          data: {schedule_id:schedule_id, Te_Code:Te_Code, Term:Term, L:L, _token:token},
          success: function(data, status) {
            console.log(data)
            $(".preview-here").html(data);
            $(".preview-here").html(data.options);
          }
      });
  }); 
</script>
@stop