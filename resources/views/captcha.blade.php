<html>
  <head>
    <title>reCAPTCHA demo: Explicit render after an onload callback</title>
    @include('partials._head')
    <script type="text/javascript">
      var onloadCallback = function() {
        grecaptcha.render('html_element', {
          'sitekey' : '6LcewFQUAAAAAG149ethHDXTtrslT39aTAUpojoY'
        });
      };
    </script>
  </head>
  <body>
    <form action="{{ route('post-captcha') }}" method="POST">
        {{ csrf_field() }}
      <div id="html_element"></div>
      <br>
      <input type="submit" value="Submit">
    </form>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>
  </body>
</html>