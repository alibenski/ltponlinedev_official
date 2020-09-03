@extends('layouts.email.email')

@section('preheader')
    Teacher - Assigned CLM Language Course
@stop

@section('content')
    <!-- Email Body : BEGIN -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
        <!-- 1 Column Text + Button : BEGIN -->
        <tr>
            <td bgcolor="#ffffff">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; text-align: justify;">
                            <h1 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 24px; line-height: 125%; color: #333333; font-weight: normal; text-align: center;">CLM Online Enrolment</h1>
                            <p>Dear colleagues, </p>
                            <p>We are pleased to announce that the convocations for the {{ $selectedTerm->Comments }} courses have been sent. Please refer to the list below for the names of your course channels in MS Teams:</p>
                            <p> 
                            	@foreach ($languages as $value)
                            	<h3>{{$value->name}}</h3> 
                            		@foreach ($queryTeachers as $e)
	                            		@if($e->Tch_L === $value->code)
										<p>{{ $e->Tch_Name }}:</p>
											@foreach ($e->classrooms as $element)
											<p style="margin-left: 50px">
			                            		LTP-{{ $selectedTerm->Term_Code }}-{{ $e->Tch_ID }}-{{$element->course->Description}}-{{$element->Te_Code_New}}:{{$element->scheduler->time_combination}} 
											</p>
											@endforeach
										@endif
                            		@endforeach
                            	@endforeach
                        </td>
                    </tr>

                    @include('layouts.email.partials._emailFooterEn')
                    
                    {{-- <tr>
                        <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; text-align: justify;">
                            <h1 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 24px; line-height: 125%; color: #333333; font-weight: normal; text-align: center;">Inscription en ligne du CFM</h1>
                             <p> Chère / cher {{ $staff_name }}, </p>
                             <p> Vous avez <strong>annulé</strong> votre @if($type === 0) inscription au cours de language du CFM suivant : @endif <strong>{{ $display_language_fr }}</strong></p>
                            
                            @if($type === 0)
                             <p>Horaires annulés : </p>
                            
                             <ul>
                                 {{ $schedule }}
                             </ul>
                             @endif
                        </td>
                    </tr>

                    @include('layouts.email.partials._emailFooterFr') --}}
                   
                </table>
            </td>
        </tr>
        <!-- 1 Column Text + Button : END -->
@stop