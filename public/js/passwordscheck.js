$(document).ready(function() {
  $("#password").keyup(function() {
    $("#result").html(checkStrength($("#password").val()));
  });
  function checkStrength(password) {
    var strength = 0;
    const firstName = $("input#firstName").val();
    const lastName = $("input#lastName").val();
    const email = $("input#email").val();
    const currentPassword = $("input#current_password").val();
    const defaultString = "Welcome2CLM";

    if (
      password.toLowerCase().match(defaultString.toLocaleLowerCase()) ||
      password.match(currentPassword)
    ) {
      $("#result").removeClass();
      $("#result").addClass("short");
      $("button.btn-submit").attr("disabled", true);
      return "New password cannot contain the default/initial/current password.";
    }
    if (
      password.toLowerCase().match(firstName.toLowerCase()) ||
      password.toLowerCase().match(lastName.toLowerCase()) ||
      password.toLowerCase().match(email.toLowerCase())
    ) {
      $("#result").removeClass();
      $("#result").addClass("short");
      $("button.btn-submit").attr("disabled", true);
      return "Password cannot contain parts of your name/email.";
    }
    if (password.length < 11) {
      $("#result").removeClass();
      $("#result").addClass("short");
      $("button.btn-submit").attr("disabled", true);
      return "Password too short";
    }
    if (password.length > 11) strength += 1;
    // If password contains both lower and uppercase characters, increase strength value.
    if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1;
    // If it has numbers and characters, increase strength value.
    if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))
      strength += 1;
    // If it has one special character, increase strength value.
    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1;
    // If it has two special characters, increase strength value.
    if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/))
      strength += 1;
    // Calculated strength value, we can return messages
    // If value is less than 2
    console.log(strength);
    if (strength < 2) {
      $("#result").removeClass();
      $("#result").addClass("weak");
      $("button.btn-submit").attr("disabled", true);
      return "Weak. Needs more complexity.";
    } else if (strength == 2) {
      $("#result").removeClass();
      $("#result").addClass("good");
      $("button.btn-submit").attr("disabled", false);
      return "Good";
    } else {
      $("#result").removeClass();
      $("#result").addClass("strong");
      $("button.btn-submit").attr("disabled", false);
      return "Strong";
    }
  }
});
