
	<ul class="nav nav-tabs">
		@if (Auth::user()->id == 3292)
		<li role="presentation" class="{{ Request::is('*manage-user-enrolment-data-by-history') ? "active" : ""}}"><a href="/admin/user/{{$student->id}}/manage-user-enrolment-data-by-history">LTP Data by History</a></li>
		<li role="presentation" class="{{ Request::is('*manage-user-enrolment-data') ? "active" : ""}}"><a href="/admin/user/{{$student->id}}/manage-user-enrolment-data">LTP Data by Term</a></li>
		@else	
		<li role="presentation" class="{{ Request::is('*manage-user-enrolment-data') ? "active" : ""}}"><a href="/admin/user/{{$student->id}}/manage-user-enrolment-data">LTP Data by Term</a></li>
		<li role="presentation" class="{{ Request::is('*manage-user-enrolment-data-by-history') ? "active" : ""}}"><a href="/admin/user/{{$student->id}}/manage-user-enrolment-data-by-history">LTP Data by History</a></li>
		@endif
		{{-- <li role="presentation" class="{{ Request::is('submitted') ? "active" : ""}}"><a href="/submitted">Current Submitted Forms</a></li> --}}
		{{-- <li role="presentation" class="{{ Request::is('previous-submitted') ? "active" : ""}}"><a href="/previous-submitted">Submitted Forms</a></li>
		<li role="presentation" class="{{ Request::is('history') ? "active" : ""}}"><a href="/history">History</a></li> --}}
	
		{{-- <li role="presentation" class="pull-right {{ Request::is('whatorg') ? "active" : ""}}"><a href="/whatorg"><strong><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Register/Enrol Here</strong></a></li> --}}
		<!--<li class="dropdown">
			<button type="button" href="#" id="BtnDropdown" class="btn btn-info dropdown-toggle" data-toggle="dropdown"> Enrolment Forms <b class="caret"></b></button>
			<ul class="dropdown-menu" role="menu" aria-labelledby="BtnDropdown">
					<li role="menu" class="{{-- Request::is('myform/create') ? "active" : ""--}} {{-- Request::is('noform/create') ? "active" : ""--}}"><a href="{{-- route('myform.create') --}}"  tabindex="-1">UN Staff Enrolment Form</a></li>
					<li role="menu" class="{{-- Request::is('selfpayform/*') ? "active" : ""--}}"><a href="{{-- route('selfpayform.create') --}}"  tabindex="-1">Self-Paying Enrolment Form</a></li>
			</ul>
		</li>-->
	</ul>