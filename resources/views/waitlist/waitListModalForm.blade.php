<div class="modal-body">
	@include('admin.partials._termSessionMsg')
    <div class="col-sm-12">
        <div id="my-box" class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Expand to Review SELECTED Waitlisted Students</h3>
            <div class="box-tools pull-right">
            <!-- Collapse Button -->
            {{-- <button type="button" class="btn btn-box-tool" data-toggle="collapse" data-target="#toggle-pane-88"><i class="fa fa-plus"></i></button> --}}
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="collapse" data-target="#toggle-box-body" title="Expand/Collapse"><i class="fa fa-caret-down"></i></button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div id="toggle-box-body" class="collapse">
            <div class="row">
                @foreach ($student_to_move as $student)
                    <div class="col-sm-4">
                        <p style="margin-left: 10px">	{{ $student->users->name}} </p>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>

  <form name="move-student-form">
		<div class="form-group col-sm-12">
            <a href="{{ route('view-default-email-waitlist-text') }}" onclick="window.open(this.href, '', 'resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;" class="btn btn-info"><span class='fa fa-eye'></span> Show default email text for waitlisted students</a>
	    </div>

		<div class="form-group col-sm-12">
	        <button class="btn btn-success"><span class='fa fa-envelope-o'></span> Send default email text to all SELECTED waitlisted students</button>
	    </div>

		<div class="form-group col-sm-12">
            <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
	        <textarea id="editor-email-waitlist" class="form-control" cols="40" rows="5" placeholder="Custom Text Here"></textarea>
            <script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( "editor-email-waitlist" );
            </script>
	    </div>
        
        <div class="form-group col-sm-12">
            <button id="moveStudent" type="button" class="btn btn-success btn-move-student" disabled=""><span class='fa fa-arrow-right'></span> 
            Send customized email text</button>
        </div>
		
		<input type="hidden" name="_token" value="{{ Session::token() }}">
		<input type="hidden" name="term_id" value="{{ Session::get('Term') }}">
		{{ method_field('PUT') }}
  </form>
</div>

<div id="commentModal" class="modal modal-comment fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal">×</button> --}}
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" role="form">

                @foreach($comments as $comment)
                  @foreach($comment as $value)
                    <div class="panel panel-info">
                      <div class="panel-heading">
                          <strong>Student: {{ $value->pash->users->name }}</strong>
                      </div>
                      <div class="panel-body">
                          <p>
                            {{ $value->comments }} <br>
                            at {{ $value->created_at }} by {{ $value->user->name }} <br><br>
                          </p>  
                      </div>
                    </div>
                  @endforeach
                @endforeach

              </form>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-warning btn-close"><span class='glyphicon glyphicon-remove'></span> Close </button> --}}
            </div>
        </div>
    </div>
</div>

<script>
$(document).on('click', '.show-modal', function() {
    $('.modal-title').text('Admin Notes');
    console.log('click');
    $('#commentModal').modal('show'); 
});
</script>

{{-- <script>
  $(document).ready(function(){
    $(".btn-close").click(function(){
     $('.modal-comment').hide('slow'); 
    });   
  });   
</script> --}}

<script>
$(document).ready(function () {
  $("textarea[name='admin_comment']").on("keyup", action);
  $("textarea[name='admin_comment']").on("change", action);

  function action() {
      if( $("textarea[name='admin_comment']").val().length > 0 && $("textarea[name='admin_comment']").val() != '' && $("select[name='CodeClass']").val() != '' ) {
          $('#moveStudent').removeAttr('disabled');
      } else {
          $('#moveStudent').attr('disabled', 'disabled');
      }   
  }
});
</script>


<script type="text/javascript">
  $(document).ready(function(){
    $.ajaxSetup({ cache: false }); // or iPhones don't get fresh data
    $("input[name='L']").prop('checked', false);
  });

  $("input[name='L']").click(function(){
      var L = $(this).val();
      var term = $("input[name='term_id']").val();
      var token = $("input[name='_token']").val();

      $.ajax({
          url: "{{ route('select-ajax-admin') }}", 
          method: 'POST',
          data: {L:L, term_id:term, _token:token},
          success: function(data, status) {
          	console.log(data)
            $("select[name='Te_Code']").html('');
            $("select[name='Te_Code']").html(data.options);
          }
      });
  }); 

  $("select[name='Te_Code']").on('change',function(){
      var course_id = $(this).val();
      var term = $("input[name='term_id']").val();
      var token = $("input[name='_token']").val();

      $.ajax({
          url: "{{ route('ajax-select-classroom') }}", 
          method: 'POST',
          data: {course_id:course_id, term_id:term, _token:token},
          success: function(data) {
          	console.log(data)
            $("select[name='CodeClass']").html('');
            $("select[name='CodeClass']").html(data.options);
          }
      });
  }); 
</script>

<script>
	$('.btn-move-student').on('click', function (event) {
		var classroom_id = $("select[name='CodeClass']").val();
    var term = $("input[name='term_id']").val();
  	var admin_comment = $("textarea[name='admin_comment']").val();
  	var token = $("input[name='_token']").val();

    if (classroom_id === null) {
      event.preventDefault();
      return alert('Please fill the required fields.');
    }
    
  	var allVals = [];  
      $(".sub_chk:checked").each(function() {  
          allVals.push($(this).attr('data-id'));
      });  
    var join_selected_values = allVals.join(",");
        
		$.ajax({
          url: "{{ route('ajax-move-students') }}", 
          method: 'POST',
          data: {ids:join_selected_values,classroom_id:classroom_id, term_id:term, admin_comment:admin_comment, _token:token},
          success: function(data) {
          	console.log(data)
          	if (data['success']) {
                $(".sub_chk:checked").each(function() {  
                    $(this).parents("tr").remove();
                });
                	window.location.reload();
                    alert(data['success']);
                } else if (data['error']) {
                    alert(data['error']);
                } else {
                    alert('Whoops Something went wrong!!');
                }
            },
	        error: function (data) {
	            alert(data.responseText);
	        }
		});
	});
</script>
