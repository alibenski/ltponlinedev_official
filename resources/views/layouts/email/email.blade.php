<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
        @include('layouts.email.partials._emailHead')
    </head>
    <body width="100%" bgcolor="#fff" style="margin: 0; mso-line-height-rule: exactly;">
        <center style="width: 100%; background: #fff; text-align: left;">
            <!-- Visually Hidden Preheader Text : BEGIN -->
            <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">

                @yield('preheader')
            
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
                    @yield('content')
                <!-- Email Body : END -->


                <!-- Email Footer : BEGIN -->
                    @include('layouts.email.partials._emailFooter')
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
                            <!--<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
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
                        <!--</div>
                    </td>
                </tr>
            </table>
            Full Bleed Background Section : END -->
        </center>
    </body>
</html>