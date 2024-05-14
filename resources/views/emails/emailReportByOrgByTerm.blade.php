@extends('layouts.email.email')

@section('preheader')
    Student Registration Email
@stop

@section('content')
 <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
        <!-- 1 Column Text + Button : BEGIN -->
        <tr>
            <td bgcolor="#ffffff">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; text-align: justify;">
                            
                            <p>Dear colleague,</p>
    
                            <p>Please find the <b>{{ $org }}</b> language course participants list for the <b>{{ $term_year_string}}</b> <b>{{ $term_name_string }} term billing</b>, by clicking the link below.</p>
    
                            <p>For information, the deadline for cancellation was the <b>{{ $cancel_date_limit_string }}</b>. Cancellations after the deadline are charged to your organization.</p>
    
                            <p>Please ensure to review the list and let us know any inconsistencies by replying to clm_language@un.org by <b>{{$deadline}}</b>. 
                            Kindly be advised that if no response is received by the above deadline, the list will be considered correct and we will proceed with the billing accordingly. </p>
    
                            <p>Thank you in advance for your answer.</p>
                            <p>Click the button below to access the CLM language course participant list for billing.</p>
                            <br />    
                            
                            <a href="{{ route('report-by-org', [Crypt::encrypt($param), Crypt::encrypt($org), Crypt::encrypt($term), Crypt::encrypt($year)]) }}" style="background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 110%; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
                                <span style="color:#ffffff;" class="button-link">&nbsp;&nbsp;&nbsp;&nbsp;Click Here &nbsp;&nbsp;&nbsp;&nbsp;</span></a>
    
                            <br />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
 </table>
@stop