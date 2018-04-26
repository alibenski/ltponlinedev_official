<option value="">--- Select Course ---</option>
@if(!empty($select_courses))
  @foreach($select_courses as $key => $value)
    <option class="col-md-8 wx" value="{{ $key }}">{{ $value }}</option>
  @endforeach
@endif
