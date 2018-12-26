<div class="row">
  <div class="col-sm-12">
    <div id="accordion">
      <a href="{{ route('pdfview',['download'=>'pdf']) }}">Download PDF</a>
        @foreach($classrooms as $classroom)
        <h3><strong>{{ $classroom_3->course->Description}}</strong></h3>
        <div class="col-sm-12">
        <h3>Section # {{ $classroom->sectionNo }}</h3>
          <p>Teacher: <h4>@if($classroom->Tch_ID) <strong>{{ $classroom->teachers->Tch_Name }}</strong> @else <span class="label label-danger">none assigned / waitlisted</span> @endif</h4></p>
          @if(!empty($classroom->Te_Mon_Room))
          <p>Monday Room: <strong>{{ $classroom->roomsMon->Rl_Room }}</strong></p>
          <p>Monday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_BTime)) }}</strong></p>
          <p>Monday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_ETime ))}}</strong></p>
          @endif
          @if(!empty($classroom->Te_Tue_Room))
          <p>Tuesday Room: <strong>{{ $classroom->roomsTue->Rl_Room }}</strong></p>
          <p>Tuesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_BTime)) }}</strong></p>
          <p>Tuesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_ETime)) }}</strong></p>
          @endif
          @if(!empty($classroom->Te_Wed_Room))
          <p>Wednesday Room: <strong>{{ $classroom->roomsWed->Rl_Room }}</strong></p>
          <p>Wednesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_BTime ))}}</strong></p>
          <p>Wednesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_ETime)) }}</strong></p>
          @endif
          @if(!empty($classroom->Te_Thu_Room))
          <p>Thursday Room: <strong>{{ $classroom->roomsThu->Rl_Room }}</strong></p>
          <p>Thursday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_BTime)) }}</strong></p>
          <p>Thursday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_ETime ))}}</strong></p>
          @endif
          @if(!empty($classroom->Te_Fri_Room))
          <p>Friday Room: <strong>{{ $classroom->roomsFri->Rl_Room }}</strong></p>
          <p>Friday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_BTime ))}}</strong></p>
          <p>Friday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_ETime)) }}</strong></p>
          @endif

          <div class="table-responsive filtered-table">
            <h4><strong>{{ $classroom_3->course->Description}} Students</strong></h4>

            <button style="margin-bottom: 10px" class="btn btn-primary delete_all">Move Selected</button>
            
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><input type="checkbox" id="master"></th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                {{-- @foreach($form_info as $form_in) --}}
                  @foreach($form_info as $form)
                    @if ($form->CodeClass === $classroom->Code)
                    <tr id="tr_{{$form->id}}" @if($form->deleted_at) style="background-color: #eed5d2;" @else @endif>
                      <td>
                        <div class="counter"></div>
                      </td>
                      <td>
                        @if($form->deleted_at) 
                        @else 
                        <input type="checkbox" class="sub_chk" data-id="{{ $form->id }}">
                        <input type="hidden" name="_token" value="{{ Session::token() }}">
                        @endif
                      </td>
                      <td>
                        @if(empty($form->users->name)) None @else {{ $form->users->name }} @endif 
                        @if($form->deleted_at) <span class="label label-danger">Cancelled</span> @else @endif
                      </td>
                      <td>
                        @if(empty($form->users->email)) None @else {{ $form->users->email }} @endif 
                      </td>
                    </tr>  
                    @endif
                  @endforeach
                {{-- @endforeach --}}
                </tbody>
            </table>
          </div>
        </div>
        @endforeach
    </div>
  </div>
</div>

@section('java_script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-confirmation/1.0.5/bootstrap-confirmation.min.js"></script>
<script>
$(document).ready(function () {
    var counter = 0;
    $('.counter').each(function() {
        counter++;
        $(this).attr('id', counter);
        $('#'+counter).html(counter);
        // console.log(counter)
    });    
});
</script>

<script type="text/javascript">
  $(document).ready(function () {


      $('#master').on('click', function(e) {
       if($(this).is(':checked',true))  
       {
          $(".sub_chk").prop('checked', true);  
       } else {  
          $(".sub_chk").prop('checked',false);  
       }  
      });


      $('.delete_all').on('click', function(e) {

          var allVals = [];  
          $(".sub_chk:checked").each(function() {  
              allVals.push($(this).attr('data-id'));
          });  

          var join_selected_values = allVals.join(",");

          var token = $("input[name='_token']").val();
          

          if(allVals.length <=0)  
          {  
              alert("Please select at least 1 student.");  

          }  else {  
              $('#modalshowform').modal('show');
              $.get('{{ route('ajax-move-students-form') }}', {'ids':join_selected_values,  '_token':token}, function(data) {
                // console.log(data);
                $('.modal-body-move-student').html(data);
              });
          }
  });
</script>
@stop