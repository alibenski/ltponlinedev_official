@component('mail::message')
 <p> Dear Manager, </p>
 <p> Staff Member {{ $staff->name }} would like to enrol in CLM language course: {{ $input_course->courses->Description }}</p>
 <p> with the following class schedule(s):</p>
 <ul>
 @foreach($input_schedules as $schedules)	
 <li>{{ $schedules->schedule->name }}</li>
 @endforeach
 </ul>
 <p> Please click on the button link below to approve or not.</p>

@component('mail::button', ['url' => config('app.url')'/myform/'.Crypt::encrypt($input_course->id).'/edit' ])
Approve
@endcomponent

@component('mail::panel')
 The information contained in this e-mail...
@endcomponent

Thank you and kind regards,<br>
{{ config('app.name') }}
@endcomponent
