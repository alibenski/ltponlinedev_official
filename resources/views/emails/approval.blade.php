@component('mail::message')
 <p> Dear Manager, </p>
 <p> Staff Member would like to enrol to CLM language course:</p>
 <p>Course Schedule A {{ $input->courses->Description }}</p>
 <p> Please click on the button link below to approve or not.</p>

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

@component('mail::panel')
 The information contained in this e-mail...
@endcomponent

Thank you and kind regards,<br>
{{ config('app.name') }}
@endcomponent
