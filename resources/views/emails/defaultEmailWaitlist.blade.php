@extends('layouts.email.email')

@section('preheader')
    Waiting List Notification
@stop

@section('content')
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
        <tr>
            <td bgcolor="#ffffff">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; text-align: left;">
                            <p>Dear <strong>{{ $name }}</strong>,</p> 
                            <p style="text-align: justify">
                                This is to inform you that you are currently on a waiting list for a place in our Language Training Programme. 
                            </p> 
                            <p> You are waitlisted to this course: 
                                 <strong> {{ $course }} </strong>
                            </p>
                            <p style="text-align: justify">
                                You will be notified as soon as a place becomes available at your level within the first two weeks of the term.
                            </p>
                            <p style="text-align: justify">
                            For more information regarding the waiting list please visit our website at <a href="https://learning.unog.ch/node/1301#position6" target="_blank">https://learning.unog.ch/node/1301#position6</a>
                            </p>
                            <p style="text-align: justify">
                            Thank you for your interest in our programme.
                            </p>
                        </td>
                    </tr>
                    <br>
                </table>   
            </td>
        </tr>
    </table>
       
    @include('layouts.email.partials._emailFooterEn')
@stop