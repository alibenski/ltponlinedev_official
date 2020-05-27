@extends('layouts.pdf.attestationPdfEn')

@section('content')
    <tr>
      <td></td>
      <td></td>
      <td align="right" style="width:200.65pt;border:solid white 1.0pt;padding:0cm 6.0pt 0cm 6.0pt;" valign="top" width="284">
        {{$dateOfPrinting}}
        <br>
        <br>
        <br>
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <p align="center" class="MsoNormal" style="text-align:center;"><span lang="EN-GB" style="font-family:Century Gothic,sans-serif; font-weight: 800; font-size: 15;">CERTIFICATE</span></p>
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
        <p class="MsoNormal" style="text-align:center;"><span lang="EN-GB" style="font-family:Century Gothic,sans-serif;">The Language Training Programme Secretariat of the Centre for Learning and Multilingualism, United Nations Office at Geneva, certifies that:</span></p>
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
        <p class="MsoNormal" style="text-align:center;"><span lang="EN" style="font-family:Century Gothic,sans-serif; font-weight: 800; font-size: 15;"><b>{{ $userName }}</b></span></p>
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

          <p class="MsoNormal" style="text-align:center;"><span style="font-family:Century Gothic,sans-serif;">
          @if ($selfPay == 1) 
          has paid {{$price}} CHF for and successfully completed for the following course:
          @else
          successfully completed the following course:
          @endif
          </span></p>

        @else 

          <p class="MsoNormal" style="text-align:center;"><span style="font-family:Century Gothic,sans-serif;">
          @if ($selfPay == 1) 
          has paid {{$price}} CHF for and attended the following course:
          @else
          attended the following course:
          @endif
          </span></p>
        
        @endif
        <br>
      </td>
    </tr>
  </tbody>
</table>

<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 800px;">
  <tbody>
    <tr>
      
      <td class="text-center">
        <p><u>Course</u></p>
        <p style="font-family:Century Gothic,sans-serif; font-weight: 800; font-size: 15;">{{ $courseEn }}*</p>
      </td>
      
    </tr>
    <tr>

      <td class="text-center">
        <p><u>Term</u></p>
        <p style="font-family:Century Gothic,sans-serif; font-weight: 800; font-size: 15;">{{ $termSeasonEn }} {{ $termYear }} ({{ $termNameEn }})</p>
      </td>

    </tr>
  </tbody>
</table>
@stop