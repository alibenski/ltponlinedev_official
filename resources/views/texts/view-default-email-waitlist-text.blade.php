  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('adminLTE3/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminLTE3/dist/css/adminlte.min.css') }}">

<div class="col-md-12 m-3 p-3">
    <form>
        <div class="form-group">
            <div class="col-md-12">
                <h4 class="text-center eczar"><strong>Viewing:</strong> Default Waitlist Email Notification </h4>
            </div>      
            <hr />                
        </div>
    </form>
</div>

<div class='container'>
        <div class="form-group">
            <div class="col-md-12">
                <h4 class="text-center eczar"><label for="subject">Subject:</label> Waiting List Notification {{ $term->Comments }} {{ $year }}</h4> 
            </div>                      
        </div>
</div>

@include('emails.defaultEmailWaitlist')
