<option>--- Select Course ---</option>
@if(!empty($courses))
  @foreach($courses as $key => $value)
    <option value="{{ $key }}">{{ $value }}</option>
  @endforeach
@endif