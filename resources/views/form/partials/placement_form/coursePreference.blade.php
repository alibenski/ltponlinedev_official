<div class="col-md-12 mt-4">
    <div class="card">
    <div class="card-header bg-primary col-md-12"><strong>Information about your availability</strong></div>
    <div class="card-body">
        <div class="row">
        <div class="col-md-12 alert alert-danger">
        <p>Please indicate the time and the days you are available to attend the course. Check/tick all that apply.</p>
        <p>Also indicate your availability for in-person, online courses, or both delivery modes.</p>
        <p>For summer term, all days are checked/ticked.</p>
        </div>
        </div>
        <div class="row col-md-12">
        <div class="otherQuestions col-md-12">
            <div class="form-group">
            <label for="" class="control-label">Time: <span class="text-danger"><em>(required)</em></span></label>
            <div class="col-md-12">
                    <div class="input-group col-md-12">                             
                    <input id="morning" name="timeInput[]" class="with-font" type="checkbox" value="morning">
                    <label for="morning" class="form-control-static">Morning (8 to 9.30 or 10 a.m.) </label>
                    </div>
                    
                    <div class="input-group col-md-12">
                    <input id="afternoon" name="timeInput[]" class="with-font" type="checkbox" value="afternoon">
                    <label for="afternoon" class="form-control-static">Afternoon (12.30 to 2 or 2.30 p.m.)</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="otherQuestions3 col-md-6">
            <div class="form-group">
            <label for="" class="control-label">Day: <span class="text-danger"><em>(required)</em></span></label>
            <div class="col-md-12">
                @foreach ($days as $id => $name)
                    <div class="input-group col-md-12">                             
                    <input id="{{ $name }}" name="dayInput[]" class="with-font" type="checkbox" value="{{ $id }}"
                    @if (substr($terms->Term_Code, -1) == '8')
                    checked                                            
                    @endif
                    >
                    <label for="{{ $name }}" class="form-control-static">{{ $name }}</label>
                    </div>
                @endforeach
                </div>
            </div>
        </div>

        <div class="otherQuestions4 col-md-6">
            <div class="form-group">
            <label for="" class="control-label">Delivery Mode Availability: <span class="text-danger"><em>(required)</em></span></label>
            <div class="col-md-12">
                    <div class="form-check">                           
                        <input id="in-person" name="deliveryMode" class="with-font form-check-input" type="radio" value="0">
                        <label for="in-person" class="form-check-label">in-person</label>
                    </div>
                    <div class="form-check">
                        <input id="online" name="deliveryMode" class="with-font form-check-input" type="radio" value="1">
                        <label for="online" class="form-check-label">online</label>
                    </div>
                    <div class="form-check">
                        <input id="both" name="deliveryMode" class="with-font form-check-input" type="radio" value="2">
                        <label for="both" class="form-check-label">available for both modes</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 form-group">
            <label class="col-md-12 control-label">Comment: <i class="text-danger">(required)</i></label>
            <div class="col-md-12 pink-border">
            <small class="text-danger"><i class="fa fa-warning"></i> <strong>You are required to fill this comment box. Failure to do so will nullify your submission.</strong></small>
            <textarea name="course_preference_comment" class="form-control" maxlength="3500" placeholder="Please indicate your preferred course, constraints, passed LPE, etc." required="required"></textarea>
            </div>
        </div>

        </div>
    </div> {{-- end card body --}}
    </div>
</div>