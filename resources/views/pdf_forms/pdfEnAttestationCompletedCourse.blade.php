@extends('layouts.pdf.attestationPdfEn')

@section('content')
    <tr>
      <td></td>
      <td></td>
      <td align="right" style="width:200.65pt;border:solid white 1.0pt;padding:0cm 6.0pt 0cm 6.0pt;" valign="top" width="284">
        Geneva, {{$dateOfPrinting}}
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
        <p class="MsoNormal" style="text-align:center;"><span lang="EN-GB" style="font-family:Century Gothic,sans-serif;">The Language Training Programme Secretariat of the Centre for Learning and Multilingualism,<br> United Nations Office at Geneva, certifies that:</span></p>
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
          @if ($term >= 191 && $selfPay == 1) 
          has paid {{$price}} CHF for and has successfully completed the following course:
          @elseif($term < 191 && !in_array($cat, ['STF', 'OCHA', 'UNA', 'WMO', 'GST', '']))
          has paid for and has successfully completed for the following course:
          @else
          has successfully completed the following course:
          @endif
          </span></p>

        @else 

          <p class="MsoNormal" style="text-align:center;"><span style="font-family:Century Gothic,sans-serif;">
          @if ($term >= 191 && $selfPay == 1) 
          has paid {{$price}} CHF for and has attended the following course:
          @elseif($term < 191 && !in_array($cat, ['STF', 'OCHA', 'UNA', 'WMO', 'GST', '']))
          has paid for and has attended the following course:
          @else
          has attended the following course:
          @endif
          </span></p>
        
        @endif
      </td>
    </tr>
  </tbody>
</table>

<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 800px;">
  <tbody>
    <tr>
      
      <td class="text-center">
        <p class="MsoNormal" style="text-align:center;"><span style="font-family:Century Gothic,sans-serif; font-weight: 800; font-size: 15;">{{ $courseEn }}*</span></p>
      </td>
      
    </tr>
    <tr>

      <td class="text-center">
        <p class="MsoNormal" style="text-align:center;"><span style="font-family:Century Gothic,sans-serif; font-weight: 800; font-size: 15;">{{ $termSeasonEn }} {{ $termYear }} ({{ $termNameEn }})</span></p>
      </td>

    </tr>
  </tbody>
</table>
@stop