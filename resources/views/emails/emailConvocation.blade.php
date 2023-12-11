<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <!-- Web Font / @font-face : BEGIN -->
    <!-- NOTE: If web fonts are not required, lines 10 - 27 can be safely removed. -->

    <!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
    <!--[if mso]>
        <style>
            * {
                font-family: sans-serif !important;
            }
        </style>
    <![endif]-->

    <!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->
    <!--[if !mso]><!-->
    <!-- insert web font reference, eg: <link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'> -->
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->

    <!-- CSS Reset : BEGIN -->
    <style>
        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }
        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }
        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }
        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto;
        }
        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
        }
        /* What it does: A work-around for email clients meddling in triggered links. */
        *[x-apple-data-detectors],  /* iOS */
        .x-gmail-data-detectors,    /* Gmail */
        .x-gmail-data-detectors *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
        /* What it does: Prevents Gmail from displaying an download button on large, non-linked images. */
        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }
        /* If the above doesn't work, add a .g-img class to any image in question. */
        img.g-img + div {
            display: none !important;
        }
        /* What it does: Prevents underlining the button text in Windows 10 */
        .button-link {
            text-decoration: none !important;
        }
        /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
        /* Create one of these media queries for each additional viewport size you'd like to fix */
        /* Thanks to Eric Lepetit (@ericlepetitsf) for help troubleshooting */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) { /* iPhone 6 and 6+ */
            .email-container {
                min-width: 375px !important;
            }
        }
	    @media screen and (max-width: 480px) {
	        /* What it does: Forces Gmail app to display email full width */
	        u ~ div .email-container {
		        min-width: 100vw;
	        }
		}
    </style>
    <!-- CSS Reset : END -->

    <!-- Progressive Enhancements : BEGIN -->
    <style>
    /* What it does: Hover styles for buttons */
    .button-td,
    .button-a {
        transition: all 100ms ease-in;
    }
    .button-td:hover,
    .button-a:hover {
        background: #555555 !important;
        border-color: #555555 !important;
    }
    /* Media Queries */
    @media screen and (max-width: 600px) {
        /* What it does: Adjust typography on small screens to improve readability */
        .email-container p {
            font-size: 17px !important;
        }
    }
    </style>
    <!-- Progressive Enhancements : END -->

    <!-- What it does: Makes background images in 72ppi Outlook render at correct size. -->
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->

