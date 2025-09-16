<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="referrer" content="origin-when-cross-origin">
  <title>LTP Attestation</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <style>
    h4,h3 {
      font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
      line-height: 1.42857143;
      color: #333;
      background-color: #fff;
    }
    td img{
      display: block;
      margin-left: auto;
      margin-right: auto;
    }
  </style>
</head>
<body>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 800px;">
  <tbody>
    <tr>
      <td style="width:200.65pt;border:solid white 1.0pt;padding:0cm 6.0pt 0cm 6.0pt;" valign="top" width="284">
        <p class="MsoNormal" style="line-height:7.2pt;"><span lang="EN-GB">&nbsp;</span></p>
        <p class="MsoNormal"><span lang="EN-GB" style="font-size:13px;font-family:Arial,sans-serif;">&nbsp;</span></p>
        <div style="border:none;border-bottom:solid windowtext 1.5pt;padding:0cm 0cm 1.0pt 0cm;">
          <p class="MsoNormal" style="border:none;padding:0cm;"><span lang="FR" style="font-size:13px;font-family:Arial,sans-serif;">OFFICE DES NATIONS UNIES À GENEVE</span></p>
        </div>
        <p>
        <span class="MsoNormal" style="text-align:justify;"><span lang="EN-GB" style="font-size:11px;font-family:Arial,sans-serif;">&nbsp;</span></span><br>
        <span class="MsoNormal"><span style="font-size:11px;font-family:Arial,sans-serif;">Centre de formation et de multilinguisme</span></span><br>
        <span class="MsoNormal"><span style="font-size:11px;font-family:Arial,sans-serif;">bâtiment H, 5ème étage</span></span><br>
        <span class="MsoNormal"><span style="font-size:11px;font-family:Arial,sans-serif;">Office des Nations Unies à Genève</span></span><br>
        <span class="MsoNormal"><span lang="EN-GB" style="font-size:11px;font-family:Arial,sans-serif;">Palais des Nations</span></span>
        <span class="MsoNormal"><span lang="EN-GB" style="font-size:11px;font-family:Arial,sans-serif;">CH-1211 Genève 10 - Suisse</span></span>
        <span class="MsoNormal" style="line-height:12.0pt;"><span lang="EN-GB" style="font-size:13px;font-family:Arial,sans-serif;">&nbsp;</span></span>
        </p>
      </td>
      <td class="text-center">
        <img width="150px" src="data:image/png;base64,{{ base64_encode(file_get_contents( asset('img/Logo_UN.jpg'))) }}" alt="Logo_UN">
      </td>
      <td style="width:200.65pt;border:solid white 1.0pt;padding:0cm 6.0pt 0cm 6.0pt;" valign="top" width="284">
        <p class="MsoNormal" style="line-height:7.2pt;"><span lang="EN-GB">&nbsp;</span></p>
        <p class="MsoNormal"><span lang="EN-GB" style="font-size:13px;font-family:Arial,sans-serif;">&nbsp;</span></p>
        <div style="border:none;border-bottom:solid windowtext 1.5pt;padding:0cm 0cm 1.0pt 0cm;">
          <p class="MsoNormal" style="border:none;padding:0cm;text-align: right;"><span lang="FR" style="font-size:13px;font-family:Arial,sans-serif;">UNITED NATIONS OFFICE AT GENEVA</span></p>
        </div>
        <p class="MsoNormal" style="text-align:right;"><span lang="EN-GB" style="font-size:11px;font-family:Arial,sans-serif;">&nbsp;</span></p>
        <p class="MsoNormal" style="text-align:right;"><span style="font-size:11px;font-family:Arial,sans-serif;">Téléphone :</span><span lang="EN-GB" style="font-size:11px;font-family:Arial,sans-serif;">&nbsp;+41 22 917 44 09</span></p>
        <p class="MsoNormal" style="text-align:right;"><span lang="DE-CH" style="font-size:11px;font-family:Arial,sans-serif;">Email : clm_language@un.org</span></p>
        <p class="MsoNormal" style="text-align:right;"><span lang="DE-CH" style="font-size:11px;font-family:Arial,sans-serif;">Site Internet : https://learning.unog.ch</span></p>
      </td>
    </tr>

    @yield('content')

<br />
<br />
<br />
<br />
<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 800px;">
  <tbody>
    <div style="border:none;border-bottom:none windowtext 1.5pt;padding:0cm 0cm 1.0pt 0cm;">
      <div align="right">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(asset('img/CLMStamp.png'))) }}" >
      </div>
      <p class="MsoNormal" style="border:none;padding:0cm;text-align: right;"><span lang="FR" style="font-size:13px;font-family:Arial,sans-serif;">Le secrétariat du Programme de formation linguistique</span>
      <br />Centre de formation et de multilinguisme
      <br />Office des Nations Unies à Genève</p>
        <hr>
        <span style="font-size:11px;font-family:Arial,sans-serif;">* Pour plus d’informations relatives aux niveaux de compétence langagière des Nations Unies et leurs équivalents avec ceux d’autres cadres de référence, veuillez-vous référer à la page suivante : 
        <a href="https://learning.unog.ch/fr/node/1301#position9" target="_blank">https://learning.unog.ch/fr/node/1301#position9</a></span>
    </div>
</tbody>
</table>
</body>
</html>