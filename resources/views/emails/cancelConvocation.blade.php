@extends('layouts.email.email')

@section('preheader')
    Cancellation of CLM language course.
@stop

@section('content')
    <!-- Email Body : BEGIN -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">

        <!-- Hero Image, Flush : BEGIN -->
        {{--<tr>
            <td bgcolor="#ffffff" align="center">
                <img src="http://placehold.it/1200x600" width="600" height="" alt="alt_text" border="0" align="center" style="width: 100%; max-width: 600px; height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; margin: auto;" class="g-img">
            </td>
        </tr> --}}
        <!-- Hero Image, Flush : END -->

        <!-- 1 Column Text + Button : BEGIN -->
        <tr>
            <td bgcolor="#ffffff">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; text-align: justify;">
                            <h1 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 24px; line-height: 125%; color: #333333; font-weight: normal; text-align: center;">CLM Online Enrolment</h1>
                            <p> Dear {{ $staff_name }}, </p>
                            <p> You have cancelled your @if($type === 0) enrolment for CLM language course: @endif <strong>{{ $display_language_en }}</strong></p>
                            
                            @if($type === 0) 
                            <p>Cancelled Schedule:</p>
                            
                                <ul>
                                    {{ $schedule }}
                                </ul>
                            @endif

                            <p>
                                If you are a self-paying student, please refer to <a href="https://learning.unog.ch/node/1301#position5">this link</a> to check if you are eligible for reimbursement and its modalities. 
                            </p>
                        </td>
                    </tr>

                    @include('layouts.email.partials._emailFooterEn')
                    
                    <tr>
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

                             <p>
                                Si vous avez payé des frais de cours, vous pouvez consulter <a href="https://learning.unog.ch/fr/node/1301#position5">le lien suivant</a> pour savoir si vous remplissez les conditions de remboursement et quelles en sont les modalités. 
                            </p>
                        </td>
                    </tr>

                    @include('layouts.email.partials._emailFooterFr')
                    <!-- <tr>
                        <td style="padding: 0 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                            <!-- Button : BEGIN
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto;">
                                <tr>
                                    <td style="border-radius: 3px; background: #222222; text-align: center;" class="button-td">
                                        <a href="#" style="background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 110%; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
                                            <span style="color:#ffffff;" class="button-link">&nbsp;&nbsp;&nbsp;&nbsp;Approval Link&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <!-- Button : END
                        </td>
                    </tr> -->
                    <!-- <tr>
                        <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                            <h2 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 18px; line-height: 125%; color: #333333; font-weight: bold;">Important Reminder</h2>
                            <p style="margin: 0;">Please note that the class schedules are not absolute and there is a possibility that they could change upon further evaluation of the Language Secretariat.</p>
                        </td>
                    </tr> -->
                </table>
            </td>
        </tr>
        <!-- 1 Column Text + Button : END -->

        <!-- 2 Even Columns : BEGIN 
        <tr>
            <td bgcolor="#ffffff" align="center" height="100%" valign="top" width="100%" style="padding-bottom: 40px">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="max-width:560px;">
                    <tr>
                        <td align="center" valign="top" width="50%">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size: 14px;text-align: left;">
                                <tr>
                                    <td style="text-align: center; padding: 0 10px;">
                                        <img src="http://placehold.it/200" width="200" height="" alt="alt_text" border="0" align="center" style="width: 100%; max-width: 200px; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; padding: 10px 10px 0;" class="stack-column-center">
                                        <p style="margin: 0;">Maecenas sed ante pellentesque, posuere leo id, eleifend dolor. Class aptent taciti sociosqu ad litora per conubia nostra, per torquent inceptos&nbsp;himenaeos.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td align="center" valign="top" width="50%">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size: 14px;text-align: left;">
                                <tr>
                                    <td style="text-align: center; padding: 0 10px;">
                                        <img src="http://placehold.it/200" width="200" height="" alt="alt_text" border="0" align="center" style="width: 100%; max-width: 200px; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; padding: 10px 10px 0;" class="stack-column-center">
                                        <p style="margin: 0;">Maecenas sed ante pellentesque, posuere leo id, eleifend dolor. Class aptent taciti sociosqu ad litora per conubia nostra, per torquent inceptos&nbsp;himenaeos.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- Two Even Columns : END -->
@stop
