@extends('admin.no_sidebar_admin')

@section('content')

  @foreach($arr as $value)
    @foreach($value as $data)
      {{$data->INDEXID}} - {{$data->CodeClass}} - {{$data->PS}}<br>
    @endforeach
  @endforeach

@stop

@section('java_script')
<script type="text/javascript">
  $("input[name='schedule_id']").click(function(){
      var schedule_id = $(this).val();
      var Te_Code = $("input[name='Te_Code']").val();
      var Term = $("input[name='Term']").val();
      var L = $("input[name='L']").val();
      var token = $("input[name='_token']").val();
      
      $.ajax({
          url: "{{ route('ajax-preview') }}", 
          method: 'POST',
          data: {schedule_id:schedule_id, Te_Code:Te_Code, Term:Term, L:L, _token:token},
          success: function(data, status) {
            console.log(data)
            $(".preview-here").html(data);
            $(".preview-here").html(data.options);
          }
      });
  }); 
</script>
@stop