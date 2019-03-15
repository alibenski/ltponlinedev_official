{{ count($merge) }}
@foreach ($merge as $element)
	<li>{{$element->INDEXID}} - {{$element->L}} - {{$element->Te_Code}} - {{$element->schedule_id}}</li>
@endforeach