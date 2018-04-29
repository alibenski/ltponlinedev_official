@extends('admin.admin')

@section('content')

	@foreach($enrolment_forms as $form)
		<ul>
			<li>
				@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
				{{ $form->L }}
				{{ $form->Code }}
				@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank">carte attachment</a> @endif
				@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank">payment attachment</a> @endif
			</li>
		</ul>
	@endforeach

@stop