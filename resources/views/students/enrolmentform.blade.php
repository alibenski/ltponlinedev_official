@extends('main')
@section('tabtitle', '| Profile')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Online Enrolment Form</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('students.update', $student->id) }}" class="well form-horizontal">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Full Name:</label>

                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span><input  name="name" placeholder="{{ $student->name }}" class="form-control"  type="text" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">Email Address:</label>

                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span><input  name="email" placeholder="{{ $student->email }}" class="form-control"  type="text" readonly>                                    
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_appointment" class="col-md-4 control-label">Type of Appointment:</label>

                            <div class="col-md-8">
                                <p class="form-control-static">{{ $student->job_appointment }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_category" class="col-md-4 control-label">Job Category:</label>

                            <div class="col-md-8">
                                <p class="form-control-static">{{ $student->job_category }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="course" class="col-md-4 control-label">Last UN Language Course:</label>

                            <div class="col-md-8">
                                <p class="form-control-static">
                                    @if(empty ($student->courses->name))
                                    none
                                    @else
                                    {{ $student->courses->name }}
                                    @endif
                                </p> 
                            </div>
                        </div>

                        <div class="form-group">
                            <label name="language_id" class="col-md-4 control-label">Enrol to which language: </label>
                            <select class="col-md-4 form-control-static" name="language_id">
                                <option value="">Select Language</option>
                                @foreach ($languages as $id => $name)
                                  <option value="{{ $id }}"> {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
							<label name="course_id" class="col-md-4 control-label">Enrol to which course: </label>
							<select class="col-md-4 form-control-static" name="course_id">
							    <option value="">--- Select Course ---</option>
							</select>
						</div>

						<div class="col-sm-offset-5">
			              <button type="submit" class="btn btn-default">Send Enrolment</button>
			              <input type="hidden" name="_token" value="{{ Session::token() }}">
			              {{ method_field('PUT') }}
			            </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts_code')
                
<script type="text/javascript">
  $("select[name='language_id']").change(function(){
      var language_id = $(this).val();
      var token = $("input[name='_token']").val();
      $.ajax({
          url: "http://enrol.local/select-ajax", 
          method: 'POST',
          data: {language_id:language_id, _token:token},
          success: function(data) {
            $("select[name='course_id'").html('');
            $("select[name='course_id'").html(data.options);
          }
      });
  }); 
</script>
@stop