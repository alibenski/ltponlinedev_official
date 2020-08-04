<!DOCTYPE html>
<html>
<head>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-170278635-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-170278635-1');
  </script>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="referrer" content="origin-when-cross-origin">
  <title>CLM Online Writing Tips</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="{{ asset('dist/css/skins/_all-skins.min.css') }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
  <!-- Custom styles for this template -->
  @yield('customcss')

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="{{ asset('jquery-ui-1.12.1/jquery-ui.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</head>

<body class="hold-transition skin-black-light sidebar-collapse">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="{{ route('teacher-dashboard') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>C</b>LM</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><i class="fa fa-external-link"></i><b> CLM SMS </b></span>
    </a> 

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="{{asset('img/generic-profile-icon-10.jpg')}}" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="{{asset('img/generic-profile-icon-10.jpg')}}" class="img-circle" alt="User Image">

                <p>
                  {{Auth::user()->name}}
                  <small>Member since {{ date('d M Y', strtotime(Auth::user()->created_at))}}</small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ route('students.index') }}" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-left">
                  <a href="{{ route('teacher-dashboard') }}" class="btn btn-default btn-flat">SMS</a>
                </div>
                <div class="pull-right">
                  <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <span>Sign out</span>
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                  {{ csrf_field() }}
                  </form>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            {{-- <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a> --}}
          </li>
        </ul>
      </div>
    </nav>
  </header>

  @include('writing_tips.partials._sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background-color: #f9fafc;">
    <!-- Content Header (Page header) -->
    <div class="col-md-10 col-md-offset-1"> 
    <section class="content-header">
        <h1>
          <a href="/admin/writing-tips"><i class="fa fa-home"></i> Writing Tips </a>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
      @if(Session::has('flash_message'))
          <div class="container">      
              <div class="alert alert-success"><em> {!! session('flash_message') !!}</em>
              </div>
          </div>
      @endif 

      <div class="row">
          <div class="col-md-8 col-md-offset-2 message">              
              @include ('partials._messages') {{-- Include session error messages --}}
          </div>
      </div>

      @yield('content')     

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->
  @yield('java_script')
</body>
</html>