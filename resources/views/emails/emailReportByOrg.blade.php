@extends('layouts.email.email')

@section('preheader')
    Student Registration Email
@stop

@section('content')

<a href="{{ route('report-by-org', [Crypt::encrypt($param), Crypt::encrypt($org), Crypt::encrypt($term), Crypt::encrypt($year)]) }}" style="background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 110%; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
    <span style="color:#ffffff;" class="button-link">&nbsp;&nbsp;&nbsp;&nbsp;Report Link&nbsp;&nbsp;&nbsp;&nbsp;</span></a>

<br />

@stop