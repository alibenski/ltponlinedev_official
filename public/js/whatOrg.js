// whatOrg page Jquery
  $("input[name='decision']").click(function(){
      if($('#decision1').is(':checked')) {
        // reset select2 (4.0.3) value to NULL and show placeholder  
        $('.select-profile-single').val([]).trigger('change');
        $('#orgSelect').attr('style', 'display: none');
        $('.select2-basic-single').val([]).trigger('change');      
        $('a.next-link').replaceWith('<button id="formBtn" type="submit" class="btn btn-block button-prevent-multi-submit">Next</button>');
        $('button[type="submit"]').addClass( "btn-success", 500);
        $('#profileSelect, #secretMsg2').fadeOut(500);
        $('#secretMsg1, #profileSelect').fadeIn(800);
      } else if ($('#decision2').is(':checked')) {
        // reset select2 (4.0.3) value to NULL and show placeholder  
        $('.select-profile-single').val([]).trigger('change');
        $('#orgSelect').attr('style', 'display: none');
        $('.select2-basic-single').val([]).trigger('change');
        $('button[id="formBtn"]').replaceWith('<a class="btn btn-success next-link btn-default btn-block button-prevent-multi-submit">Next</a>');
        $('a.next-link').removeClass( "btn-success", 500);
        $('#profileSelect, #secretMsg1').fadeOut(500);
        $('#secretMsg2, #profileSelect').fadeIn(800);
      }
  });

  // on event change of Profile, show orgSelect
  $('select[id="profile"]').on('change', function() {
    $('.select2-basic-single').val([]).trigger('change');
    $('#orgSelect').fadeIn(300);
    var dProfile = $(this).val(); 
    console.log('Profile selected: ' + dProfile);

      if (dProfile === 'MSU') { // staff from mission
        $("select[id='input'] option[value='MSU']").prop('selected',true);
        $('.select2-basic-single').val('MSU').trigger('change');
          console.log($('select[id="input"]').val());
          var dOrg = $('select[id="input"]').val();
          var dDecision = $('input[name="decision"]:checked').val();
          var token = $('meta[name=csrf-token]').attr('content');
        orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
      }
      if (dProfile === 'SPOUSE') { // spouse
        $("select[id='input'] option[value='999']").prop('selected',true);
        $('.select2-basic-single').val('999').trigger('change');
          console.log($('select[id="input"]').val());
          var dOrg = $('select[id="input"]').val();
          var dDecision = $('input[name="decision"]:checked').val();
          var token = $('meta[name=csrf-token]').attr('content');
        orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
      }
      if (dProfile === 'RET') { // retired UN staff
        $("select[id='input'] option[value='RET']").prop('selected',true);
        $('.select2-basic-single').val('RET').trigger('change');
          console.log($('select[id="input"]').val());
          var dOrg = $('select[id="input"]').val();
          var dDecision = $('input[name="decision"]:checked').val();
          var token = $('meta[name=csrf-token]').attr('content');
        orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
      }
      if (dProfile === 'SERV') { // staff from service orgs in Palais
        $("select[id='input'] option[value='SERV']").prop('selected',true);
        $('.select2-basic-single').val('SERV').trigger('change');
          console.log($('select[id="input"]').val());
          var dOrg = $('select[id="input"]').val();
          var dDecision = $('input[name="decision"]:checked').val();
          var token = $('meta[name=csrf-token]').attr('content');
        orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
      }
      if (dProfile === 'PRESS') { // staff from PRESS NGO
        $("select[id='input'] option[value='PRESS']").prop('selected',true);
        $('.select2-basic-single').val('PRESS').trigger('change');
          console.log($('select[id="input"]').val());
          var dOrg = $('select[id="input"]').val();
          var dDecision = $('input[name="decision"]:checked').val();
          var token = $('meta[name=csrf-token]').attr('content');
        orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
      }
  });

  // ajax post on change event of Org dropdown
  $('select[id="input"]').change(function() {
      var dOrg = $('select[id="input"]').val();
      var dProfile = $('select[id="profile"]').val();
      var dDecision = $('input[name="decision"]:checked').val();
      var token = $('meta[name=csrf-token]').attr('content');
      orgCompare(dProfile, dOrg, dDecision, token);
  });  

  function orgCompare(dProfile, dOrg, dDecision, token) {
      // do not execute ajax and modal if value of selection is NULL
      if (dOrg != null) {
        $.post("/org-compare-ajax", { 'organization':dOrg, '_token':token }, function(response) {
              console.log(response[0]); // data
              console.log(response[1]['Org Full Name']); // torgan
              if (response[0] === false) {
                $('#modalshow').modal('show');
                $('input[id="textOrg"]').attr('value', response[1]['Org Full Name']);
                $('input[id="inputOrg"]').attr('name','organization');
                $('input[name="organization"]').attr('value', dOrg);
                $('input[id="inputProfile"]').attr('value', dProfile);
                $('input[id="inputDecision"]').attr('value', dDecision);
                console.log('profile: ' + dProfile);
                console.log('decision: ' + dDecision);
              } 
              if (response[0] === true) {
                $('select[id="input"]').attr('name','organization');
                $('a.next-link').replaceWith('<button id="formBtn" type="submit" class="btn btn-block button-prevent-multi-submit">Next</button>');
                $('button[type="submit"]').addClass( "btn-success", 800); 
                console.log('t profile: ' + dProfile);
                console.log('t decision: ' + dDecision);
              }
            });
      } else {
        console.log('orgSelect reset value to ' + dOrg);
      }
  }
