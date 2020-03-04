@extends('admin.admin')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
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
            <label for="L" class="control-label"> Language:</label>
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
    
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        $.ajaxSetup({ cache: false }); // or iPhones don't get fresh data
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