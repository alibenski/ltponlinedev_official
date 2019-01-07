@extends('teachers.teacher_template')

@section('content')

@foreach($assigned_classes as $classroom)
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
        </tr>
      </tbody>
    </table>
@endforeach

@stop