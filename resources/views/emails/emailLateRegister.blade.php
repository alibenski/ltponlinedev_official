@extends('layouts.email.email')

@section('preheader')
    Student Registration Email
@stop

@section('content')
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
        <tr>
            <td bgcolor="#ffffff">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; text-align: left;">
                            <h1 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 24px; line-height: 125%; color: #333333; font-weight: normal; text-align: center;">CLM Online Enrolment</h1>
                            <p> Hello! Thank you for your interest in our language training programme. </p>
                        <p> Please click the button below to register your online registration user account.</p>
                            <p> Note: The link will expire in 24 hours.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                            <!-- Button : BEGIN -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto;">
                                <tr>
                                    <td style="border-radius: 3px; background: #222222; text-align: center;" class="button-td">
                                        <a href="{{ $url }}" style="background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 110%; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
                                            <span style="color:#ffffff;" class="button-link">&nbsp;&nbsp;&nbsp;&nbsp;Click Here&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <!-- Button : END -->
                        </td>
                    </tr>
                    <br>
                    @include('layouts.email.partials._emailFooterEn')
@stop