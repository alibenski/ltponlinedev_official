<div class="modal-body">
	@include('admin.partials._termSessionMsg')
    <div class="col-sm-12">
        <div id="my-box" class="box box-info">
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
        <div class="default-text-section">
            <div class="form-group col-sm-12">
                <div class="col-sm-6">
                <a href="{{ route('view-default-email-waitlist-text') }}" onclick="window.open(this.href, '', 'resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;" class="btn btn-default btn-block"><span class='fa fa-eye'></span> View default email text for waitlisted students</a>
                </div>

                <div class="col-sm-6 send-default-section">
                    <button class="btn btn-success btn-block send-default-waitlist-email"><span class='fa fa-envelope-o'></span> Send default email text to all SELECTED waitlisted students</button>
                </div>
            </div>

            <div class="col-sm-12">
                <hr />
            </div>
        </div>


        <div class="form-group col-sm-12">
            <h4><input type="checkbox" class="custom_chk" /><strong> I will use custom text for the waitlist email notification. </strong></h4>
        </div>
        
        
        <div class="custom-text-section" style="display:none;">
            <div class="col-sm-12">
                <hr />
            </div>
            <div class="form-group col-sm-12">
                <label for="subject">Subject: </label>
                <input type="text" name="subject" placeholder="@if (is_null($text->subject))
                    -- no subject -- @else {{$text->subject}} @endif" value="" style="width: 100%;">
            </div>

            <div class="form-group col-sm-12">
                <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
                <textarea id="editorEmailWaitlist" name="textValue" class="form-control" cols="40" rows="5" placeholder="Custom Text Here">
                    {{ $text->text }}
                </textarea>
            </div>
            
            <div class="form-group col-sm-12">
                <div class="col-sm-4 view-custom-section">
                <a href="{{ route('view-custom-email-waitlist-text') }}" onclick="window.open(this.href, '', 'resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;" id="viewCustomEmail" class="btn btn-default btn-view-custom-email"><span class='fa fa-eye'></span> View customized text </a>
                </div>
                <div class="col-sm-4 save-custom-section">
                <button id="saveCustomEmail" type="button" class="btn btn-default btn-save-custom-email"><span class='fa fa-save'></span> 
                Save text</button>
                </div>
                <div class="col-sm-4 send-custom-section">
                <button id="sendCustomEmail" type="button" class="btn btn-success btn-send-custom-email"><span class='fa fa-paper-plane'></span> 
                Send customized email text</button>
                </div>
            </div>
        </div>

</div>
<script>
// Replace the <textarea id="editor1"> with a CKEditor
// instance, using default configuration.
CKEDITOR.replace( "editorEmailWaitlist");

$("input.custom_chk").on("change", function () {
    if ($(this).is(':checked',true)) {
        $(".custom-text-section").removeAttr("style");
        $(".default-text-section").attr('style', 'display:none;');
    } else {
        $(".default-text-section").removeAttr("style");
        $(".custom-text-section").attr('style', 'display:none;');
    }
});

$('.send-default-waitlist-email').on('click', function(e) {
    let term = $("input[name='term_id']").val();
    let token = $("input[name='_token']").val();
    let allIds = [];  
      $(".repo-id").each(function() {  
          allIds.push($(this).attr("data-id"));
      });  
    let join_selected_values = allIds.join(",");
    
    $("div.send-default-section").html("");
    $("div.send-default-section").html("<button class='btn btn-warning btn-block send-default-waitlist-email' disabled><span class='fa fa-refresh fa-spin'></span> Sending... Please wait </button>");
    
    $.ajax({
          url: "{{ route('send-default-waitlist-email') }}", 
          method: 'POST',
          data: {ids:join_selected_values, term_id:term, _token:token},
          success: function(data) {
          	console.log(data)
            alert("Default Waitlist Email Sent");
            $("div.send-default-section").html("");
            $("div.send-default-section").html("<button class='btn btn-danger btn-block send-default-waitlist-email' disabled> Email Sent </button>");
          }
      });
    
});

$(document).on("click", "#saveCustomEmail", function () {
    let term = $("input[name='term_id']").val();
    let token = $("input[name='_token']").val();
    let subject = $("input[name='subject']").val();
    let textValue =  CKEDITOR.instances['editorEmailWaitlist'].getData();

    $("div.save-custom-section").html("");
    $("div.save-custom-section").html("<button id='saveCustomEmail' class='btn btn-default btn-save-custom-email'><span class='fa fa-refresh fa-spin'></span> Saving... Please wait </button>");
    
    $.ajax({
        url: "{{ route('store-enrolment-is-open-text', 3) }}", 
        method: 'PUT',
        data: { subject:subject, textValue:textValue, term_id:term, _token:token},
        success: function(data) {
            console.log(data)
            alert(data);
            $("div.save-custom-section").html("");
            $("div.save-custom-section").html("<button id='saveCustomEmail' class='btn btn-default btn-save-custom-email'><span class='fa fa-save'></span> Save text </button>");

            $("div.save-custom-section").on("change", "#saveCustomEmail",function () {
                alert("some");
            })
        }
    });

})
</script>