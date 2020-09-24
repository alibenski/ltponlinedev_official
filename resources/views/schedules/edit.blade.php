@extends('admin.admin')

@section('content')
<div class="container">
  <div class="col-md-12"><h3 class="alert text-center"><i class="icon fa fa-pencil"></i>Edit Schedule ID # {{$schedule->id}} - {{ $schedule->name }}</h3></div>
    <div class="col-md-12 form-group text-center">
      <h4 for="">Choose Type of Schedule:</h4>
      <button type="button" class="btn btn-default btn-lg btn-space standard">Standard</button>
      <button type="button" class="btn btn-default btn-lg btn-space non-standard">Non-Standard</button>
    </div>

    <form class="form-horizontal col-md-12" method="POST" action="{{ route('update-non-standard-schedule', $schedule->id) }}" autocomplete="off">
      {{ csrf_field() }}
      <div class="form-sched non-standard-schedule hidden">

        <div class="form-group">
          <label for="sched_name" class="control-label col-md-4">Non-standard Schedule:</label>
          <input type="hidden" name="standard_format" value="0">
          <input type="text" class="col-md-6 form-control-static" id="sched_name" name="sched_name" rows="1" style="resize:none;" placeholder="Enter the non-standard schedule format, e.g. 1 & 15 Feb, 1, 15 & 29 March, etc.">
          <strong><small class="col-md-6 col-md-offset-4 text-danger">Use only for non-standard schedule format, e.g. "1 & 15 Feb, 1, 15 & 29 March", "10 & 24 May, 7 & 21 June, 5 July",etc.</small></strong>
        </div>

        <div class="form-group">
          <label for="sched_name_fr" class="control-label col-md-4">French Translation:</label>
          <input type="text" class="col-md-6 form-control-static" id="sched_name_fr" name="sched_name_fr" rows="1" style="resize:none;" placeholder="Enter French Translation">
        </div>
        
        <div class="form-group">
            <label class="col-md-4 control-label">Choose Days:</label>
            <div class="col-md-4">
              @foreach ($days as $id => $name)
              <div class="input-group"> 
                <span class="input-group-addon">       
                  <input type="checkbox" name="begin_day[]" class="add-filter" value="{{ $id }}" autocomplete="off">                 
                </span>
                  <label type="text" class="form-control">{{ $name }}</label>
              </div>
              @endforeach
              <strong><p class="col-md-12 text-danger text-center">
                If none checked, the system by default will choose from Monday to Friday
              </p></strong>
            </div>
        </div>

        <div class="form-group">
            <label name="begin_time" class="col-md-4 control-label">Begin Time: </label>
            <select class="col-md-6 form-control-static" name="begin_time">
                <option value="">Select Begin Time</option>
                @foreach ($btimes as $id => $name)
                    <option value="{{ $id }}"> {{ date('h:i:sa', strtotime($name)) }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label name="end_time" class="col-md-4 control-label">End Time: </label>
            <select class="col-md-6 form-control-static" name="end_time">
                <option value="">Select End Time</option>
                @foreach ($etimes as $id => $name)
                    <option value="{{ $id }}"> {{ date('h:i:sa', strtotime($name)) }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 col-md-offset-4">
          <div class="row">
            <div class="col-sm-6">
              <a href="{{ route('schedules.index') }}" class="btn btn-danger btn-block">Cancel</a>
            </div>
            <div class="col-sm-6">
                <button type="submit" class="btn btn-success btn-block">Update</button>
                <input type="hidden" name="_token" value="{{ Session::token() }}">
                {{ method_field('PUT') }}
          </div>
        </div> 
      </div>

    </form>
</div>
<div class="container">
    <form class="form-horizontal col-md-12" method="POST" action="{{ route('schedules.update', $schedule->id) }}" autocomplete="off"> 
      {{ csrf_field() }}
      <div class="form-sched standard-schedule hidden">
              <!-- array checkboxes -->
              <div class="form-group">
                <input type="hidden" name="standard_format" value="1">
                  <label class="col-md-4 control-label">Choose Days:</label>
                  <div class="col-md-4">
                      <div class="checkbox">
                          <label>
                            @foreach ($days as $id => $name)
                              <input type="checkbox" name="begin_day[]" value="{{ $id }}" /> {{ $name }}
                              <br>
                            @endforeach
                          </label>
                      </div>
                  </div>
              </div>

              <div class="form-group">
                  <label name="begin_time" class="col-md-4 control-label">Begin Time: </label>
                  <select class="col-md-6 form-control-static" name="begin_time">
                      <option value="">Select Begin Time</option>
                      @foreach ($btimes as $id => $name)
                          <option value="{{ $id }}"> {{ date('h:i:sa', strtotime($name)) }}</option>
                      @endforeach
                  </select>
              </div>

              <div class="form-group">
                  <label name="end_time" class="col-md-4 control-label">End Time: </label>
                  <select class="col-md-6 form-control-static" name="end_time">
                      <option value="">Select End Time</option>
                      @foreach ($etimes as $id => $name)
                          <option value="{{ $id }}"> {{ date('h:i:sa', strtotime($name)) }}</option>
                      @endforeach
                  </select>
              </div>
    
            <div class="container">
              <div class="col-md-4 col-md-offset-4">
                <dl class="dl-horizontal">
                  <dt>Created at:</dt>
                  <dd>{{ date('M j, Y h:i:sa', strtotime($schedule->created_at)) }}</dd>
                </dl>

                <dl class="dl-horizontal">
                  <dt>Last updated:</dt>
                  <dd>{{ date('M j, Y h:i:sa', strtotime($schedule->updated_at)) }}</dd>
                </dl>
                <hr>
                <div class="row">
                  <div class="col-sm-6">
                    <a href="{{ route('schedules.index') }}" class="btn btn-danger btn-block">Cancel</a>
                  </div>
                  <div class="col-sm-6">
                      <button type="submit" class="btn btn-success btn-block">Update</button>
                      <input type="hidden" name="_token" value="{{ Session::token() }}">
                      {{ method_field('PUT') }}
                </div>
              </div>
              </div>
            </div>
      </div>
    </form>
</div>
@endsection

@section('java_script')
<script>
  $(document).ready(function() {
    $( "button.standard" ).click(function() {
      $(this).addClass('btn-success');
      $("button.non-standard").removeClass('btn-success');
      
      $( "div.form-sched.standard-schedule" ).removeClass('hidden');
      $( "div.form-sched.non-standard-schedule" ).addClass('hidden');

    });

    $( "button.non-standard" ).click(function() {
      $(this).addClass('btn-success');
      $("button.standard").removeClass('btn-success');
      
      $( "div.form-sched.non-standard-schedule" ).removeClass('hidden');
      $( "div.form-sched.standard-schedule" ).addClass('hidden');

    });
  });
</script>
@stop