<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="referrer" content="origin-when-cross-origin">
  <title>Class List</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <style>
    h4,h3 {
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    line-height: 1.42857143;
    color: #333;
    background-color: #fff;
    }
  </style>
</head>
<body>
  
<div class="row">
  <div class="col-sm-12">
      
        @foreach($classrooms as $classroom)

        <table>
          <tbody>
            <tr>
              <td>
              <img src="{{ asset("img/CLM-TextRight_En.jpg") }}" width="260" height="93" alt="CLM Language Training" border="0" style="height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; margin-right: 50px;"> 
              </td>
              <td class="pull-right" style="padding-left: 100px;">
                <h4 class="text-right"><strong>Language Training Programme</strong></h4>
                <h4 class="text-right"><strong>{{ $classroom_3->course->Description}}</strong></h4>
                <h4 class="text-right">{{$term_name}}</h4>
              </td>
            </tr>
          </tbody>
          
        </table>
        
        
        

        <table>
          <tbody>
            <tr>
              <td class="item1" style="margin: 5px; padding: 10px;">
                <p>Teacher: <span>@if($classroom->Tch_ID) <strong>{{ $classroom->teachers->Tch_Name }}</strong> @else <span class="label label-danger">none assigned / waitlisted</span> @endif</span></p>
              </td>
              @if(!empty($classroom->Te_Mon_Room))
              <td class="item2" style="margin: 5px; padding: 10px;">
              <p>Monday Room: <strong>{{ $classroom->roomsMon->Rl_Room }}</strong></p>
              <p>Monday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_BTime)) }}</strong></p>
              <p>Monday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_ETime ))}}</strong></p>
              </td>
              @endif

              @if(!empty($classroom->Te_Tue_Room))
              <td class="item3" style="margin: 5px; padding: 10px;">
              <p>Tuesday Room: <strong>{{ $classroom->roomsTue->Rl_Room }}</strong></p>
              <p>Tuesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_BTime)) }}</strong></p>
              <p>Tuesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_ETime)) }}</strong></p>
              </td>  
              @endif

              @if(!empty($classroom->Te_Wed_Room))
              <td class="item4" style="margin: 5px; padding: 10px;">
              <p>Wednesday Room: <strong>{{ $classroom->roomsWed->Rl_Room }}</strong></p>
              <p>Wednesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_BTime ))}}</strong></p>
              <p>Wednesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_ETime)) }}</strong></p>
              </td>
              @endif

              @if(!empty($classroom->Te_Thu_Room))
              <td class="item5" style="margin: 5px; padding: 10px;">
              <p>Thursday Room: <strong>{{ $classroom->roomsThu->Rl_Room }}</strong></p>
              <p>Thursday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_BTime)) }}</strong></p>
              <p>Thursday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_ETime ))}}</strong></p>
              </td>
              @endif

              @if(!empty($classroom->Te_Fri_Room))
              <td class="item6" style="margin: 5px; padding: 10px;">
              <p>Friday Room: <strong>{{ $classroom->roomsFri->Rl_Room }}</strong></p>
              <p>Friday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_BTime ))}}</strong></p>
              <p>Friday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_ETime)) }}</strong></p>
              </td>
              @endif

              @if(!empty($classroom->Te_Sat_Room))
              <td class="item6" style="margin: 5px; padding: 10px;">
              <p>Saturday Room: <strong>{{ $classroom->roomsSat->Rl_Room }}</strong></p>
              <p>Saturday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Sat_BTime ))}}</strong></p>
              <p>Saturday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Sat_ETime)) }}</strong></p>
              </td>
              @endif
            </tr>
          </tbody>
        </table>
          
      <div class="row">
        <div class="col-sm-12">
          <div class="table-responsive filtered-table">
            {{-- <h4><strong>{{ $classroom_3->course->Description}} Students</strong></h4> --}}

            {{-- <button style="margin-bottom: 10px" class="btn btn-primary delete_all">Move Selected</button> --}}
            
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                    </tr>
                </thead>
                <tbody>
                {{-- @foreach($form_info as $form_in) --}}
                  @foreach($form_info as $form)
                    @if ($form->CodeClass === $classroom->Code)
                    <tr id="tr_{{$form->id}}" @if($form->deleted_at) style="background-color: #eed5d2;" @else @endif>
                      <td>
                        @if(empty($form->users->name)) None @else {{ $form->users->name }} @endif 
                        @if($form->deleted_at) <span class="label label-danger">Cancelled</span> @else @endif
                      </td>
                      <td>
                        @if(empty($form->users->email)) None @else {{ $form->users->email }} @endif 
                      </td>
                      <td>
                        @if(empty($form->users->sddextr)) None @else {{ $form->users->sddextr->PHONE }} @endif
                      </td>
                    </tr>  
                    @endif
                  @endforeach
                {{-- @endforeach --}}
                </tbody>
            </table>
          </div>
        </div>
      </div>
          

        @endforeach
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <h5><strong>Total: {{ $student_count }}</strong></h5>
  </div>
</div>

</body>
</html>