<style type="text/css">

.tab { margin-left: 40px; }
section { font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;  }
</style>
<section>
	
	<h3>CLM Language Online Registration Release Notes</h3>
	<h4>Release Notes: 31 December 2018</h4>

	<li>sorting of waitlist - debugged orderBy priority and submission date</li>
	<li>waitlist count is not automatically updated - refresh manually</li>
	<li>classlist for teachers on 31 December</li>
	<li>pending payment students / late approval of payments inserted to waitlisted classes</li>
	<li>admin comments when moving students</li>
	<li>link stat counter to term in admin dashboard</li>
	<li>Moving of students directly in PASH Table</li>
	<li>Table showing Classroom count each</li>
	

	<h4>Release Notes: 11 January 2019</h4>

	<li>Copy 189 Term Results</li>
	<li>Quick edit of existing teachers</li>
	<li>Create teacher accounts and permissions</li>
		<li class="tab">Clean teachers table - update email and index no.</li>
	<li>Create view of classes</li>
	<li>Create attendance tracking view</li>
	<li>Create Release Notes page</li>


	<h4>Release Notes: 11 February 2019 - Preparation for 194 Enrollment</h4>

<li>Control next level of student - No need teachers assign the appropriate course</li>
{{-- <li>If student cancels his class this term, how will system react next term? Force him to placement form or regular form? </li> --}}
<li>Student can edit form and update attachments too via link provided by language admin</li>
	<li class="tab">Students could update attachments in selfpayment forms - Daniel</li>
	<li class="tab">Set-up email process to send link to upload attachment files when validating payments</li>
	<li class="tab">Add controls and validation when accessing the page</li>
<li>Disapprove admin button/function for new user requests</li>
<li>Update My Profile view to include the PROFILE field and DEPT field separately (case: Interns)</li>
<li>Update User table (not only SDDEXTR) when students update their profile and name</li>
<li>Change of text in the enrolment forms and website as suggested by teachers (English)</li>
	<li class="tab">Update FAQ to mention the valid status of online payment</li>
<li>From Carol W, text on the placement form for English : "If you are in Geneva, please select one of the dates shown. If you are outside Geneva, please select the online option"</li>
<li>Students form overall approval status field for easy query</li>
	<li class="tab">Overall approval status legend:</li>
		<li class="tab">○ 1 - approved </li>
		<li class="tab">○ 0 - disapproved</li>
		<li class="tab">○ null - waiting/pending decision from HR focal point/Lang Admin</li>
		<li class="tab">○ 2 (only for self-payment) - pending valid payment document</li>
	<li class="tab">add approved status field in Enrolment and Placement tables</li>
	<li class="tab">Overall approval status of Self-payment forms depend on Lang Admin Secretariat </li>
<li>Check box disclaimer of manager approval instead of emailing the manager</li>
	<li class="tab">Get text of disclaimer</li>
	<li class="tab">Change process flow (code) - no email for UNOG staff, only to HR focal points - Controller logic</li>
	<li class="tab">Change email template (white background) - regular and placement</li>
	<li class="tab">Change mail class variables - approval email to HR</li>
	<li class="tab">Change mail class variables - notify student via email about HR decision</li>
	<li class="tab">Change approval reminder CRON settings to exclude manager email</li>
<li>Comment box in enrolment form</li>
	<li class="tab">Add comment field in enrolment table - std_comments</li>
	<li class="tab">Admin view std_comments from regular enrolment forms</li>
	<li class="tab">Include field in fillable array in model</li>
<li>Enter text explaining 1 form is for 1 language before submit button</li>
<li>Explanation of "flexible" button</li>
	<li class="tab">Get text</li>
<li>Selfpay approval status in placement form view info</li>
<li>Include Term details in email subject template of HR FP's </li>
<li>Hotfix: Attendance Remarks shows in other weeks</li>
<li>Hotfix: Multiple classes due to incorrect db input</li>
<li>Hotfix: Te_Code fetch of course description is null</li>
<li>Hotfix: Teacher select week and manage attendance ErrorException </li>
<li>Take out L from attendance choices</li>
<li>Redesign eform2 HR approval confirmation page;</li>
<li>Additional enrolment values in Admin Dashboard</li>
<li>Show placement test status on Submitted Forms page of the student</li>
<li>Check ajax method that checks if student was enrolled the past 2 terms including deleted_at field</li>
<li>Check ajax method that checks if student priority including deleted_at field</li>
{{-- <li>DPKO is non-paying?</li>
<li>OSE Syria to be added in Org list?</li> --}}
<li>Term create and edit views/controller methods updated</li>
<li>Complete creation of new course in controller</li>
<li>Edit in CourseController</li>

	<h4>Development Freeze: 11 Feb - 6 Mar 2019</h4>
	<li>Development freeze on Online Enrolment Platform (Student Access and Views) - Hot fixes on issues only</li>

	<h4>Release Notes: 15 March 2019</h4>

	<li>Teachers' view</li>
		<li class="tab">Results view but with grades selection: Pass, Failed, No show, Incomplete.</li> 
		<li class="tab">3 additional boxes for oral, written and overall results - Virginie</li>
		<li class="tab">Assigning to next term course</li>
		<li class="tab">Attendance view: have an overall summary of the attendance of the student.</li> 
		<li class="tab">See who has enrolled in attendance view - Fabienne</li>
	<li>Admin logs in as User/Teacher to see assigned classrooms and attendance</li>
	<li>Admin/ Teacher focal points can see assigned classrooms and attendance</li>
	<li>Classes page includes students assigned</li>
	<li>Complete self-payment admin view to show approved, pending, disapproved, and to also show cancelled regular and placement forms</li>
	
</section>
