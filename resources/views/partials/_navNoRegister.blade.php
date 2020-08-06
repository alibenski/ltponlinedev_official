<nav class="navbar navbar-light bg-light">
    <div class="container d-flex">
        <span class="navbar-brand mb-0 h1 justify-content-start">
            <a class="navbar-brand" href="{{ url('/') }}">
                UNOG CLM LTP Online Enrolment
            </a>
        </span>

        <span class="navbar-brand mb-0 h1 justify-content-end">
            <!-- Authentication Links -->
            @if (Auth::guest())
                <a class="navbar-brand" href="{{ route('login') }}">Login</a>
            @else
                <div class="dropdown">
                <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span><i class="fa fa-lg fa-user-circle btn-space" aria-hidden="true"></i></span>
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    
                    @role('Admin') {{-- Laravel-permission blade helper --}}
                        <a href="{{ route('admin_dashboard') }}" class="dropdown-item">
                            <span><i class="fa fa-btn fa-unlock btn-space align-middle" aria-hidden="true"></i></span>
                            Admin Page
                        </a>
                    @endrole
                    @hasrole('Teacher')
                        <a href="{{ route('teacher-dashboard') }}" class="dropdown-item"><i class="fa fa-btn fa-pied-piper-alt btn-space" aria-hidden="true"></i>Teacher Admin Page</a>
                    @endhasrole
                
                
                    <a href="{{ route('students.edit', Auth::user()->id)}}" class="dropdown-item"><i class="fa fa-btn fa-edit btn-space" aria-hidden="true"></i>Edit Profile</a>
                
                
                    <a href="{{ route('logout') }}" class="dropdown-item"
                        onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"><i class="fa fa-btn fa-sign-out btn-space" aria-hidden="true"></i>
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                    
                </div>
            </div>
            @endif
        </span>
    </div>
</nav>
