/* javascript for profile and organization auto assignment
    when editing student profile
    only for external profiles e.g. MSU, NGO, etc.
*/
function getCountry() {
    $.ajax({
        url: "{{ route('ajax-select-country') }}", 
        method: 'GET',
        success: function(data, status) {
            console.log(data)
        $("#countrySection").html("");
        $("#countrySection").html(data.options);
        }
    });  
}

$('select[id="profile"]').on('change', function () {
    $("#step2").removeClass("d-none")

    if ($("input[name='decision']").is(':checked')) {
        console.log("do nothing")
    } else {
            // reset organization field values
        $('select[name="organization"].select2-basic-single').val([]).trigger('change');
        $('select[name="organization"].select2-basic-single').select2({
            placeholder: "Select Organization",
        });
        $("select[name='organization'] option").prop('disabled', false);
        $("input[name='decision']").prop("checked", false);
            // reset div sections 
        $('#orgSelect').html("");
        $("#countrySection").html("");
        $("#ngoSection").html("");
        $('#orgSelect').fadeIn(300);
        
        let dProfile = $(this).val();
        console.log('Profile selected: ' + dProfile);

        const arrayProfile1 = ["STF", "INT", "CON", "WAE", "JPO", "SPOUSE", "RET"];
        let dCheckProfile = $.inArray(dProfile, arrayProfile1);
        
        if (dCheckProfile != -1 ) {
            $("input[name='decision']").prop("checked", false);
            $('#orgSelect').html("");
            $("#countrySection").html("");
            $("#ngoSection").html("");
            $('#hiddenSection').removeClass("d-none");
            $('#hiddenDecision').removeClass("d-none");

            // check if organization is NOT one of the ff: MSU, NGO, etc.
            const arrayOrganization = ["MSU", "999", "RET", "SERV", "NGO", "PRESS"];
            let dCurrentOrganization = $("input[name='currentOrganization']").val();
            let dCheckOrganization = $.inArray(dCurrentOrganization, arrayOrganization);

            if (dCheckOrganization != -1) {
                alert("Change organization needed.");
                $('#hiddenDecision').addClass("d-none");
                $.get("/org-select-ajax", function(data) {
                    $('#orgSelect').html(data);
                    $(document).find('.select2-basic-single').select2();
                    // disable MSU, NGO, etc options from Org select dropdown
                    // $("select[name='organization'] option[value='MSU']").prop('disabled', true);
                    // $("select[name='organization'] option[value='999']").prop('disabled', true);
                    // $("select[name='organization'] option[value='RET']").prop('disabled', true);
                    // $("select[name='organization'] option[value='SERV']").prop('disabled', true);
                    // $("select[name='organization'] option[value='NGO']").prop('disabled', true);
                    // $("select[name='organization'] option[value='PRESS']").prop('disabled', true);
                    // make organization select required    
                    $("select[name='organization']").attr("required", true);
                });

            }            
        } else {
            $.get("/org-select-ajax", function(data) {
                $('#orgSelect').html(data);
                $(document).find('.select2-basic-single').select2();
                $('#selectInput').val('1');
                console.log($('#selectInput').val());
                $('#hiddenSection').removeClass("d-none");
                $('#hiddenDecision').addClass("d-none");
                $("select[name='organization']").attr("required", true);
                
                if (dProfile === 'MSU') { // staff from mission
                    $("input[name='decision']").prop("checked", false);              
                    $("select[name='organization'] option[value='MSU']").prop('selected', true);
                    $("select[name='organization'] option").not(':selected').prop('disabled', true);
                    $("select[name='organization']").val('MSU').trigger('change');
                    console.log('Org selected: ' + $('select[name="organization"]').val());
            
                    $("#countrySection").html("");
                    $("#ngoSection").html("");
                    getCountry();
                    alert("Organization will be automatically chosen for this profile. Please select country.");
                    // var dOrg = $('select[name="org"]').val();
                    // var dDecision = $('input[name="decision"]:checked').val();
                    // var token = $('meta[name=csrf-token]').attr('content');
                    // orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
                    
                    }

                    // if (dProfile === 'SPOUSE') { // spouse
                    // $("input[name='decision']").prop("checked", false);
                    // $("select[name='organization'] option[value='999']").prop('selected', true);
                    // $("select[name='organization'] option").not(':selected').prop('disabled', true);
                    // $("select[name='organization']").val('999').trigger('change');
                    // console.log('Org selected: ' + $('select[name="organization"]').val());
                
                    
                    // $("#countrySection").html("");
                    // $("#ngoSection").html("");
                    // alert("Organization will be automatically chosen for this profile.");
                    // // console.log($('select[name="org"]').val());
                    // // var dOrg = $('select[name="org"]').val();
                    // // var dDecision = $('input[name="decision"]:checked').val();
                    // // var token = $('meta[name=csrf-token]').attr('content');
                    // // orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
                    // }
                    // if (dProfile === 'RET') { // retired UN staff
                    // $("input[name='decision']").prop("checked", false);
                    // $("select[name='organization'] option[value='RET']").prop('selected', true);
                    // $("select[name='organization'] option").not(':selected').prop('disabled', true);
                    // $("select[name='organization']").val('RET').trigger('change');
                    // console.log('Org selected: ' + $('select[name="organization"]').val());
                
                    
                    // $("#countrySection").html("");
                    // $("#ngoSection").html("");
                    // alert("Organization will be automatically chosen for this profile.");
                    // // console.log($('select[name="org"]').val());
                    // // var dOrg = $('select[name="org"]').val();
                    // // var dDecision = $('input[name="decision"]:checked').val();
                    // // var token = $('meta[name=csrf-token]').attr('content');
                    // // orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
                    // }
                    if (dProfile === 'SERV') { // staff from service orgs in Palais
                    $("input[name='decision']").prop("checked", false);
                    $("select[name='organization'] option[value='SERV']").prop('selected', true);
                    $("select[name='organization'] option").not(':selected').prop('disabled', true);
                    $("select[name='organization']").val('SERV').trigger('change');
                    console.log('Org selected: ' + $('select[name="organization"]').val());
                
                    
                    $("#countrySection").html("");
                    $("#ngoSection").html("");
                    alert("Organization will be automatically chosen for this profile.");
                    // console.log($('select[name="org"]').val());
                    // var dOrg = $('select[name="org"]').val();
                    // var dDecision = $('input[name="decision"]:checked').val();
                    // var token = $('meta[name=csrf-token]').attr('content');
                    // orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
                    }
                    if (dProfile === 'NGO') { // staff from NGO
                    $("input[name='decision']").prop("checked", false);
                    $("select[name='organization'] option[value='NGO']").prop('selected', true);
                    $("select[name='organization'] option").not(':selected').prop('disabled', true);
                    $("select[name='organization']").val('NGO').trigger('change');
                    console.log('Org selected: ' + $('select[name="organization"]').val());
                
                    
                    $("#countrySection").html("");
                    $("#ngoSection").html("");
                
                    $("#ngoSection").html("<div class='col-md-12'><div class='form-group row'><label for='ngoName' class='col-md-12 control-label text-danger'>NGO Name: <span style='color: red'><i class='fa fa-asterisk' aria-hidden='true'></i> required field</span> </label><div class='col-md-12'><input id='ngoName' type='text' class='form-control' name='ngoName' placeholder='Enter NGO agency name' required></div></div></div>");
                
                    alert("Organization will be automatically chosen for this profile. Please enter NGO name.");
                    // console.log($('select[name="org"]').val());
                    // var dOrg = $('select[name="org"]').val();
                    // var dDecision = $('input[name="decision"]:checked').val();
                    // var token = $('meta[name=csrf-token]').attr('content');
                    // orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
                    }
                    if (dProfile === 'PRESS') { // staff from PRESS
                    $("input[name='decision']").prop("checked", false);
                    $("select[name='organization'] option[value='PRESS']").prop('selected', true);
                    $("select[name='organization'] option").not(':selected').prop('disabled', true);
                    $("select[name='organization']").val('PRESS').trigger('change');
                    console.log('Org selected: ' + $('select[name="organization"]').val());
                
                    
                    $("#countrySection").html("");
                    $("#ngoSection").html("");
                    alert("Organization will be automatically chosen for this profile.");
                    // console.log($('select[name="org"]').val());
                    // var dOrg = $('select[name="org"]').val();
                    // var dDecision = $('input[name="decision"]:checked').val();
                    // var token = $('meta[name=csrf-token]').attr('content');
                    // orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
                    }

                }); 
            
            
        }

    }

});
