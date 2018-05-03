  
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{asset('img/spideyman_sample.png')}}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Peter Parker</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- search form (Optional) -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
        </div>
      </form>
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">OPERATIONS</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="{{ route('admin_dashboard') }}"><i class="fa fa-tachometer"></i> <span>Admin Dashboard</span></a></li>
        <li class=""><a href="{{ route('users.index') }}"><i class="fa fa-users"></i> <span>User Admin</span></a></li>
        <li class=""><a href="{{ route('preenrolment.index') }}"><i class="fa fa-users"></i> <span>Enrolment Forms</span></a></li>
        <li class=""><a href="#"><i class="fa fa-users"></i> <span>Placement Test Forms</span></a></li>
        <li class=""><a href="{{ route('course-schedule.index') }}"><i class="fa fa-calendar"></i> <span>Pre-enrolment Schedule</span></a></li>
        <li class=""><a href="{{ route('placement-schedule.index') }}"><i class="fa fa-calendar"></i> <span>Placement Test Schedule</span></a></li>
        <li class="treeview">
          <a href="#"><i class="fa fa-book"></i> <span>Courses</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('courses.index') }}">View All</a></li>
            <li><a href="{{ route('courses.create') }}">Create</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-snowflake-o"></i> <span>Terms</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('terms.index') }}">View All</a></li>
            <li><a href="{{ route('terms.create') }}">Create</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-clock-o"></i> <span>Schedule (Day & Time)</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('schedules.index') }}">View All</a></li>
            <li><a href="{{ route('schedules.create') }}">Create</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-building"></i> <span>Classes</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">View All</a></li>
            <li><a href="#">Create</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-globe"></i> <span>Organizations</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">View All</a></li>
            <li><a href="#">Create</a></li>
          </ul>
        </li>        
        <li class="treeview">
          <a href="#"><i class="fa fa-handshake-o"></i> <span>Focal Points</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">View All</a></li>
            <li><a href="#">Create</a></li>
          </ul>
        </li>
        <li><a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa fa-btn fa-sign-out btn-space" aria-hidden="true"></i>
              <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
            </form>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
  