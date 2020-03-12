<nav class="navbar navbar-expand-sm bg-light navbar-light justify-content-end">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
    
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-navbar-collapse" aria-controls="app-navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    
        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto mt-2">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                @else
                    <div class="dropdown">
                        <a href="#" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span><i class="fa fa-lg fa-user-circle btn-space" aria-hidden="true"></i></span>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
    
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            
                            @role('Admin') {{-- Laravel-permission blade helper --}}
                                <a href="{{ route('admin_dashboard') }}">
                                    <span><i class="fa fa-btn fa-unlock btn-space dropdown-item align-middle" aria-hidden="true"></i></span>
                                    Admin Page
                                </a>
                            @endrole
                            @hasrole('Teacher')
                                <a href="{{ route('teacher-dashboard') }}"><i class="fa fa-btn fa-pied-piper-alt btn-space dropdown-item" aria-hidden="true"></i>Teacher Admin Page</a>
                            @endhasrole
                        
                        
                            <a href="{{ route('students.edit', Auth::user()->id)}}"><i class="fa fa-btn fa-edit btn-space dropdown-item" aria-hidden="true"></i>Edit Profile</a>
                        
                        
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();"><i class="fa fa-btn fa-sign-out btn-space dropdown-item" aria-hidden="true"></i>
                                Logout
                            </a>
    
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            
                        </div>
                    </div>
                @endif
            </ul>
        </div>
    </div>
</nav>

