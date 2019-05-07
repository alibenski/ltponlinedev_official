@if (!empty($scheduleDays->day_1))
  <label>Monday Room:</label>
  <div class="form-group room_div_{{ $scheduleDays->id }}">
    <select id="room_id_select_{{ $scheduleDays->id }}" class="col-md-8 form-control select2-multi" name="Te_Mon_Room" multiple="multiple" autocomplete="off"  style="width: 100%">
        <option value="">--- Select Room ---</option>
        @foreach ($rooms as $valueRoom)
            <option value="{{$valueRoom->id}}">{{$valueRoom->Rl_Room}} </option>
        @endforeach
    </select>
  </div>
@endif

@if (!empty($scheduleDays->day_2))
  <label>Tuesday Room:</label>
  <div class="form-group room_div_{{ $scheduleDays->id }}">
    <select id="room_id_select_{{ $scheduleDays->id }}" class="col-md-8 form-control select2-multi" name="Te_Tue_Room" multiple="multiple" autocomplete="off"  style="width: 100%">
        <option value="">--- Select Room ---</option>
        @foreach ($rooms as $valueRoom)
            <option value="{{$valueRoom->id}}">{{$valueRoom->Rl_Room}} </option>
        @endforeach
    </select>
  </div> 
@endif

@if (!empty($scheduleDays->day_3))
  <label>Wednesday Room:</label>
  <div class="form-group room_div_{{ $scheduleDays->id }}">
    <select id="room_id_select_{{ $scheduleDays->id }}" class="col-md-8 form-control select2-multi" name="Te_Wed_Room" multiple="multiple" autocomplete="off"  style="width: 100%">
        <option value="">--- Select Room ---</option>
        @foreach ($rooms as $valueRoom)
            <option value="{{$valueRoom->id}}">{{$valueRoom->Rl_Room}} </option>
        @endforeach
    </select>
  </div>
@endif

@if (!empty($scheduleDays->day_4))
  <label>Thursday Room:</label>
  <div class="form-group room_div_{{ $scheduleDays->id }}">
    <select id="room_id_select_{{ $scheduleDays->id }}" class="col-md-8 form-control select2-multi" name="Te_Thu_Room" multiple="multiple" autocomplete="off"  style="width: 100%">
        <option value="">--- Select Room ---</option>
        @foreach ($rooms as $valueRoom)
            <option value="{{$valueRoom->id}}">{{$valueRoom->Rl_Room}} </option>
        @endforeach
    </select>
  </div>
@endif

@if (!empty($scheduleDays->day_5))
  <label>Friday Room:</label>
  <div class="form-group room_div_{{ $scheduleDays->id }}">
    <select id="room_id_select_{{ $scheduleDays->id }}" class="col-md-8 form-control select2-multi" name="Te_Fri_Room" multiple="multiple" autocomplete="off"  style="width: 100%">
        <option value="">--- Select Room ---</option>
        @foreach ($rooms as $valueRoom)
            <option value="{{$valueRoom->id}}">{{$valueRoom->Rl_Room}} </option>
        @endforeach
    </select>
  </div>
@endif

