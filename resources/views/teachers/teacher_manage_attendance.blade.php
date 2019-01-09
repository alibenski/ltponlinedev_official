
@extends('teachers.teacher_template')

@section('content')

<div class="row">
  <div class="col-md-12">
    <h4><strong>Log Student Attedance for {{ $course->courses->Description}} - {{ $day }}: {{ $time }} ({{ $week }})</strong></h4>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>P</th>   
              <th>L</th>   
              <th>E</th>   
              <th>A</th>   
          </tr>
          <tr>
              <th></th>
              <th></th>
              <th class="pull-right">Set status for all students <i class="fa fa-arrow-circle-right"></i></th>
              <th><input name="allStatus" type="radio" id="masterP"></th>
              <th><input name="allStatus" type="radio" id="masterL"></th>
              <th><input name="allStatus" type="radio" id="masterE"></th>
              <th><input name="allStatus" type="radio" id="masterA"></th>
              
              {{-- <th>Remarks</th> --}}
          </tr>
      </thead>
      <tbody>
        @foreach($form_info as $form)
        <tr id="{{$form->id}}">
          <td>
            <div class="counter"></div>
          </td>
          <td>
            @if(empty($form->users->name)) None @else {{ $form->users->name }} @endif </td>
          <td>
            @if(empty($form->users->email)) None @else {{ $form->users->email }} @endif </td>
          <td><input name="indivStatus" type="radio" class="sub_chk_p" data-id="{{ $form->id }}"></td>
          <td><input name="indivStatus" type="radio" class="sub_chk_l" data-id="{{ $form->id }}"></td>
          <td><input name="indivStatus" type="radio" class="sub_chk_e" data-id="{{ $form->id }}"></td>
          <td><input name="indivStatus" type="radio" class="sub_chk_a" data-id="{{ $form->id }}"></td>
          {{-- <td>
            <textarea name="" id="" cols="30" rows="1"></textarea>
          </td> --}}
        </tr>
        @endforeach
      </tbody>
  </table>
  </div>
</div>
  

@stop

@section('java_script')
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


      $('#masterP').on('click', function(e) {
       if($(this).is(':checked',true))  
       {
          $(".sub_chk_p").prop('checked', true);  
       } else {  
          $(".sub_chk_p").prop('checked',false);  
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
          //     // var check = confirm("Are you sure you want to delete this row?");  
          //     // if(check == true){  


          //     //     var join_selected_values = allVals.join(","); 


          //     //     $.ajax({
          //     //         url: $(this).data('url'),
          //     //         type: 'DELETE',
          //     //         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          //     //         data: 'ids='+join_selected_values,
          //     //         success: function (data) {
          //     //             if (data['success']) {
          //     //                 $(".sub_chk:checked").each(function() {  
          //     //                     $(this).parents("tr").remove();
          //     //                 });
          //     //                 alert(data['success']);
          //     //             } else if (data['error']) {
          //     //                 alert(data['error']);
          //     //             } else {
          //     //                 alert('Whoops Something went wrong!!');
          //     //             }
          //     //         },
          //     //         error: function (data) {
          //     //             alert(data.responseText);
          //     //         }
          //     //     });


          //     //   $.each(allVals, function( index, value ) {
          //     //       $('table tr').filter("[data-row-id='" + value + "']").remove();
          //     //   });
          //     // }  
          // }  
      });


      // $('[data-toggle=confirmation]').confirmation({
      //     rootSelector: '[data-toggle=confirmation]',
      //     onConfirm: function (event, element) {
      //         element.trigger('confirm');
      //     }
      // });


      // $(document).on('confirm', function (e) {
      //     var ele = e.target;
      //     e.preventDefault();


      //     $.ajax({
      //         url: ele.href,
      //         type: 'DELETE',
      //         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      //         success: function (data) {
      //             if (data['success']) {
      //                 $("#" + data['tr']).slideUp("slow");
      //                 alert(data['success']);
      //             } else if (data['error']) {
      //                 alert(data['error']);
      //             } else {
      //                 alert('Whoops Something went wrong!!');
      //             }
      //         },
      //         error: function (data) {
      //             alert(data.responseText);
      //         }
      //     });


      //     return false;
      // });
  });
</script>
@stop