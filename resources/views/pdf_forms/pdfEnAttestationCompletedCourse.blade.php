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
      <td style="width:200.65pt;border:solid white 1.0pt;padding:0cm 6.0pt 0cm 6.0pt;" valign="top" width="284">
        <p class="MsoNormal" style="line-height:7.2pt;"><span lang="EN-GB">&nbsp;</span></p>
        <p class="MsoNormal"><span lang="EN-GB" style="font-size:13px;font-family:Arial,sans-serif;">&nbsp;</span></p>
        <div style="border:none;border-bottom:solid windowtext 1.5pt;padding:0cm 0cm 1.0pt 0cm;">
          <p class="MsoNormal" style="border:none;padding:0cm;"><span lang="FR" style="font-size:13px;font-family:Arial,sans-serif;">OFFICE DES NATIONS UNIES A GENEVE</span></p>
        </div>
        <p class="MsoNormal" style="text-align:justify;"><span lang="EN-GB" style="font-size:11px;font-family:Arial,sans-serif;">&nbsp;</span></p>
        <p class="MsoNormal"><span style="font-size:11px;font-family:Arial,sans-serif;">Centre for Learning and Multilingualism</span></p>
        <p class="MsoNormal"><span style="font-size:11px;font-family:Arial,sans-serif;">Annex Bocage 2 - Room 5 (ground floor)</span></p>
        <p class="MsoNormal"><span style="font-size:11px;font-family:Arial,sans-serif;">United Nations Office at Geneva</span></p>
        <p class="MsoNormal"><span lang="EN-GB" style="font-size:11px;font-family:Arial,sans-serif;">Palais des Nations</span></p>
        <p class="MsoNormal"><span lang="EN-GB" style="font-size:11px;font-family:Arial,sans-serif;">CH-1211 Geneva 10 - Switzerland</span></p>
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
        <p class="MsoNormal" style="text-align:right;"><span style="font-size:11px;font-family:Arial,sans-serif;">Telephone</span><span lang="EN-GB" style="font-size:11px;font-family:Arial,sans-serif;">&nbsp;0041 22 917 4409</span></p>
        <p class="MsoNormal" style="text-align:right;"><span lang="DE-CH" style="font-size:11px;font-family:Arial,sans-serif;">E-mail: clm_language@un.org</span></p>
        <p class="MsoNormal" style="text-align:right;"><span lang="DE-CH" style="font-size:11px;font-family:Arial,sans-serif;">Website: https://learning.unog.ch</span></p>
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
        <p class="MsoNormal" style="text-align:center;"><span lang="EN-GB" style="font-family:Century Gothic,sans-serif;">The Language Secretariat of the Centre for Learning & Multilingualism, United Nations Office at Geneva certifies that:</span></p>
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
        <p class="MsoNormal" style="text-align:center;"><span lang="EN" style="font-family:Century Gothic,sans-serif; font-weight: 800; font-size: 11;">{{ $userName }}</span></p>
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
          <p class="MsoNormal" style="text-align:center;"><span style="font-family:Century Gothic,sans-serif;">Has successfully completed @if ($selfPay == 1) and has paid for @endif the following course:</span></p>
        @else 
          <p class="MsoNormal" style="text-align:center;"><span style="font-family:Century Gothic,sans-serif;">Has attended @if ($selfPay == 1) and has paid for @endif the following course:</span></p>
        @endif
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
        <p class="text-center">Term</p>
      </td>
      <td>
        <p class="text-center">Course</p>
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <p class="text-center">{{ $termSeasonEn }} {{ $termYear }}</p>
      </td>
      <td>
        <p class="text-center">{{ $courseEn }}</p>
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
      <p class="MsoNormal" style="border:none;padding:0cm;text-align: right;"><span lang="FR" style="font-size:13px;font-family:Arial,sans-serif;">The Language Secretariat</span></p>
      <p class="MsoNormal" style="border:none;padding:0cm;text-align: right;"><span lang="FR" style="font-size:13px;font-family:Arial,sans-serif;">United Nations Office at Geneva</span></p>
    </div>
  </tbody>
</table>  
</body>
</html>