@extends('admin.admin')

@section('content')

<p>SDDEXTR: {{ count($counter) }}</p>
<p>User: {{ count($counterUser) }}</p>
<p>PASH: {{ count($counterPASH) }}</p>
<p>Enrolment:{{ count($counterEnrol) }}</p>
<p>Placement:{{ count($counterPlacement) }}</p>
<p>Placement:{{ count($counterModified) }}</p>

@foreach ($getIndex as $element)
	<li> {{ $element->{'INDEXNO-August'} }} </li>

@endforeach

@stop