<div class="form-group col-sm-12">
  <label>Language</label> 
  @foreach ($languages as $id => $name)
      <div class="input-group col-sm-12">
        <input id="{{ $name }}" name="L" class="with-font lang_select_no modify-option" type="radio" value="{{ $id }}">
        <label for="{{ $name }}" class="label-lang form-control-static">{{ $name }}</label>
      </div>
  @endforeach
</div>
