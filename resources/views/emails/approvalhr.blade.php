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
    <center style="width: 100%; background: #ffffff; text-align: left;">

        <!-- Visually Hidden Preheader Text : BEGIN -->
        <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
            {{ $staff_name }} would like to enrol in a CLM language course and needs your approval.
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
                        <img src="https://learning.unog.ch/sites/all/themes/sdlstheme/images/CLM-TextRight_En.jpg" width="260" height="93" alt="alt_text" border="0" style="height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
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
                                <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                    
                                     <p> Dear CLM Learning Partner, </p>
                                     <p> {{ $staff_name }} would like to enrol on a CLM language course: <strong> {{ $input_course->courses->Description }} ({{ $term_en }} {{ $term_season_en }})</strong></p>
                                     <p> The student chose the following options for class schedule(s):</p>
                                     <ol>
                                     @foreach($formItems as $form)   
                                     <li><strong>{{ $form->schedule->name }}</strong> 
                                        {{-- : 
                                        <strong>
                                        @if( $form->approval == 1)
                                                Approved 
                                        @else
                                                Disapproved
                                        @endif   
                                        </strong> --}}
                                     </li>
                                     @endforeach
                                     </ol>
                                     {{-- <p>Please note that the enrolment above has received initial approval  from the staff member's supervisor.</p> --}}
                                     <p>Please click on the button below to access the validation page.</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                    <!-- Button : BEGIN -->
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto;">
                                        <tr>
                                            <td style="border-radius: 3px; background: #222222; text-align: center;" class="button-td">
                                                <a href="{{ route('approval.getform2hr', [
                                                    Crypt::encrypt($input_course->id), 
                                                    Crypt::encrypt($input_course->Term)
                                                    {{-- Crypt::encrypt($input_course->INDEXID),  --}}
                                                    {{-- Crypt::encrypt($input_course->Te_Code),  --}}
                                                    {{-- Crypt::encrypt($input_course->form_counter),  --}}
                                                    ]) }}" style="background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 110%; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
                                                    <span style="color:#ffffff;" class="button-link">&nbsp;&nbsp;&nbsp;&nbsp;Click Here&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- Button : END -->
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                    
                                     <p> Chère ou cher Partenaire de formation, </p>
                                     <p> {{ $staff_name }}, voudrait s’inscrire au cours de langue du Centre de formation et de multilinguisme suivant : <strong> {{ $input_course->courses->FDescription }} ({{ $term_fr }} {{ $term_season_fr }}) </strong></p>
                                     <p> L'étudiant(e) a indiqué comme options possibles les horaires suivants :</p>
                                     <ol>
                                     @foreach($formItems as $form)   
                                     <li><strong>{{ $form->schedule->name }}</strong> 
                                        {{-- :  
                                        <strong>
                                        @if( $form->approval == 1)
                                                approuvé 
                                        @else
                                                désapprouvé
                                        @endif   
                                        </strong> --}}
                                     </li>
                                     @endforeach
                                     </ol>
                                     {{-- <p>Les choix d’horaires ci-dessus ont été initialement approuvés par le ou la superviseur(e).</p> --}}
                                     <p>Veuillez cliquer sur le bouton ci-dessus pour avoir accès à la page de validation.</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                    <h2 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 18px; line-height: 125%; color: #333333; font-weight: bold;">Disclaimer</h2>
                                    <p style="margin: 0;">Please note that the class schedules are not fixed and there is a possibility that they could change when the Language Training Programme secretariat finalises the programme for the term.</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                    <h2 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 18px; line-height: 125%; color: #333333; font-weight: bold;">Avertissement </h2>
                                    <p style="margin: 0;">Veuillez noter la possibilité que les horaires des cours changent suite à une modification du secrétariat du Programme de formation linguistique.</p>
                                </td>
                            </tr>
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

                <!-- Clear Spacer : BEGIN -->
                <tr>
                    <td aria-hidden="true" height="40" style="font-size: 0; line-height: 0;">
                        &nbsp;
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
                                    <p style="margin: 0;text-align: left;">We believe in multilingualism and multiculturalism as key elements of mutual understanding in a global context. To meet this goal, we offer language courses in the six official languages of the United Nations (Arabic, Chinese, English, French, Russian and Spanish).</p>
                                    <br>
                                    <p style="margin: 0;text-align:left;"><strong>Le Programme de formation linguistique à l'Office des Nations Unies à Genève</strong></p> 
                                    <p style="margin: 0;text-align: left;">Nous croyons au multilinguisme et au multiculturalisme en tant qu'éléments clés de la compréhension mutuelle dans un contexte mondial. À cette fin, nous proposons des cours de langues dans les six langues officielles des Nations Unies (anglais, arabe, chinois, espagnol, français et russe).</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- 1 Column Text : END -->

            </table>
            <!-- Email Body : END -->

            <!-- Email Footer : BEGIN -->
            @include('emails._partials.emailConvoFooter')
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