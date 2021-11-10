$('select[id="profile"]').on('change', function () {
  $('div#orgSection').removeClass('d-none');
  $('select[name="org"].select2-basic-single').val([]).trigger('change');
  $('select[name="org"].select2-basic-single').select2({
    placeholder: "Select Organization",
  });
  $("select[name='org'] option").prop('disabled', false);

  $('#orgSelect').fadeIn(300);
  
  var dProfile = $(this).val();
  console.log('Profile selected: ' + dProfile);

  if (dProfile === 'MSU') { // staff from mission
    $("select[name='org'] option[value='MSU']").prop('selected', true);
    $("select[name='org'] option").not(':selected').prop('disabled', true);
    $('.select2-basic-single').val('MSU').trigger('change');
    console.log($('select[name="org"]').val());
    var dOrg = $('select[name="org"]').val();
    var dDecision = $('input[name="decision"]:checked').val();
    var token = $('meta[name=csrf-token]').attr('content');
    orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
  }
  if (dProfile === 'SPOUSE') { // spouse
    $("select[name='org'] option[value='999']").prop('selected', true);
    $("select[name='org'] option").not(':selected').prop('disabled', true);
    $('.select2-basic-single').val('999').trigger('change');
    console.log($('select[name="org"]').val());
    var dOrg = $('select[name="org"]').val();
    var dDecision = $('input[name="decision"]:checked').val();
    var token = $('meta[name=csrf-token]').attr('content');
    orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
  }
  if (dProfile === 'RET') { // retired UN staff
    $("select[name='org'] option[value='RET']").prop('selected', true);
    $("select[name='org'] option").not(':selected').prop('disabled', true);
    $('.select2-basic-single').val('RET').trigger('change');
    console.log($('select[name="org"]').val());
    var dOrg = $('select[name="org"]').val();
    var dDecision = $('input[name="decision"]:checked').val();
    var token = $('meta[name=csrf-token]').attr('content');
    orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
  }
  if (dProfile === 'SERV') { // staff from service orgs in Palais
    $("select[name='org'] option[value='SERV']").prop('selected', true);
    $("select[name='org'] option").not(':selected').prop('disabled', true);
    $('.select2-basic-single').val('SERV').trigger('change');
    console.log($('select[name="org"]').val());
    var dOrg = $('select[name="org"]').val();
    var dDecision = $('input[name="decision"]:checked').val();
    var token = $('meta[name=csrf-token]').attr('content');
    orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
  }
  if (dProfile === 'NGO') { // staff from NGO
    $("select[name='org'] option[value='NGO']").prop('selected', true);
    $("select[name='org'] option").not(':selected').prop('disabled', true);
    $('.select2-basic-single').val('NGO').trigger('change');
    console.log($('select[name="org"]').val());
    var dOrg = $('select[name="org"]').val();
    var dDecision = $('input[name="decision"]:checked').val();
    var token = $('meta[name=csrf-token]').attr('content');
    orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
  }
  if (dProfile === 'PRESS') { // staff from PRESS
    $("select[name='org'] option[value='PRESS']").prop('selected', true);
    $("select[name='org'] option").not(':selected').prop('disabled', true);
    $('.select2-basic-single').val('PRESS').trigger('change');
    console.log($('select[name="org"]').val());
    var dOrg = $('select[name="org"]').val();
    var dDecision = $('input[name="decision"]:checked').val();
    var token = $('meta[name=csrf-token]').attr('content');
    orgCompare(dProfile, dOrg, dDecision, token); // execute orgCompare function
  }
});