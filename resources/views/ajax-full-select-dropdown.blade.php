<label>Language</label> 
@foreach ($languages as $id => $name)
    <div class="input-group col-sm-12">
      <input id="{{ $name }}" name="L" class="with-font lang_select_no" type="radio" value="{{ $id }}">
      <label for="{{ $name }}" class="label-lang form-control-static">{{ $name }}</label>
    </div>
@endforeach
<div class="form-group">
	<label>Course</label>
    <div class="col-sm-12">
      <div class="dropdown">
        <select class="col-sm-12 form-control course_select_no select2-basic-single" style="width: 100%;" name="Te_Code">
            <option value="">--- Select Course ---</option>
        </select>
      </div>
    </div>
</div>

<div class="form-group">
	<label>Schedule</label>
    <div class="col-sm-12">
      <div class="dropdown">
        <select class="col-sm-12 form-control schedule_select_no select2-basic-single" style="width: 100%;" name="schedule_id">
            <option value="">Fill Out Language and Course Options</option>
        </select>
      </div>
    </div>
</div>
