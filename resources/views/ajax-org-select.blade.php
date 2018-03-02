                            <div class="form-group">
                                <label for="organization" class="col-md-2 control-label">Change Organization to: </label>
                                
                                <div class="col-md-8">
                                  <div class="dropdown">
                                    <select class="col-md-8 form-control" style="width: 100%;" name="organization" autocomplete="off" >
                                        <option>--- Please Select Organization ---</option>
											@if(!empty($select_org))
											  @foreach($select_org as $key => $value)
											    <option class="col-md-8 wx" value="{{ $key }}">{{ $value }}</option>
											  @endforeach
											@endif
                                    </select>
                                  </div>
                                </div>
                            </div>
