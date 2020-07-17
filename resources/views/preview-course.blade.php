@extends('admin.no_sidebar_admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style type="text/css">
    .centerImage {
    display: block;
    margin: 0 auto;
    }
    </style>
@stop

@section('content')
<div class="fancy-pants"></div>
<div class="alert bg-black col-sm-12">
	<h4 class="text-center"><strong>Run Batch</strong></h4>
</div>

<div class="row">
    <img src="{{ asset('img/82.gif') }}" alt="fancy-pants" class="centerImage">
</div>

{{-- @include('admin.partials._termSessionMsg') --}}
<div class="row">
  <div class="col-md-12">
    <div class="row" style="border: solid 1px skyblue; margin-bottom: 75px">
      <div class="col-md-4">
        <form method="POST" action="{{ route('insert-priority-1') }}" class="form-prevent-multi-submit">
          {{ csrf_field() }}
          <div class="form-group col-sm-12 add-margin">
            <select class="col-sm-8 form-control select2-filter" name="Term" autocomplete="off" required="required" style="width: 90%">
                <option value="">--- Select Term ---</option>
                @foreach ($terms as $value)
                    <option value="{{$value->Term_Code}}">{{$value->Term_Code}} {{$value->Comments}} - {{$value->Term_Name}}</option>
                @endforeach
            </select>
          </div>
    
          <div class="form-group col-sm-12 add-margin">           
                <button type="submit" class="btn btn-success button-prevent-multi-submit execute-batch">Insert Priority 1</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
          </div>
        </form>
      </div>
  
      <div class="col-md-4">
        <form method="POST" action="{{ route('insert-priority-2') }}" class="form-prevent-multi-submit">
          {{ csrf_field() }}
          <div class="form-group col-sm-12 add-margin">
            <select class="col-sm-8 form-control select2-filter" name="Term" autocomplete="off" required="required" style="width: 90%">
                <option value="">--- Select Term ---</option>
                @foreach ($terms as $value)
                    <option value="{{$value->Term_Code}}">{{$value->Term_Code}} {{$value->Comments}} - {{$value->Term_Name}}</option>
                @endforeach
            </select>
          </div>
    
          <div class="form-group col-sm-12 add-margin">           
                <button type="submit" class="btn btn-success button-prevent-multi-submit execute-batch">Insert Priority 2</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
          </div>
        </form>
      </div>
  
      <div class="col-md-4">
        <form method="POST" action="{{ route('insert-priority-2-placement') }}" class="form-prevent-multi-submit">
          {{ csrf_field() }}
          <div class="form-group col-sm-12 add-margin">
            <select class="col-sm-8 form-control select2-filter" name="Term" autocomplete="off" required="required" style="width: 90%">
                <option value="">--- Select Term ---</option>
                @foreach ($terms as $value)
                    <option value="{{$value->Term_Code}}">{{$value->Term_Code}} {{$value->Comments}} - {{$value->Term_Name}}</option>
                @endforeach
            </select>
          </div>
    
          <div class="form-group col-sm-12 add-margin">           
                <button type="submit" class="btn btn-success button-prevent-multi-submit execute-batch">Insert Priority 2 Placement</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
          </div>
        </form>
      </div>
  
      <div class="col-md-4">
        <form method="POST" action="{{ route('insert-priority-3') }}" class="form-prevent-multi-submit">
          {{ csrf_field() }}
          <div class="form-group col-sm-12 add-margin">
            <select class="col-sm-8 form-control select2-filter" name="Term" autocomplete="off" required="required" style="width: 90%">
                <option value="">--- Select Term ---</option>
                @foreach ($terms as $value)
                    <option value="{{$value->Term_Code}}">{{$value->Term_Code}} {{$value->Comments}} - {{$value->Term_Name}}</option>
                @endforeach
            </select>
          </div>
    
          <div class="form-group col-sm-12 add-margin">           
                <button type="submit" class="btn btn-success button-prevent-multi-submit execute-batch">Insert Priority 3</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
          </div>
        </form>
      </div>
  
      <div class="col-md-4">
        <form method="POST" action="{{ route('insert-priority-4') }}" class="form-prevent-multi-submit">
          {{ csrf_field() }}
          <div class="form-group col-sm-12 add-margin">
            <select class="col-sm-8 form-control select2-filter" name="Term" autocomplete="off" required="required" style="width: 90%">
                <option value="">--- Select Term ---</option>
                @foreach ($terms as $value)
                    <option value="{{$value->Term_Code}}">{{$value->Term_Code}} {{$value->Comments}} - {{$value->Term_Name}}</option>
                @endforeach
            </select>
          </div>
    
          <div class="form-group col-sm-12 add-margin">           
                <button type="submit" class="btn btn-success button-prevent-multi-submit execute-batch">Insert Priority 4</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
          </div>
        </form>
      </div>

      <div class="col-md-4">
        <form method="POST" action="{{ route('insert-priority-5') }}" class="form-prevent-multi-submit">
          {{ csrf_field() }}
          <div class="form-group col-sm-12 add-margin">
            <select class="col-sm-8 form-control select2-filter" name="Term" autocomplete="off" required="required" style="width: 90%">
                <option value="">--- Select Term ---</option>
                @foreach ($terms as $value)
                    <option value="{{$value->Term_Code}}">{{$value->Term_Code}} {{$value->Comments}} - {{$value->Term_Name}}</option>
                @endforeach
            </select>
          </div>
    
          <div class="form-group col-sm-12 add-margin">           
                <button type="submit" class="btn btn-success button-prevent-multi-submit execute-batch">Insert Priority 5</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
          </div>
        </form>
      </div>

    </div>
    
    <div class="row" style="border: solid 1px skyblue; margin-bottom: 75px">
      <div class="col-md-4">
        <form method="POST" action="{{ route('order-codes') }}" class="form-prevent-multi-submit">
          {{ csrf_field() }}
          <div class="form-group col-sm-12 add-margin">
            <select class="col-sm-8 form-control select2-filter" name="Term" autocomplete="off" required="required" style="width: 90%">
                <option value="">--- Select Term ---</option>
                @foreach ($terms as $value)
                    <option value="{{$value->Term_Code}}">{{$value->Term_Code}} {{$value->Comments}} - {{$value->Term_Name}}</option>
                @endforeach
            </select>
          </div>
    
          <div class="form-group col-sm-12 add-margin">           
                <button type="submit" class="btn btn-success button-prevent-multi-submit execute-batch">Execute Phase 2</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
          </div>
        </form>
      </div>

      <div class="col-md-4">
        <form method="POST" action="{{ route('assign-course-sched-to-student') }}" class="form-prevent-multi-submit">
          {{ csrf_field() }}
          <div class="form-group col-sm-12 add-margin">
            <select class="col-sm-8 form-control select2-filter" name="Term" autocomplete="off" required="required" style="width: 90%">
                <option value="">--- Select Term ---</option>
                @foreach ($terms as $value)
                    <option value="{{$value->Term_Code}}">{{$value->Term_Code}} {{$value->Comments}} - {{$value->Term_Name}}</option>
                @endforeach
            </select>
          </div>
    
          <div class="form-group col-sm-12 add-margin">           
                <button type="submit" class="btn btn-success button-prevent-multi-submit execute-batch">Execute Phase 3</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
          </div>
        </form>
      </div>

      <div class="col-md-4">
        <form method="POST" action="{{ route('check-code-if-exists-in-preview') }}" class="form-prevent-multi-submit">
          {{ csrf_field() }}
          <div class="form-group col-sm-12 add-margin">
            <select class="col-sm-8 form-control select2-filter" name="Term" autocomplete="off" required="required" style="width: 90%">
                <option value="">--- Select Term ---</option>
                @foreach ($terms as $value)
                    <option value="{{$value->Term_Code}}">{{$value->Term_Code}} {{$value->Comments}} - {{$value->Term_Name}}</option>
                @endforeach
            </select>
          </div>
    
          <div class="form-group col-sm-12 add-margin">           
                <button type="submit" class="btn btn-success button-prevent-multi-submit execute-batch">Execute Phase 4</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
          </div>
        </form>
      </div>
    </div>

    <div class="row" style="border: solid 1px skyblue; margin-bottom: 75px">
      <div class="col-md-4">
        <form method="POST" action="{{ route('check-duplicate-in-preview') }}" class="form-prevent-multi-submit">
          {{ csrf_field() }}
          <div class="form-group col-sm-12 add-margin">
            <select class="col-sm-8 form-control select2-filter" name="Term" autocomplete="off" required="required" style="width: 90%">
                <option value="">--- Select Term ---</option>
                @foreach ($terms as $value)
                    <option value="{{$value->Term_Code}}">{{$value->Term_Code}} {{$value->Comments}} - {{$value->Term_Name}}</option>
                @endforeach
            </select>
          </div>
    
          <div class="form-group col-sm-12 add-margin">           
                <button type="submit" class="btn btn-warning button-prevent-multi-submit execute-batch">Check Duplicate Forms</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
          </div>
        </form>
      </div>
  
      <div class="col-md-4">
        <form method="POST" action="{{ route('check-undefined-offset') }}" class="form-prevent-multi-submit">
          {{ csrf_field() }}
          <div class="form-group col-sm-12 add-margin">
            <select class="col-sm-8 form-control select2-filter" name="Term" autocomplete="off" required="required" style="width: 90%">
                <option value="">--- Select Term ---</option>
                @foreach ($terms as $value)
                    <option value="{{$value->Term_Code}}">{{$value->Term_Code}} {{$value->Comments}} - {{$value->Term_Name}}</option>
                @endforeach
            </select>
          </div>
    
          <div class="form-group col-sm-12 add-margin">           
                <button type="submit" class="btn btn-warning button-prevent-multi-submit execute-batch">Check Undefined Offset</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
          </div>
        </form>
      </div>
      
      <div class="col-md-4">
        <form method="POST" action="{{ route('create-classrooms') }}" class="form-prevent-multi-submit">
          {{ csrf_field() }}
          <div class="form-group col-sm-12 add-margin">
            <select class="col-sm-8 form-control select2-filter" name="Term" autocomplete="off" required="required" style="width: 90%">
                <option value="">--- Select Term ---</option>
                @foreach ($terms as $value)
                    <option value="{{$value->Term_Code}}">{{$value->Term_Code}} {{$value->Comments}} - {{$value->Term_Name}}</option>
                @endforeach
            </select>
          </div>
          
          <div class="form-group col-sm-12 add-margin">
            <label for="minimum">Enter Minimum No. of Students</label>
            <input type="number" name="minimum">
          </div>
    
          <div class="form-group col-sm-12 add-margin">           
                <button type="submit" class="btn btn-success button-prevent-multi-submit">Execute Phase 5</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
          </div>
        </form>
      </div>

    </div>

  </div>
</div>
@stop

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/submit.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("div.fancy-pants").delay(1000).fadeOut(1000,"linear");

    $('.select2-basic-single').select2({
    placeholder: "Select Filter",
    });

    $(".execute-batch").click(function() {
      $("div.fancy-pants").fadeIn(1000,"linear");;
    });
});
</script>
<script type="text/javascript">
  $("input[name='L']").click(function(){
      var L = $(this).val();
      var term = $("input[name='term_id']").val();
      var token = $("input[name='_token']").val();

      $.ajax({
          url: "{{ route('select-ajax') }}", 
          method: 'POST',
          data: {L:L, term_id:term, _token:token},
          success: function(data, status) {
            $("select[name='Te_Code']").html('');
            $("select[name='Te_Code']").html(data.options);
          }
      });
  }); 
</script>
@stop