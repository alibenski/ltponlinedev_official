@extends('admin.admin')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
  <div class="row">
    <div class="col-sm-12">
      @include('admin.partials._termSessionMsg')
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <div class="box box-info">
        <div class="box-body">
          <form method="GET" action="">
            {{ csrf_field() }}
  
            <input type="hidden" name="term" value=@if (Session::has('Term')) "{{ Session::get('Term') }}"  @else "" @endif >
  
            <div class="form-group col-sm-12">
            <label for="L" class="control-label"> Select Language: </label>
            <div class="col-sm-12">
              @foreach ($languages as $id => $name)
              <div class="col-sm-4">
                <div class="input-group"> 
                <span class="input-group-addon">       
                  <input id="{{ $name }}" type="radio" name="L" value="{{ $id }}">                 
                </span>
                  <label for="{{ $name }}" type="text" class="form-control">{{ $name }}</label>
                </div>
              </div>
              @endforeach 
            </div>
            </div>

            <div class="form-group col-sm-12" style="display: none;">
              <label name="room" class="control-label">Rooms: </label>
              <select id="room_select" class="col-md-8 form-control select2-multi" name="room" multiple="multiple" autocomplete="off"  style="width: 100%">
                  <option value="0"> All Rooms </option>
                  @foreach ($rooms as $id => $name)
                      <option value="{{$id}}">{{$name}} </option>
                  @endforeach
              </select>
            </div>

            <input type="hidden" name="_token" value="{{ Session::token() }}">
          </form>
        </div>
          @if (!Session::has('Term'))
            <div class="overlay"></div>
          @endif
      </div>
    </div>
  </div>

  <div class="insert-view-calendar-here"></div>
@stop
@section('java_script')
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        $.ajaxSetup({ cache: false }); // or iPhones don't get fresh data
        $('.select2-multi').select2({
          placeholder: " --- Select Room Here ---",
          maximumSelectionLength: 1,
        });
        $("input[name='L']").prop('checked', false);
        $("input[name='L']").click(function(){
            var L = $(this).val();
            var term = $("input[name='term']").val();
            var token = $("input[name='_token']").val();

            $.ajax({
                url: "{{ route('view-calendar') }}", 
                method: 'POST',
                data: {L:L, term:term, _token:token},
                success: function(data, status) {
                  $("div.insert-view-calendar-here").html('');
                  $("div.insert-view-calendar-here").html(data.options);
                }
            });
        }); 
      });
    </script>
@stop