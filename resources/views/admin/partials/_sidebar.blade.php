  
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{asset('img/generic-profile-icon-10.jpg')}}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->name}}</p>
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
        <li class="header"><i class="fa fa-tachometer"></i> ADMIN OPERATIONS</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="{{ Request::is('admin/users') ? "active" : ""}}"><a href="{{ route('users.index') }}"><i class="fa fa-users"></i> <span>User Administration</span></a></li>
        
        @hasrole('Admin')
        <li class="{{ Request::is(route('system-index')) ? "active" : ""}}"><a href="{{ route('system-index') }}"><i class="fa fa-cogs"></i> <span>System Operations</span></a></li>
        @endhasrole

        @hasrole('Admin')
        <li class="{{ Request::is(route('billing-index')) ? "active" : ""}}"><a href="{{ route('billing-index') }}"><i class="fa fa-money"></i> <span>Billing</span></a></li>
        @endhasrole

        <li class="{{ Request::is('admin-stats/stats') ? "active" : ""}}"><a href="{{ route('stats') }}"><i class="fa fa-bar-chart"></i> <span>Admin Stats</span></a></li>

        <li class="treeview {{ Request::is('admin/preenrolment') ? "active" : ""}}">
          <a href="#"><i class="fa fa-file-o"></i> <span>Enrolment Forms</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('preenrolment.index') }}">View All</a></li>
          </ul>
        </li>  

        <li class="treeview {{ Request::is('admin/placement-form') ? "active" : ""}}">
          <a href="#"><i class="fa fa-file"></i> <span>Placement Test Forms</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('placement-form.index') }}">View All</a></li>
          </ul>
        </li>  

        <li class="treeview {{ Request::is('admin/selfpayform*') ? "active" : ""}}">
          <a href="#"><i class="fa fa-euro"></i> <span>Validate Payment</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('selfpayform.index') }}">Validate Enrolment Payment</a></li>
            <li><a href="{{ route('index-placement-selfpay') }}">Validate Placement Payment</a></li>
          </ul>
        </li> 

        <li class="{{ Request::is('admin/terms*') ? "active" : ""}}"><a href="{{ route('terms.index') }}"><i class="fa fa-snowflake-o"></i> <span>Terms</span></a></li>
        <li class="{{ Request::is('admin/courses*') ? "active" : ""}}"><a href="{{ route('courses.index') }}"><i class="fa fa-book"></i> <span>Course Catalogue</span></a></li>
        <li class="{{ Request::is('admin/schedules*') ? "active" : ""}}"><a href="{{ route('schedules.index') }}"><i class="fa fa-clock-o"></i> <span>Schedule (Day & Time)</span></a></li>
        <li class="{{ Request::is('admin/course-schedule*') ? "active" : ""}}"><a href="{{ route('course-schedule.index') }}"><i class="fa fa-calendar-o"></i> <span>Course + Schedule</span></a></li>
        <li class="{{ Request::is('admin/placement-schedule*') ? "active" : ""}}"><a href="{{ route('placement-schedule.index') }}"><i class="fa fa-calendar"></i> <span>Placement Test Schedule</span></a></li>
        <li class="{{ Request::is('admin/classrooms*') ? "active" : ""}}"><a href="{{ route('classrooms.index') }}"><i class="fa fa-pencil-square-o"></i> <span>Classes</span></a></li>
        <li class="{{ Request::is('admin/rooms*') ? "active" : ""}}"><a href="{{ route('rooms.index') }}"><i class="fa fa-building-o"></i> <span>Rooms</span></a></li>
        <li class="{{ Request::is('admin/teachers*') ? "active" : ""}}"><a href="{{ route('teachers.index') }}"><i class="fa fa-pied-piper-alt"></i> <span>Teachers</span></a></li>

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
        <li class="{{ Request::is('admin/email-manager*') ? "active" : ""}}"><a href="{{ route('mailTracker_Index') }}" target="_blank"><i class="fa fa-envelope"></i> <span>Emails</span></a></li>
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
  