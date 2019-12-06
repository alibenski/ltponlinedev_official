@if(!empty($collection))
  @foreach($collection as $value)
    <option class="col-md-8 wx" value="{{ $value->Code }}">{{ $value->scheduler->name }} - section # {{ $value->sectionNo }}
		- @if (is_null($value->Tch_ID)) No Teacher/Waitlist/Placeholder @else {{ $value->teachers->Tch_Name }} @endif
    </option>
  @endforeach
@endif
