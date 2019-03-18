{{ count($merge) }}
@foreach ($merge as $element)
	<li>{{$element->INDEXID}} - {{$element->L}}  - {{$element->schedule_id}} - {{$element->courses->Description}}</li>
@endforeach