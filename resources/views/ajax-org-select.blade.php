                            <div class="col-md-12">
                            <div class="form-group row well">
                                <label for="organization" class="col-md-2 control-label text-danger">Change Organization to: </label>
                                
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
                                  <p class="small text-danger"><strong>Please check that you belong to the correct Organization in this field.</strong></p>
                                </div>
                            </div>
                            </div>
