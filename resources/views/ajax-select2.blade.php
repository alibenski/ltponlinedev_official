@if(!empty($select_schedules))
  @foreach($select_schedules as $key => $value)
    <option value="{{ $key }}">{{ $key }} - {{ $value }}</option>
  @endforeach
@endif
