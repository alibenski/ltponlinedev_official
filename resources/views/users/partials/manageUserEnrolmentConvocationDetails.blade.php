
<div class="row">
		      <div class="col-sm-12">
		          <div class="panel panel-success">
		              <div class="panel-heading"><strong>Convocation Details</strong></div>

		              <div class="panel-body">
		                <p>
		                  @foreach ($student_convoked as $element)
		                  <h3><strong>@if(!empty($element->courses->Description)){{ $element->courses->Description }}@endif</strong> <a href="{{ route('view-classrooms-per-section', [$element->CodeClass]) }}" target="_blank"><i class="fa fa-external-link"></i></a></h3>
		                  @if(empty($element->classrooms))
						  <h2 class="text-danger"><strong>Student assigned to a class with Course+Schedule Code, {{ $element->Code }}, which may have been deleted</strong></h2>
						  @endif
		                  <p>Schedule: <strong>@if(!empty($element->schedules->name)){{$element->schedules->name}}@endif</strong></p>  
						  @if(!empty($element->classrooms))
						  <p>
							MS Teams Class Name: <strong>{{ $element->Term }}-{{substr($element->courses->Description, 0, 2)}} {{substr($element->courses->Description, strpos($element->courses->Description, ": ") + 1)}}@if ( !is_null($element->classrooms->Tch_ID))-{{substr($element->classrooms->teachers->Tch_Firstname, 0, 1)}}. {{$element->classrooms->teachers->Tch_Lastname}}-@elseif ( $element->classrooms->Tch_ID == 'TBD')-TBD-@else-N/A-@endif{{substr($element->schedules->name, 0, 3)}}@if (($pos = strrpos($element->schedules->name, "&")) !== FALSE)&{{str_replace(' ','',substr($element->schedules->name, $pos + 1, 4))}} @endif 
								@if(\Carbon\Carbon::parse($element->schedules->begin_time) < \Carbon\Carbon::parse('1899-12-30 12:00:00'))Morning @else Lunch @endif //
								{{$element->CodeClass}} </strong>
						  </p>
		                    @if(!empty($element->classrooms->Te_Mon_Room))
		                    <p>Monday Room: <strong>{{ $element->classrooms->roomsMon->Rl_Room }}</strong></p>
		                    @endif
		                    @if(!empty($element->classrooms->Te_Tue_Room))
		                    <p>Tuesday Room: <strong>{{ $element->classrooms->roomsTue->Rl_Room }}</strong></p>
		                    @endif
		                    @if(!empty($element->classrooms->Te_Wed_Room))
		                    <p>Wednesday Room: <strong>{{ $element->classrooms->roomsWed->Rl_Room }}</strong></p>
		                    @endif
		                    @if(!empty($element->classrooms->Te_Thu_Room))
		                    <p>Thursday Room: <strong>{{ $element->classrooms->roomsThu->Rl_Room }}</strong></p>
		                    @endif
		                    @if(!empty($element->classrooms->Te_Fri_Room))
		                    <p>Friday Room: <strong>{{ $element->classrooms->roomsFri->Rl_Room }}</strong></p>
		                    @endif
		                    @if(!empty($element->classrooms->Te_Sat_Room))
		                    <p>Saturday Room: <strong>{{ $element->classrooms->roomsSat->Rl_Room }}</strong></p>
		                    @endif

		                  <p>
		                    @if($element->classrooms->Tch_ID == 'TBD')
		                    <h4><span class="label label-danger"> Waitlisted</span></h4> 
		                    @elseif(empty($element->classrooms->Tch_ID))
		                    <h4><span class="label label-danger"> Waitlisted</span></h4> 
		                    @else 
		                    Teacher: <strong>{{ $element->classrooms->teachers->Tch_Name }} </strong>
		                    @endif
		                  </p>
						  @endif
						  <p>
							Result: <strong>@if($element->Result == 'P') Passed @elseif($element->Result == 'F') Failed @elseif($element->Result == 'I') Incomplete @else -- @endif</strong>
						  </p>
						  <p>
							Written Grade: <strong>@if (!is_null($element->Written)) {{ $element->Written }} @endif</strong>
						  </p>
						  <p>
							Oral Grade: <strong>@if (!is_null($element->Oral)) {{ $element->Oral }} @endif</strong>
						  </p>
						  <p>
							Overall Grade: <strong>@if (!is_null($element->Overall_Grade)) {{ $element->Overall_Grade }} @endif</strong>
						  </p>
						  @if(!empty($element->classrooms))
		                  <br> 
		                  	@if($element->classrooms->Tch_ID == 'TBD')
		                  	@elseif(empty($element->classrooms->Tch_ID))
		                    @else
		                    <!-- <form method="POST" action="{{ route('cancel-convocation', [$element->CodeIndexIDClass]) }}" class="form-prevent-multi-submit">
		                        <input type="submit" value="@if($element->deleted_at) Cancelled @else Cancel Enrolment @endif" class="btn btn-danger btn-space button-prevent-multi-submit" @if($element->deleted_at) disabled="" @else @endif>
		                        {{-- name="deleteTerm" attribute for LimitCancelPeriod middleware --}}
		                        <input type="hidden" name="deleteTerm" value="{{ $element->Term }}">
		                        <input type="hidden" name="_token" value="{{ Session::token() }}">
		                       {{ method_field('DELETE') }}
		                    </form> -->

		                    <button type="button" class="btn btn-danger btn-space pash-delete" data-toggle="modal" data-id="{{ $element->id }}" @if($element->deleted_at) disabled="" @endif> @if($element->deleted_at)<i class="fa fa-remove"></i> Cancelled @else <i class="fa fa-trash"></i> Cancel Enrolment @endif</button>

							<div id="modalDeletePash-{{ $element->id }}" class="modal fade" role="dialog">
							  <div class="modal-dialog">
							      <div class="modal-content">

							          <div class="modal-header bg-danger">
							              <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="text: white;">&times;</button>
							              <h3 class="modal-title">Class Cancellation</h3>
							          </div>
							          <div class="modal-body-pash-delete">
							            <div class="col-sm-12">

							              <form method="POST" action="{{ route('cancel-convocation', [$element->CodeIndexIDClass]) }}" class="delete-form form-prevent-multi-submit">

							                  <h4>Index # {{ $element->INDEXID }} : <strong> {{ $element->users->name }}</strong></h4>
							                  <h4>Cancelling participation from <strong> {{ $element->courses->Description }}</strong></h4>
							                  
							                  <div class="form-group">
							                    <h4><input type="checkbox" name="cancelled_but_not_billed" value=1> Student will <strong class="text-danger"><u>NOT</u></strong> be billed</h4>
							                  </div>

							                  <input type="submit" value="@if($element->deleted_at) Cancelled @else Delete @endif" class="delete-form btn btn-danger btn-space button-prevent-multi-submit" @if($element->deleted_at) disabled="" @else @endif>

							                  <input type="hidden" name="deleteTerm" value="{{ $element->Term }}">
							                  <input type="hidden" name="_token" value="{{ Session::token() }}">
							                 {{ method_field('DELETE') }}
							              </form>

							            </div>
							          </div>
							          <div class="modal-footer modal-background">
							            
							          </div>
							      
							      </div>
							  </div>
							</div>

		                    @endif
						   @endif
		                  @endforeach
		                </p>
		              </div>
		      </div>
		  </div>