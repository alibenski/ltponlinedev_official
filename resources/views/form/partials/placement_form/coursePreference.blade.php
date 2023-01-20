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

            <div class="row">

                <div class="otherQuestions col-md-12 border">
                    <div class="form-group">
                    <label for="" class="control-label">Time: <span class="text-danger"><em>(required)</em></span></label>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="text-label-time">Available Any Time? </label>
                                <div class="form-check form-check-inline">                           
                                    <input id="flexibleTimeYes" name="flexibleTime" class="with-font form-check-input" type="radio" value="1">
                                    <label for="flexibleTimeYes" class="form-check-label">YES</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input id="flexibleTimeNo" name="flexibleTime" class="with-font form-check-input" type="radio" value="0">
                                    <label for="flexibleTimeNo" class="form-check-label">NO</label>
                                </div>
                            </div>
                            
                            <div id="anyTimeSection" class="d-none form-group alert alert-success">YES, I am flexible to accept Morning (8 to 9.30 or 10 a.m.) or Afternoon (12.30 to 2 or 2.30 p.m.) classes</div>

                            <div id="timeInputSection" class="d-none form-group">
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
                </div>

                <script>
                    $("input[name='flexibleTime']").on("click", function () {
                        $("input[name='timeInput[]']").prop("checked", false);
                    })
                    $("input#flexibleTimeYes").on("click", function () {
                        $("div#timeInputSection").addClass("d-none");
                        $("div#anyTimeSection").removeClass("d-none");
                        $("input[name='timeInput[]']").attr("type", "checkbox");
                        $("input[name='timeInput[]']").prop("checked", true);
                    })
                    $("input#flexibleTimeNo").on("click", function () {
                        $("div#timeInputSection").removeClass("d-none");
                        $("div#anyTimeSection").addClass("d-none");
                        $("input[name='timeInput[]']").attr("type", "radio");
                    })
                </script>

                <div class="otherQuestions3 col-md-12 border mt-2">                    
                    <div class="form-group">
                        <label for="" class="control-label">Day: <span class="text-danger"><em>(required)</em></span></label>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="text-label-day">Available Any Day? </label>
                                <div class="form-check form-check-inline">                           
                                    <input id="flexibleDayYes" name="flexibleDay" class="with-font form-check-input" type="radio" value="1">
                                    <label for="flexibleDayYes" class="form-check-label">YES</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input id="flexibleDayNo" name="flexibleDay" class="with-font form-check-input" type="radio" value="0">
                                    <label for="flexibleDayNo" class="form-check-label">NO</label>
                                </div>
                            </div>

                            <div id="anyDaySection" class="d-none form-group alert alert-success">YES, I am flexible to accept any day of the week (Monday to Friday)</div>

                            <div id="dayInputSection" class="d-none form-group">
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
                </div>

                <script>
                    $("input[name='flexibleDay']").on("click", function () {
                        $("input[name='dayInput[]']").prop("checked", false);
                    })
                    $("input#flexibleDayYes").on("click", function () {
                        $("div#dayInputSection").addClass("d-none");
                        $("div#anyDaySection").removeClass("d-none");
                        $("input[name='dayInput[]']").prop("checked", true);
                    })
                    $("input#flexibleDayNo").on("click", function () {
                        $("div#dayInputSection").removeClass("d-none");
                        $("div#anyDaySection").addClass("d-none");
                    })

                    let limit = 4;
                    $("input[name='dayInput[]']").on("change", function () {
                        if ($("input[name='dayInput[]']:checked").length > limit) {
                            $(this).prop('checked', false);
                            alert("Only 4 days allowed. Choose Yes if you are available to take a class on any given day.");
                        }
                    })
                </script>

                <div class="otherQuestions4 col-md-12 border mt-2">
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
                            <input id="flexibleFormatYes" name="flexibleFormat" type="hidden" value="0">
                        </div>
                    </div>
                </div>

                <script>
                    $("input#in-person").on("click", function () {
                        $("input#flexibleFormatYes").attr("value", "0");
                    })
                    $("input#online").on("click", function () {
                        $("input#flexibleFormatYes").attr("value", "0");
                    })
                    $("input#both").on("click", function () {
                        $("input#flexibleFormatYes").attr("value", "1");
                    })
                </script>

                <div class="col-md-12 form-group mt-2">
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