</head>
<body width="100%" bgcolor="#ffffff" style="margin: 0; mso-line-height-rule: exactly;">
{{-- <body width="100%" bgcolor="#4286f4" style="margin: 0; mso-line-height-rule: exactly;">
    <center style="width: 100%; background: #4286f4; text-align: left;"> --}}
    <center style="width: 100%; background: #ffffff; text-align: left;">

        <!-- Visually Hidden Preheader Text : BEGIN -->
        <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
            CLM Language Training Online Registration Platform
        </div>
        <!-- Visually Hidden Preheader Text : END -->

        <!--
            Set the email width. Defined in two places:
            1. max-width for all clients except Desktop Windows Outlook, allowing the email to squish on narrow but never go wider than 600px.
            2. MSO tags for Desktop Windows Outlook enforce a 600px width.
        -->
        <div style="max-width: 600px; margin: auto;" class="email-container">
            <!--[if mso]>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" align="center">
            <tr>
            <td>
            <![endif]-->

            <!-- Email Header : BEGIN -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
                <tr>
                    <td style="padding: 20px 0; text-align: center">
                        <img src="https://learning.unog.ch/sites/all/themes/sdlstheme/images/CLM-TextRight_En.jpg" width="260" height="93" alt="CLM Language Training" border="0" style="height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                    </td>
                </tr>
            </table>
            <!-- Email Header : END -->

            <!-- Email Body : BEGIN -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">

                <!-- Hero Image, Flush : BEGIN -->
{{--                 <tr>
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
                                <td style="padding: 15px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                    <h1 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 24px; line-height: 125%; color: #333333; font-weight: normal;"></h1>
                                    <p>Dear {{ $staff_name }}, </p>
                                    <p style="text-align: justify">
                                        We would like to inform you that you are successfully enrolled in a language course from <strong>{{ $term_en }}</strong>. Your course details are:
                                    </p>
                                    
                                    <p>
                                        <h3><strong>{{ $course_name_en }}</strong></h3>
                                        
                                        Schedule: <strong>{{$schedule}}</strong> 
                                        <br> 
                                        Teacher: <strong>{{ $teacher }}</strong> ({{ $teacher_email }})
                                        <br>
                                        <br>
                                        @foreach($classrooms as $classroom)
                                          @if(!empty($classroom->Te_Mon_Room))
                                          <p>Monday Room: <strong>{{ $classroom->roomsMon->Rl_Room }} </strong></p>
                                          {{-- <p>Monday Time: <strong>{{ date('H:i', strtotime($classroom->Te_Mon_BTime)) }} - {{ date('H:i', strtotime($classroom->Te_Mon_ETime ))}}</strong></p> --}}
                                          @endif
                                          @if(!empty($classroom->Te_Tue_Room))
                                          <p>Tuesday Room: <strong>{{ $classroom->roomsTue->Rl_Room }} </strong></p>
                                          {{-- <p>Tuesday Time: <strong>{{ date('H:i', strtotime($classroom->Te_Tue_BTime)) }} - {{ date('H:i', strtotime($classroom->Te_Tue_ETime)) }}</strong></p> --}}
                                          @endif
                                          @if(!empty($classroom->Te_Wed_Room))
                                          <p>Wednesday Room: <strong>{{ $classroom->roomsWed->Rl_Room }} </strong></p>
                                          {{-- <p>Wednesday Time: <strong>{{ date('H:i', strtotime($classroom->Te_Wed_BTime ))}} - {{ date('H:i', strtotime($classroom->Te_Wed_ETime)) }}</strong></p> --}}
                                          @endif
                                          @if(!empty($classroom->Te_Thu_Room))
                                          <p>Thursday Room: <strong>{{ $classroom->roomsThu->Rl_Room }} </strong></p>
                                          {{-- <p>Thursday Time: <strong>{{ date('H:i', strtotime($classroom->Te_Thu_BTime)) }} - {{ date('H:i', strtotime($classroom->Te_Thu_ETime ))}}</strong></p> --}}
                                          @endif
                                          @if(!empty($classroom->Te_Fri_Room))
                                          <p>Friday Room: <strong>{{ $classroom->roomsFri->Rl_Room }} </strong></p>
                                          {{-- <p>Friday Time: <strong>{{ date('H:i', strtotime($classroom->Te_Fri_BTime ))}} - {{ date('H:i', strtotime($classroom->Te_Fri_ETime)) }}</strong></p> --}}
                                          @endif
                                        @endforeach
                                    </p>
                                    
                                    <ul>
                                        <li style="text-align: justify"><strong>NEW:</strong> An <strong>orientation week</strong>, held one week before the start of the course, will enable you to familiarize yourself with the course learning tools, including the language learning platform, and meet your teacher and group.
                                        <br />
                                        During the orientation week, your teacher will email you with further instructions about what is expected from you before the course formally begins, the link to your course material, and autonomous activities on the CLM Language Learning Platform. 
                                        </li>
                                        <li style="text-align: justify">Some courses require materials. Please check the <a href="https://learning.unog.ch/node/1443">list of materials</a> necessary to purchase before your course starts.</li>
                                    </ul>
                                    <h4><strong><u>Important information for ONLINE courses</u></strong></h4>
                                    <p style="text-align: justify">Please note the equipment you will need: </p>
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
                                        <tr>
                                            <td style="padding: 20px 0; text-align: center">
                                                <img src="https://ltponlinedev.unog.ch/img/online_equip_icons.png" width="450" alt="CLM Language Training" border="0" style="height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                            </td>
                                        </tr>
                                    </table>
                                    <p style="text-align: justify">We will use Microsoft Teams as the teleconferencing tool for your virtual course sessions taking place at the time indicated.  Your teacher will email you further details about how to join these classes during the orientation week.</p>
                                    
                                    <h4><strong><u>Important information for IN-PERSON courses</u></strong></h4>
                                    <p style="text-align: justify">- <a href="https://learning.unog.ch/sites/default/files/ContainerEn/LTP/Admin/Bocage_Plan.pdf">Map of Palais des Nations, Annex Bocage (location of classrooms)</a> <br /> - <a href="https://learning.unog.ch/node/1446">Grounds pass and/or parking badge for vehicles</a></p>

                                    <h4><strong><u>Unable to attend the first week of the course? </u></strong></h4>
                                    <p style="text-align: justify">Please email your teacher before the course starts. Participants who do not attend class during the first week may be disenrolled from the course and their seat given to participants on a waiting list.</p>

                                    <h4><strong><u>You need to cancel your enrolment? </u></strong></h4>
                                    <p style="text-align: justify">You need to do so before {{ $cancel_date_limit_string }} 11:59 p.m. No course fees will be reimbursed after this date. 
                                        <ul>
                                            <li style="text-align: justify">
                                                To cancel, please <a href="https://ltponlinedev.unog.ch/previous-submitted">click here</a>. You then need to select the appropriate term and click the red button “Cancel Enrolment” (<a href="https://learning.unog.ch/node/1301#position7">more info</a>).
                                            </li>
                                            <li style="text-align: justify">
                                                To have your fees reimbursed, please <a href="https://learning.unog.ch/node/1301#position5">click here</a>.
                                            </li>
                                        </ul>
                                    </p>
                                    
                                    <p style="text-align: justify"><em>A technical reason for not cancelling on time will not be considered valid for reimbursement nor a reason for not charging your organization. Thank you for your understanding.</em></p>

                                    <p style="text-align: justify">If you have any questions, please visit our <a href="https://learning.unog.ch/node/1301#position8">FAQs</a>.</p>
                                    {{-- <ol>
                                        <li>
                                            <strong>For courses delivered online:</strong>
                                        </li>
                                        <p style="text-align: justify">
                                        <b>Microsoft Teams</b> <br>
                                        We will use Microsoft Teams as the teleconferencing tool for the virtual sessions that will take place at the time indicated above. Further details about how to join your session will be sent by your teacher before the beginning of the term.
                                        </p>
                                        <p style="text-align: justify">
                                        <b>IT requirements</b> <br>
                                        <ul>
                                            <li style="text-align: justify">
                                                Ensure that you have a camera, headphones and a microphone.
                                            </li>
                                            <li style="text-align: justify">
                                                Test the connection before the course. 
                                            </li>
                                            <li style="text-align: justify">
                                                Contact your Organization’s IT department if you have any problems.
                                            </li>
                                            <li style="text-align: justify">
                                                When attending the course, close all other applications and files. This will ensure that you focus on the content of the course as if you were attending a face-to-face course and will not overload the bandwidth.
                                            </li>
                                        </ul>
                                        </p>

                                        <li>
                                            <strong>For all courses, online and in-person:</strong> 
                                        </li>
                                        <p style="text-align: justify">
                                        <b><a href="https://moodle.unog.ch/unog/login/index.php">Moodle</a></b> <br>
                                        <ul>
                                            <li style="text-align: justify">
                                                <u>Once notified by your teacher</u>, you will find your learning material and autonomous activities on the CLM e-learning Moodle platform at <a href="https://moodle.unog.ch/unog/login/index.php">https://moodle.unog.ch/unog/login/index.php</a>. Before you start your class, make sure to log in to your course to familiarize yourself with the content and access the training materials.
                                            </li>
                                            <li style="text-align: justify">
                                                If you don’t have a Moodle access yet, please use the following credentials to log in:
                                                <br />- Username: first part before the @  of your email address (ex. “psmith” is the username for psmith@un.org) 
                                                <br />- Default password: Welcome2U_2022# (you will be asked to change it when you log in)
                                            </li>
                                        </ul>
                                        </p>
                                    </ol> --}}
                                    <br /> 
                                    <p style="text-align: justify">
                                        The Language Training Programme wishes you a rich learning experience.
                                    </p>
                                    <br>
                                    
                                    <p style="text-align: justify">
                                        To find out more, you can visit our website at <a href="https://learning.unog.ch/language-index">https://learning.unog.ch/language-index</a>
                                    </p>
                                    <hr>
                                </td>
                            </tr>

                            <tr>
                                @include('emails.emailConvocationFr')
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- 1 Column Text + Button : END -->

                <!-- Clear Spacer : BEGIN -->
                <tr>
                    <td aria-hidden="true" height="20" style="font-size: 0; line-height: 0;">
                        <hr>
                    </td>
                </tr>
                <!-- Clear Spacer : END -->

                <!-- 1 Column Text : BEGIN -->
                <tr>
                    <td bgcolor="#ffffff">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="padding: 15px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                    <p style="margin: 0;text-align:left;"><strong>The Language Training Programme at the United Nations Office at Geneva</strong></p> 
                                    <p style="margin: 0;text-align: justify;">We believe in multilingualism and multiculturalism as key elements of mutual understanding in a global context. To meet this goal, we offer language courses in the six official languages of the United Nations (Arabic, Chinese, English, French, Russian and Spanish).</p>
                                    <br>
                                    <p style="margin: 0;text-align:left;"><strong>Le Programme de formation linguistique à l'Office des Nations Unies à Genève</strong></p> 
                                    <p style="margin: 0;text-align: justify;">Nous croyons au multilinguisme et au multiculturalisme en tant qu'éléments clés de la compréhension mutuelle dans un contexte mondial. À cette fin, nous proposons des cours de langues dans les six langues officielles des Nations Unies (anglais, arabe, chinois, espagnol, français et russe).</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- 1 Column Text : END -->

            </table>
            <!-- Email Body : END -->

            <!-- Email Footer : BEGIN -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px; font-family: sans-serif; color: black; font-size: 12px; line-height: 140%;">
                <tr>
                    <td style="padding: 40px 10px; width: 100%; font-family: sans-serif; font-size: 12px; line-height: 140%; text-align: center; color: #222222;" class="x-gmail-data-detectors">
                        <webversion style="color: #222222; text-decoration: underline; font-weight: bold;"></webversion>
                        
                        <hr>
                        {{date("Y")}} All Rights Reserved. <br><br>
                        <unsubscribe style="color: #222222; text-decoration: underline;"></unsubscribe>
                    </td>
                </tr>
            </table>
            <!-- Email Footer : END -->

            <!--[if mso]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </div>

        <!-- Full Bleed Background Section : BEGIN 
        <table role="presentation" bgcolor="#709f2b" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
            <tr>
                <td valign="top" align="center">
                    <div style="max-width: 600px; margin: auto;" class="email-container">
                        <!--[if mso]>
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" align="center">
                        <tr>
                        <td>
                        <![endif]-->
        <!--                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="padding: 40px; text-align: left; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #ffffff;">
                                    <p style="margin: 0;">Maecenas sed ante pellentesque, posuere leo id, eleifend dolor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent laoreet malesuada cursus. Maecenas scelerisque congue eros eu posuere. Praesent in felis ut velit pretium lobortis rhoncus ut&nbsp;erat.</p>
                                </td>
                            </tr>
                        </table>
                        <!--[if mso]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
        <!--             </div>
                </td>
            </tr>
        </table>
        Full Bleed Background Section : END -->

    </center>
</body>
</html>