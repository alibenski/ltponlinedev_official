<div class="form-group{{ $errors->has('contractfile') ? 'is-invalid' : '' }}">
    <label for="contractfile" class="col-md-12 control-label"><strong>Please tick the box of the document(s) you are providing a copy of:</strong> <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i> required field</span> </label>

    <div class="col-md-12">
        <div class="form-check col-md-12">
            <input class="form-check-input" type="radio" name="spouseRadio" id="spouseRadio1" value=1>
            <label class="form-check-label" for="spouseRadio1">
                Applicant's UN badge or carte de légitimation <strong>(both front and back sides in one file)</strong>
            </label>
        </div>
        <div class="form-check col-md-12">
            <input class="form-check-input" type="radio" name="spouseRadio" id="spouseRadio2" value=2>
            <label class="form-check-label" for="spouseRadio2">
                Proof of marriage between the applicant and the staff (UN or permanent mission) AND staff (UN or permanent mission) UN badge or carte de légitimation <strong>(both front and back sides in one file)</strong>
            </label>
        </div>
    </div>

    <div class="spouse-file-section"></div>
</div>

<script>
    $("input[name='spouseRadio']").on("click", function () {
        var spouseChoice = $(this).val();
        console.log(spouseChoice);
        if (spouseChoice == 1) {
            showFileAttachSpouse1();
        }
        else if (spouseChoice == 2) {
            showFileAttachSpouse2();
        }         
        else {
            $("div.spouse-file-section").html("");
        }
    });

    function showFileAttachSpouse1() {
        $.ajax({
            url: "{{ route('ajax-file-attach-spouse-1') }}", 
            method: 'GET',
            success: function(data, status) {
            $("div.spouse-file-section").html("");
            $("div.spouse-file-section").html(data.options);
            }
        }); 
    }

    function showFileAttachSpouse2() {
        $.ajax({
            url: "{{ route('ajax-file-attach-spouse-2') }}", 
            method: 'GET',
            success: function(data, status) {
            $("div.spouse-file-section").html("");
            $("div.spouse-file-section").html(data.options);
            }
        }); 
    }
</script>