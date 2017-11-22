@component('mail::message')
 <p> Dear Manager, </p>
 <p> Staff Member {{ $user->name }} would like to enrol to a CLM language course.</p>
 <p> Please click on the link below to approve or not.</p>
@component('mail::panel')
 The information contained in this website is for general information purposes only. The information is provided by Gamesstation, while we endeavour to keep the information up to date and correct, we make no representations or warranties of any kind. Any reliance you place on such information is strictly at your own risk.
@endcomponent
@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thank you and kind regards,<br>
{{ config('app.name') }}
@endcomponent
