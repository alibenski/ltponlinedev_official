<html>
  <head>
    <title>reCAPTCHA demo: Explicit render after an onload callback</title>
    @include('partials._head')
    <!-- Include script -->
   <script type="text/javascript">
   function callbackThen(response) {

     // read Promise object
     response.json().then(function(data) {
       console.log(data);
       if(data.success && data.score >= 0.6) {
          console.log('valid recaptcha');
       } else {
          document.getElementById('contactForm').addEventListener('submit', function(event) {
             event.preventDefault();
             alert('recaptcha error');
          });
       }
     });
   }

   function callbackCatch(error){
      console.error('Error:', error)
   }
   </script>

   {!! htmlScriptTagJsApi([
      'callback_then' => 'callbackThen',
      'callback_catch' => 'callbackCatch',
   ]) !!}
  </head>
  <body>
    <form action="{{ route('post-captcha') }}" method="POST" id="contactForm">
        {{ csrf_field() }}
      <div id="html_element"></div>
      <br>
      <input type="submit" value="Submit">
    </form>
  </body>
</html>