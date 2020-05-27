@extends('layouts.pdf.attestationPdfFr')

@section('content')
    <tr>
      <td></td>
      <td></td>
      <td align="right" style="width:200.65pt;border:solid white 1.0pt;padding:0cm 6.0pt 0cm 6.0pt;" valign="top" width="284">
        Genève, {{$dateOfPrinting}}
        <br>
        <br>
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <p align="center" class="MsoNormal" style="text-align:center;"><span lang="FR" style="font-family:Century Gothic,sans-serif; font-weight: 800; font-size: 15;">ATTESTATION</span></p>
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
        <p class="MsoNormal" style="text-align:center;"><span lang="FR" style="font-family:Century Gothic,sans-serif;">Le secrétariat du Programme de formation linguistique du Centre de formation et de multilinguisme de l’Office des Nations Unies à Genève certifie que :</span></p>
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
        <p class="MsoNormal" style="text-align:center;"><span lang="FR" style="font-family:Century Gothic,sans-serif; font-weight: 800; font-size: 15;">{{ $userName }}</span></p>
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
          s’est acquitté(e) des frais de cours de {{$price}} CHF et a réussi le cours mentionné ci-dessous :
          @else
          a réussi le cours mentionné ci-dessous :
          @endif
          </span></p>

        @else

          <p class="MsoNormal" style="text-align:center;"><span style="font-family:Century Gothic,sans-serif;">
          @if ($selfPay == 1) 
          s’est acquitté(e) des frais de cours de {{$price}} CHF et a suivi le cours mentionné ci-dessous :
          @else
          a suivi le cours mentionné ci-dessous :
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
        <p><u>Cours</u></p>
        <p style="font-family:Century Gothic,sans-serif; font-weight: 800; font-size: 15;">{{ $courseFr }}*</p>
      </td>
      
    </tr>
    <tr>

      <td class="text-center">
        <p><u>Trimestre</u></p>
        <p style="font-family:Century Gothic,sans-serif; font-weight: 800; font-size: 15;">{{ $termSeasonFr }} {{ $termYear }} ({{ $termNameFr }})</p>
      </td>

    </tr>
  </tbody>
</table>
@stop