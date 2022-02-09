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
                            {!! $text->text !!}
                        </td>
                    </tr>
                    <br>
                </table>   
            </td>
        </tr>
    </table>
    @include('layouts.email.partials._emailFooterEn')
@stop