<div class="modal-body">
	@include('admin.partials._termSessionMsg')
    <div class="col-sm-12">
        <div id="my-box" class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Expand to Review SELECTED Waitlisted Students</h3>
            <div class="box-tools pull-right">
            <!-- Collapse Button -->
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
                        <input type="hidden" class="repo-id" data-id="{{ $student->id }}">
                    </div>
                @endforeach
            </div>
        </div>
        <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>

    <input type="hidden" name="_token" value="{{ Session::token() }}">
    <input type="hidden" name="term_id" value="{{ Session::get('Term') }}">

		<div class="form-group col-sm-12">
            <div class="col-sm-6">
            <a href="{{ route('view-default-email-waitlist-text') }}" onclick="window.open(this.href, '', 'resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;" class="btn btn-info btn-block"><span class='fa fa-eye'></span> Show default email text for waitlisted students</a>
            </div>

            <div class="col-sm-6">
                <button class="btn btn-success btn-block send-default-waitlist-email"><span class='fa fa-envelope-o'></span> Send default email text to all SELECTED waitlisted students</button>
            </div>
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
            <button id="moveStudent" type="button" class="btn btn-success btn-move-student" disabled=""><span class='fa fa-paper-plane'></span> 
            Send customized email text</button>
        </div>

</div>

<script>
$('.send-default-waitlist-email').on('click', function(e) {
    let term = $("input[name='term_id']").val();
    let token = $("input[name='_token']").val();
    let allIds = [];  
      $(".repo-id").each(function() {  
          allIds.push($(this).attr('data-id'));
      });  
    let join_selected_values = allIds.join(",");

    $.ajax({
          url: "{{ route('send-default-waitlist-email') }}", 
          method: 'POST',
          data: {ids:join_selected_values, term_id:term, _token:token},
          success: function(data) {
          	console.log(data)
            alert("Default Wailist Email Sent");
          }
      });
    
});


</script>