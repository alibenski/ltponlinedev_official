<option value="">--- Select Here ---</option>
@if(!empty($teachers))
  @foreach($teachers as $key => $value)
    <option class="col-md-8 wx" value="{{ $key }}">{{ $value }}</option>
  @endforeach
@endif
