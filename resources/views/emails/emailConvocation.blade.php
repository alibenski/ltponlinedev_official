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
                                        We have the pleasure to inform you that you are successfully registered in a language class for the coming term from <strong>{{ $term_en }}</strong>. (Monday 18 April is a United Nations holiday, no courses will take place that day). Please see below the information about your course:
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
                                          <p>Monday Room: <strong>{{ $classroom->roomsMon->Rl_Room }} *</strong></p>
                                          {{-- <p>Monday Time: <strong>{{ date('H:i', strtotime($classroom->Te_Mon_BTime)) }} - {{ date('H:i', strtotime($classroom->Te_Mon_ETime ))}}</strong></p> --}}
                                          @endif
                                          @if(!empty($classroom->Te_Tue_Room))
                                          <p>Tuesday Room: <strong>{{ $classroom->roomsTue->Rl_Room }} *</strong></p>
                                          {{-- <p>Tuesday Time: <strong>{{ date('H:i', strtotime($classroom->Te_Tue_BTime)) }} - {{ date('H:i', strtotime($classroom->Te_Tue_ETime)) }}</strong></p> --}}
                                          @endif
                                          @if(!empty($classroom->Te_Wed_Room))
                                          <p>Wednesday Room: <strong>{{ $classroom->roomsWed->Rl_Room }} *</strong></p>
                                          {{-- <p>Wednesday Time: <strong>{{ date('H:i', strtotime($classroom->Te_Wed_BTime ))}} - {{ date('H:i', strtotime($classroom->Te_Wed_ETime)) }}</strong></p> --}}
                                          @endif
                                          @if(!empty($classroom->Te_Thu_Room))
                                          <p>Thursday Room: <strong>{{ $classroom->roomsThu->Rl_Room }} *</strong></p>
                                          {{-- <p>Thursday Time: <strong>{{ date('H:i', strtotime($classroom->Te_Thu_BTime)) }} - {{ date('H:i', strtotime($classroom->Te_Thu_ETime ))}}</strong></p> --}}
                                          @endif
                                          @if(!empty($classroom->Te_Fri_Room))
                                          <p>Friday Room: <strong>{{ $classroom->roomsFri->Rl_Room }} *</strong></p>
                                          {{-- <p>Friday Time: <strong>{{ date('H:i', strtotime($classroom->Te_Fri_BTime ))}} - {{ date('H:i', strtotime($classroom->Te_Fri_ETime)) }}</strong></p> --}}
                                          @endif
                                        @endforeach
                                    </p>
                                    <br>
                                    <p style="text-align: justify">
                                        * <span style="color: red;">Important note: Due to the situation related to COVID-19, the term will start remotely, and the face-to-face component will be delivered online. Should the situation evolve during the term, the delivery mode might change taking into account local health and safety conditions, except for courses advertised as online that will remain online.</span>
                                    </p>
                                        
                                    <p style="text-align: justify">
                                        Please note that the group size will be exceptionally reduced from 15 to 12 or 13 participants in most courses to offer you better learning conditions. This is not applicable to courses initially advertised as online.
                                    </p>

                                    <p style="text-align: justify">
                                        How are we going to interact and which tools are we going to use?
                                    </p>
                                    <ol>
                                        <li>
                                            <p style="text-align: justify">
                                            <b>Microsoft Teams</b> <br>
                                            We will use Microsoft Teams as the teleconferencing tool to replace your face-to-face sessions. Virtual sessions will take place at the time indicated above. Further details about how to join your session will be sent by your teacher before the beginning of the term.
                                            </p>
                                        </li>

                                        <li>
                                            <p style="text-align: justify">
                                            <b><a href="https://moodle.unog.ch/unog/login/index.php">Moodle</a></b> <br>
                                            <ul>
                                                <li style="text-align: justify">
                                                    <u>Once notified by your teacher</u>, you will find your learning material and autonomous activities on the CLM e-learning Moodle platform at <a href="https://moodle.unog.ch/unog/login/index.php">https://moodle.unog.ch/unog/login/index.php</a>. Before you start your class, make sure to log in to your course to familiarize yourself with the content and access the training materials.
                                                </li>
                                                {{-- <li style="text-align: justify">
                                                    Update your profile by adding a picture in your profile.
                                                </li> --}}
                                                {{-- <li style="text-align: justify">
                                                    If you are new to the Language Training Programme and don’t have your Moodle access yet, you will receive your credentials and the log-in instructions by email before your first class. 
                                                </li> --}}
                                                <li style="text-align: justify">
                                                    If you don’t have a Moodle access yet, please use the following credentials to log in:
                                                    <br />- Username: first part before the @  of your email address (ex. “psmith” is the username for psmith@un.org) 
                                                    <br />- Default password: Welcome2U_2022# (you will be asked to change it when you log in)
                                                </li>
                                            </ul>
                                            </p>
                                        </li>

                                        <li>
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
                                        </li>

                                        <li>
                                            <p style="text-align: justify">
                                            <b>Books and training materials:</b> please find <a href="https://learning.unog.ch/node/1443">here the list of books and materials</a> that you need to acquire before your class starts
                                            </p>
                                        </li>
                                    </ol>

                                    {{-- <p style="text-align: justify">
                                        The mode of communication to use with your teacher outside of your class time is email. The teachers are working very hard, but they also need time for themselves and/or their families; therefore, please be aware that they are not available 24/7. Thank you for your understanding. 
                                    </p> --}}
                                    {{-- <p style="text-align: justify">
                                        If you encounter any issue with your registration in the above mentioned course, please fill the <a href="https://learning.unog.ch/contact-us">contact form</a>. 
                                    </p> --}}

                                    <!--<p style="text-align: justify">
                                        Please read carefully the <strong>information before start of term</strong> on our website (<a href="https://learning.unog.ch/node/1472">click here</a>).
                                    </p>-->
                                    
                                    {{-- <p style="text-align: justify">
                                        If you encounter any issue with your registration in the above mentioned course, please contact the Language Training secretariat at <a href="mailto: clm_language@un.org">clm_language@un.org</a>. 
                                    </p> --}}
                                    <p style="text-align: justify">
                                        <u>If you are unable to attend the first week of the term</u>, you must inform your teacher by email before the term starts. Participants who do not attend class during the first week of the term may be disenrolled from the course and their seat given to participants who were placed on a waiting list.
                                    </p>
                                    <p style="color: red;text-align: justify;">
                                        Should you need to cancel your enrolment (or one of them in case of registering in two courses), you must do so before {{ $cancel_date_limit_string }} 11:59 p.m. No course fees will be refunded after this date. For fees reimbursement, please click <a href="https://learning.unog.ch/node/1301#position5">here</a>.
                                    </p>
                                    <p style="text-align: justify"> 
                                        To cancel, log into the platform <a href="https://clmlanguageregistration.unog.ch">https://clmlanguageregistration.unog.ch</a>, go to “Submitted Forms”, select the appropriate term and click ”View Forms” to display your applications. Click the red button “Cancel Enrolment” on the registration form you wish to cancel.
                                    </p>
                                    <p style="text-align: justify">
                                        A technical reason for not having been able to cancel on time will not be considered as a valid reason for re-imbursement nor a reason for not charging your organization. Thank you for your understanding.
                                    </p>
                                    {{-- <p style="margin: 0;text-align: justify;">
                                        If you have any question please contact us at: <a href="mailto: clm_language@un.org">clm_language@un.org</a>.
                                    </p>    --}}
                                    <p style="margin: 0;text-align: justify;">
                                        If you have any question, please visit our <a href="https://learning.unog.ch/node/1301#position8">FAQs</a>. 
                                    </p>   
                                    <br> 
                                    <p>
                                        The Language Training Programme wishes you a rich learning experience next term.
                                    </p>
                                    <br><br>
                                    {{-- <p>
                                        <h4><strong>Language Training Secretariat</strong></h4>
                                        Annex Bocage 2 - Room 5 (ground floor) - we are available via email or on the phone<br>
                                        <br>
                                        Opening hours : 9:00-12:30 from Monday to Friday.<br>
                                        Telephone: 00 41 22 917 44 09<br><br>
                                        <a href="https://learning.unog.ch/">https://learning.unog.ch/</a>
                                    </p> --}}
                                    <p>
                                        To know more, visit our website at <a href="https://learning.unog.ch/language-index">https://learning.unog.ch/language-index</a>
                                    </p>
                                    <hr>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 15px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">    
                                    <p>Cher / Chère {{ $staff_name }},</p>
                                    <p style="text-align: justify">
                                        Nous avons le plaisir de vous informer que vous êtes inscrit(e) avec succès à un cours de langue pour le trimestre prochain du <strong>{{ $term_fr }}</strong> (le lundi 18 avril étant un jour férié aux Nations Unies, aucun cours n’aura lieu ce jour-là). Voici ci-dessous les informations relatives à votre cours :
                                    </p>
                                    
                                    <p>
                                        <h3><strong>{{ $course_name_fr }}</strong></h3>

                                        Horaire : <strong>{{$schedule}}</strong> 
                                        <br> 
                                        Professeur : <strong>{{ $teacher }}</strong> ({{ $teacher_email }})
                                        <br> 
                                        <br> 
                                        @foreach($classrooms as $classroom)
                                          @if(!empty($classroom->Te_Mon_Room))
                                          <p>Salle du lundi : <strong>{{ $classroom->roomsMon->Rl_Room }} *</strong></p>
                                          {{-- <p>horaire lundi : <strong>{{ date('H:i', strtotime($classroom->Te_Mon_BTime)) }} - {{ date('H:i', strtotime($classroom->Te_Mon_ETime ))}}</strong></p> --}}
                                          @endif
                                          @if(!empty($classroom->Te_Tue_Room))
                                          <p>Salle du mardi : <strong>{{ $classroom->roomsTue->Rl_Room }} *</strong></p>
                                          {{-- <p>horaire mardi : <strong>{{ date('H:i', strtotime($classroom->Te_Tue_BTime)) }} - {{ date('H:i', strtotime($classroom->Te_Tue_ETime)) }}</strong></p> --}}
                                          @endif
                                          @if(!empty($classroom->Te_Wed_Room))
                                          <p>Salle du mercredi : <strong>{{ $classroom->roomsWed->Rl_Room }} *</strong></p>
                                          {{-- <p>horaire mercredi : <strong>{{ date('H:i', strtotime($classroom->Te_Wed_BTime ))}} - {{ date('H:i', strtotime($classroom->Te_Wed_ETime)) }}</strong></p> --}}
                                          @endif
                                          @if(!empty($classroom->Te_Thu_Room))
                                          <p>Salle du jeudi : <strong>{{ $classroom->roomsThu->Rl_Room }} *</strong></p>
                                          {{-- <p>horaire jeudi : <strong>{{ date('H:i', strtotime($classroom->Te_Thu_BTime)) }} - {{ date('H:i', strtotime($classroom->Te_Thu_ETime ))}}</strong></p> --}}
                                          @endif
                                          @if(!empty($classroom->Te_Fri_Room))
                                          <p>Salle du vendredi : <strong>{{ $classroom->roomsFri->Rl_Room }} *</strong></p>
                                          {{-- <p>horaire vendredi : <strong>{{ date('H:i', strtotime($classroom->Te_Fri_BTime ))}} - {{ date('H:i', strtotime($classroom->Te_Fri_ETime)) }}</strong></p> --}}
                                          @endif
                                        @endforeach
                                    </p>
                                    <br>
                                    <p style="text-align: justify">
                                        * <span style="color: red;">Remarque importante : En raison de la situation liée à COVID-19, le trimestre commencera à distance et la composante en face à face sera dispensée de façon virtuelle. Si la situation évolue au cours du trimestre, le mode d’enseignement pourrait être amené à changer suivant les règles locales de santé et de sécurité, sauf pour les cours annoncés comme étant dispensés en ligne qui resteront en ligne.</span>
                                    </p>
                                        
                                    <p style="text-align: justify">
                                        Veuillez noter que la taille des groupes sera exceptionnellement réduite de 15 à 12 -13 participants dans la plupart des cours afin de vous offrir de meilleures conditions d'apprentissage, sauf pour les cours annoncés comme étant en ligne.
                                    </p>

                                    <p style="text-align: justify">
                                        Comment allons-nous interagir et quels outils allons-nous utiliser ?
                                    </p>
                                    <ol>
                                        <li>
                                            <p style="text-align: justify">
                                            <b>Microsoft Teams</b> <br>
                                            Nous utiliserons Microsoft Teams comme outil de téléconférence pour remplacer vos sessions en face-à-face. Les sessions auront lieu au moment de votre cours, comme indiqué ci-dessus. Les professeurs vous enverront de plus amples informations sur la manière de participer à votre session avant le début du trimestre.
                                            </p>
                                        </li>

                                        <li>
                                            <p style="text-align: justify">
                                            <b><a href="https://moodle.unog.ch/unog/login/index.php">Moodle</a></b> <br>
                                            <ul>
                                                <li style="text-align: justify">
                                                    <u>Lorsque vous en serez informé(e) par votre professeur(e)</u>, vous trouverez votre matériel d'apprentissage et vos activités en autonomie sur la plateforme d'apprentissage Moodle du CFM : <a href="https://moodle.unog.ch/unog/login/index.php">https://moodle.unog.ch/unog/login/index.php</a>. Avant de commencer votre cours, assurez-vous de vous connecter à votre cours pour vous familiariser avec le contenu et accéder au matériel de formation.
                                                </li>
                                                {{-- <li style="text-align: justify">
                                                    Si vous êtes nouveau dans le programme de formation linguistique et que vous n'avez pas encore votre accès à Moodle, vous recevrez vos identifiants et les instructions de connexion par courrier électronique avant votre premier cours. 
                                                </li> --}}
                                                <li style="text-align: justify">
                                                    Si vous n'avez pas encore votre accès à Moodle, veuillez vous connecter avec les identifiants suivants :
                                                    <br />- Nom d’utilisateur : la première partie avant le @ de votre adresse email (ex. “psmith” pour psmith@un.org) 
                                                    <br />- Mot de passe par défaut : Welcome2U_2022# (vous devrez le modifier lors de la première connexion)
                                                </li>
                                            </ul>
                                            </p>
                                        </li>

                                        <li>
                                            <p style="text-align: justify">
                                            <b>Exigences informatiques</b> <br>
                                            <ul>
                                                <li style="text-align: justify">
                                                    Assurez-vous que vous avez une caméra, des écouteurs et un microphone.
                                                </li>
                                                <li style="text-align: justify">
                                                    Testez la connexion avant le cours. 
                                                </li>
                                                <li style="text-align: justify">
                                                    Contactez le département informatique de votre organisation si vous avez un problème.
                                                </li>
                                                <li style="text-align: justify">
                                                    Lorsque vous assistez au cours, fermez toutes les autres applications et tous les autres dossiers. Cela vous permettra de vous concentrer sur le contenu du cours comme si vous suiviez un cours en face à face et de ne pas surcharger la bande passante.
                                                </li>
                                            </ul>
                                            </p>
                                        </li>

                                        <li>
                                            <p style="text-align: justify">
                                            <b>Livres et matériel :</b> veuillez trouver <a href="https://learning.unog.ch/fr/node/1443">ici la liste des livres</a> à se procurer avant le premier cours.
                                            </p>
                                        </li>
                                    </ol>

                                    {{-- <p style="text-align: justify">
                                        Le mode de communication avec votre professeur en dehors de vos heures de cours est le courrier électronique. Les enseignants travaillent très dur, mais ils ont également besoin de se reposer et de prendre du temps pour eux ou leur famille ; vous comprendrez donc qu'ils ne sont pas disponibles 24 heures sur 24, 7 jours sur 7. Nous vous remercions de votre compréhension. 
                                    </p> --}}
                                    {{-- <p style="text-align: justify">
                                        Si vous rencontrez des problèmes quant à votre cours mentionné ci-dessus, veuillez nous contacter via le <a href="https://learning.unog.ch/fr/node/25">formulaire de contact</a>. 
                                    </p> --}}

                                    <!--<p style="text-align: justify">
                                        Veuillez lire attentivement <strong>les informations avant le début du trimestre</strong> disponibles sur notre site web en cliquant <a href="https://learning.unog.ch/fr/node/1472">ici</a>.
                                    </p>-->
                                    {{-- <p style="text-align: justify">
                                        Si vous rencontrez des problèmes quant à votre cours mentionné ci-dessus, veuillez contacter le secrétariat de la formation linguistique à l'adresse <a href="mailto: clm_language@un.org">clm_language@un.org</a>. 
                                    </p> --}}
                                    <p style="text-align: justify">
                                        Si vous ne pouvez pas être présent(e)s la première semaine du trimestre, vous devez en informer à l’avance votre professeur par email. Les participant(e)s absent(e)s la première semaine du trimestre peuvent se voir désinscrit(e)s du cours et leur place donnée aux participant(e)s de la liste d’attente.
                                    </p>    
                                    <p style="color: red;text-align: justify;">
                                        Si vous devez annuler votre inscription (ou une de vos inscriptions si vous vous êtes inscrit(e) à deux cours), vous devez le faire avant le {{ $cancel_date_limit_string_fr }} à 23h59. Aucun frais de cours ne sera remboursé après cette date. Pour toute information sur le remboursement, cliquer <a href="https://learning.unog.ch/fr/node/1301#position5">ici</a>.
                                    </p>
                                    <p style="text-align: justify"> 
                                        Pour annuler, connectez-vous à la plate-forme <a href="https://clmlanguageregistration.unog.ch">https://clmlanguageregistration.unog.ch</a>, allez à «Submitted Forms», sélectionnez le trimestre approprié, puis cliquez sur «View Forms» pour afficher vos formulaires. Cliquez sur le bouton rouge «Cancel Enrolment» sur le formulaire d'inscription que vous souhaitez annuler.
                                    </p>
                                    <p style="text-align: justify">
                                        En cas de retard dans l’annulation, la raison technique ne sera pas considérée comme valable pour le remboursement, ni pour une non-facturation de votre organisation. Merci de votre compréhension.
                                    </p>
                                    {{-- <p style="margin: 0;text-align: justify;">
                                        Si vous avez des questions, n'hésitez pas à nous contacter à l'adresse suivante : <a href="mailto: clm_language@un.org">clm_language@un.org</a>.
                                    </p> --}}
                                    <p style="margin: 0;text-align: justify;">
                                        Si vous avez des questions, merci de consulter nos <a href="https://learning.unog.ch/fr/node/1301#position8">FAQ</a>.
                                    </p>
                                    <br>
                                    <p style="text-align: justify">
                                        Le Programme de formation linguistique vous souhaite une expérience riche en apprentissage pour le prochain trimestre.
                                    </p>
                                    <br><br>
                                    {{-- <p>
                                        <h4><strong>Secrétariat du Programme de formation linguistique</strong></h4>
                                        Annexe Bocage II - bureau 5 (Rez-de-chaussée) – vous pouvez nous joindre par email ou par téléphone<br>
                                        <br>
                                        Heures d’ouverture: 9:00-12:30 du lundi au vendredi.<br>
                                        Téléphone: + 41 22 917 44 09<br><br>
                                        <a href="https://learning.unog.ch/">https://learning.unog.ch/</a>
                                    </p> --}}
                                    <p>
                                        Pour en savoir plus, rendez-vous sur notre site web : <a href="https://learning.unog.ch/fr/language-index">https://learning.unog.ch/fr/language-index</a>
                                    </p>
                                </td>
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