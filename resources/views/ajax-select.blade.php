{{-- dropdown select options grouped by regular/specialized courses --}}
<option value="">--- Select Course ---</option>
@if(!empty($select_courses))
  @foreach($select_courses as $item)
    @foreach ($item as $value)
    <option class="col-md-8 wx" value="{{ $value->Te_Code_New }}">{{ $value->course->Description }}</option>
    @endforeach
  @endforeach
@endif
