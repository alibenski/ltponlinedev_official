<div class="form-group col-md-12">
    <div class="card">
        <div class="card-header bg-info text-white"><strong>Student flexibility</strong></div>
        <div class="card-body">
            If the selected class is full or cancelled, I am flexible and can accept another: <span class="small text-danger"><strong>Required fields</strong></span>
            <div class="form-group col-md-12">
                <div class="disclaimer-flexible alert alert-default alert-block col-md-12">
                    {{-- <input id="flexibleBtn" name="flexibleBtn" class="with-font" type="checkbox" value="1">
                    <label for="flexibleBtn" class="form-control-static">I am flexible and can accept another <b>schedule (days/times)</b> if the selected class is full.
                    </label> --}}
                    <div class="form-group">
                        <label for="text-label-day">Day: </label>
                        <div class="form-check form-check-inline">        

                            @if (substr($terms->Term_Code, -1) == '8') 
                            <input id="flexibleDayYes" name="flexibleDay" class="with-font form-check-input"  value="1" type="checkbox" checked disabled>
                            <input name="flexibleDay" class="with-font form-check-input" value="1" type="hidden">
                            @else
                            <input id="flexibleDayYes" name="flexibleDay" class="with-font form-check-input"  value="1" type="radio">
                            @endif

                            <label for="flexibleDayYes" class="form-check-label">YES</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input id="flexibleDayNo" name="flexibleDay" class="with-font form-check-input" type="radio" value="0" @if (substr($terms->Term_Code, -1) == '8') disabled @endif>
                            <label for="flexibleDayNo" class="form-check-label">NO</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="text-label-time">Time: </label>
                        <div class="form-check form-check-inline">                           
                            <input id="flexibleTimeYes" name="flexibleTime" class="with-font form-check-input" type="radio" value="1">
                            <label for="flexibleTimeYes" class="form-check-label">YES</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input id="flexibleTimeNo" name="flexibleTime" class="with-font form-check-input" type="radio" value="0">
                            <label for="flexibleTimeNo" class="form-check-label">NO</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="text-label-mode">Delivery Mode (in-person or online): </label>
                        <div class="form-check form-check-inline">                           
                            <input id="flexibleFormatYes" name="flexibleFormat" class="with-font form-check-input" type="radio" value="1">
                            <label for="flexibleFormatYes" class="form-check-label">YES</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input id="flexibleFormatNo" name="flexibleFormat" class="with-font form-check-input" type="radio" value="0">
                            <label for="flexibleFormatNo" class="form-check-label">NO</label>
                        </div>
                    </div>

                </div>
            </div> 

            <div id="summerTermText" class="form-group @if (substr($terms->Term_Code, -1) != '8') d-none @endif">
                <p class="text-danger">For summer term, Flexible Day field is checked by default - Monday to Friday</p>
            </div>

            {{-- <div class="form-group col-md-12">
                <div class="disclaimer-flexible alert alert-default alert-block col-md-12">
                <input id="flexibleFormat" name="flexibleFormat" class="with-font" type="checkbox" value="1">
                <label for="flexibleFormat" class="form-control-static">I am flexible about the delivery mode and <b>can accept either in-person or online</b> if my first choice of mode is not available. 
                </label>
                </div>
            </div>   --}}

            <div class="form-group">
                <label class="col-md-12 control-label">Comments: </label>
                <div class="col-md-12">
                    <span class="text-danger">Please indicate below any other relevant information, for example, a second preferred course</span>
                    <textarea name="regular_enrol_comment" class="form-control border border-danger" maxlength="3500" placeholder=""></textarea>
                    {{-- <small class="text-danger">Please indicate any relevant information above; for example: what course (if any) you would like to take if the course you selected is full, and any time constraints.</small> --}}
                </div>
            </div>
        </div>
    </div>
</div>