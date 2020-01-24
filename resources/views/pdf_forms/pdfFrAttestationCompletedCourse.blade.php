<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="referrer" content="origin-when-cross-origin">
  <title>Attestation</title>
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
      <td style="width:212.65pt;border:solid white 1.0pt;padding:0cm 6.0pt 0cm 6.0pt;" valign="top" width="284">
        <p class="MsoNormal" style="line-height:7.2pt;"><span lang="EN-GB">&nbsp;</span></p>
        <p class="MsoNormal"><span lang="EN-GB" style="font-size:13px;font-family:Arial,sans-serif;">&nbsp;</span></p>
        <div style="border:none;border-bottom:solid windowtext 1.5pt;padding:0cm 0cm 1.0pt 0cm;">
          <p class="MsoNormal" style="border:none;padding:0cm;"><span lang="FR" style="font-size:13px;font-family:Arial,sans-serif;">OFFICE DES NATIONS UNIES A GENEVE</span></p>
        </div>
        <p class="MsoNormal" style="text-align:justify;"><span lang="EN-GB" style="font-size:11px;font-family:Arial,sans-serif;">&nbsp;</span></p>
        <p class="MsoNormal"><span style="font-size:11px;font-family:Arial,sans-serif;">Centre de formation et de multilinguisme</span></p>
        <p class="MsoNormal"><span style="font-size:11px;font-family:Arial,sans-serif;">Annex Bocage 2 - Bureau 5</span></p>
        <p class="MsoNormal"><span style="font-size:11px;font-family:Arial,sans-serif;">Bureau des Nations Unies à Genève</span></p>
        <p class="MsoNormal"><span lang="EN-GB" style="font-size:11px;font-family:Arial,sans-serif;">Palais des Nations</span></p>
        <p class="MsoNormal"><span lang="EN-GB" style="font-size:11px;font-family:Arial,sans-serif;">CH-1211 Genève 10 - Suisse</span></p>
        <p class="MsoNormal" style="line-height:12.0pt;"><span lang="EN-GB" style="font-size:13px;font-family:Arial,sans-serif;">&nbsp;</span></p>
      </td>
      <td class="text-center">
        <img width="150px" src={{ asset('img/Logo_UN.jpg') }}>
      </td>
      <td style="width:212.65pt;border:solid white 1.0pt;padding:0cm 6.0pt 0cm 6.0pt;" valign="top" width="284">
        <p class="MsoNormal" style="line-height:7.2pt;"><span lang="EN-GB">&nbsp;</span></p>
        <p class="MsoNormal"><span lang="EN-GB" style="font-size:13px;font-family:Arial,sans-serif;">&nbsp;</span></p>
        <div style="border:none;border-bottom:solid windowtext 1.5pt;padding:0cm 0cm 1.0pt 0cm;">
          <p class="MsoNormal" style="border:none;padding:0cm;text-align: right;"><span lang="FR" style="font-size:13px;font-family:Arial,sans-serif;">UNITED NATIONS OFFICE AT GENEVA</span></p>
        </div>
        <p class="MsoNormal" style="text-align:right;"><span lang="EN-GB" style="font-size:11px;font-family:Arial,sans-serif;">&nbsp;</span></p>
        <p class="MsoNormal" style="text-align:right;"><span style="font-size:11px;font-family:Arial,sans-serif;">Téléphone</span><span lang="EN-GB" style="font-size:11px;font-family:Arial,sans-serif;">&nbsp;0041 22 917 4409</span></p>
        <p class="MsoNormal" style="text-align:right;"><span lang="DE-CH" style="font-size:11px;font-family:Arial,sans-serif;">E-mail: clm_language@un.org</span></p>
        <p class="MsoNormal" style="text-align:right;"><span lang="DE-CH" style="font-size:11px;font-family:Arial,sans-serif;">Site internet: https://learning.unog.ch</span></p>
      </td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td align="center">
        {{$dateOfPrinting}}
        <br>
        <br>
        <br>
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <p align="center" class="MsoNormal" style="text-align:center;"><span lang="FR" style="font-family:Century Gothic,sans-serif; font-weight: 800; font-size: 11;">A T T E S T A T I O N</span></p>
        <br>
      </td>
      <td></td>
    </tr>    
  </tbody>
</table>

<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 800px;">
  <tbody>
    <tr>
      <td>
        <p class="MsoNormal" style="text-align:justify;"><span lang="FR" style="font-family:Century Gothic,sans-serif;">Le Secrétariat des Langues du centre de formation et de multilinguisme des Nations Unies à Genève certifie que&nbsp;:</span></p>
        <br>
        <br>
      </td>
    </tr>
  </tbody>
</table>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 800px;">
  <tbody>
    <tr>
      <td></td>
      <td>
        <p class="MsoNormal" style="text-align:center;"><span lang="FR" style="font-family:Century Gothic,sans-serif; font-weight: 800; font-size: 11;">{{ $userName }}</span></p>
        <br>
        <br>
      </td>
    </tr>
  </tbody>
</table>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 800px;">
  <tbody>
    <tr>
      <td></td>
      <td>
        @if ($result == 'P')
          <p class="MsoNormal" style="text-align:center;"><span style="font-family:Century Gothic,sans-serif;">A complété avec succès @if ($selfPay == 1) et a payé pour @endif le cours mentionné ci-dessous&nbsp;:</span></p>
        @else
          <p class="MsoNormal" style="text-align:center;"><span style="font-family:Century Gothic,sans-serif;">A suivi @if ($selfPay == 1) et a payé pour @endif le cours mentionné ci-dessous&nbsp;:</span></p>
        @endif
        <br>
        <br>
      </td>
    </tr>
  </tbody>
</table>

<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 800px;">
  <tbody>
    <tr>
      <td></td>
      <td>
        <p class="text-center">Trimestre</p>
      </td>
      <td>
        <p class="text-center">Cours</p>
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <p class="text-center">{{ $termSeasonFr }} {{ $termYear }}</p>
      </td>
      <td>
        <p class="text-center">{{ $courseFr}}</p>
      </td>
    </tr>
  </tbody>
</table>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 800px;">
  <tbody>
    <div style="border:none;border-bottom:none windowtext 1.5pt;padding:0cm 0cm 1.0pt 0cm;">
      <p class="MsoNormal" style="border:none;padding:0cm;text-align: right;"><span lang="FR" style="font-size:13px;font-family:Arial,sans-serif;">Le Secrétariat des langues</span></p>
      <p class="MsoNormal" style="border:none;padding:0cm;text-align: right;"><span lang="FR" style="font-size:13px;font-family:Arial,sans-serif;">Nations Unies Genève</span></p>
    </div>
  </tbody>
</table>  
</body>
</html>