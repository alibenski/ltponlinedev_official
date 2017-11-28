<option>--- Select Course ---</option>
@if(!empty($select_courses))
  @foreach($select_courses as $key => $value)
    <option value="{{ $key }}">{{ $value }}</option>
  @endforeach
@endif
