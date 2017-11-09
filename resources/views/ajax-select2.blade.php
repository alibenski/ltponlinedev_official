<option>--- Select 2 Schedules ---</option>
@if(!empty($select_schedules))
  @foreach($select_schedules as $key => $value)
    <option value="{{ $key }}">{{ $value->scheduler->name }}</option>
  @endforeach
@endif