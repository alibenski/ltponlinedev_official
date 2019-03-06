<style type="text/css">

.tab { margin-left: 40px; }
section { font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;  }
</style>
<section>
	
<h3>CLM Language Online Registration Release Notes</h3>

<p style="margin: 0in; font-family: Calibri; font-size: 14.0pt;"><span style="text-decoration: underline;">Release Notes: 28 March 2019</span></p>
<p style="margin: 0in; font-family: Calibri; font-size: 14.0pt;">&nbsp;</p>
<p style="color: #1f497d; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Schedule for special schedule with different dates - create</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;"><span style="font-weight: bold;">Hotfix</span>: Attendance for English Online course</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Batch function should include the teacher_comments field</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Debug Attendance summary queries letters &lt;, s, c results to<span style="font-weight: bold;"> Uncaught TypeError Cannot use 'in' operator to search for '0' </span></p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Check batch run if query does not consider selfpay_approval == 0 and 2</p>
<p style="margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">&nbsp;</p>

<p style="margin: 0in; font-family: Calibri; font-size: 14.0pt;"><span style="text-decoration: underline;">Release Notes: 15 March 2019</span></p>
<p style="margin: 0in; font-family: Calibri; font-size: 11.0pt;">&nbsp;</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Teachers' view</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Results view but with grades selection: Pass, Failed, No show, Incomplete.</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">3 additional boxes for oral, written and overall results - Virginie</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Overall Grade field to be added in Results table</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Assigning to next term course</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Add teacher comments in EOT results - separate field, separate table from Admin comments</p>
<p style="color: #000000; margin: 0in; margin-left: .75in; font-family: Calibri; font-size: 11.0pt;">Add field to 5 models: Preenrolment, PlacementForm, Preview, Repo, ModifiedForms</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Indicate if student has been assigned a course in table view</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Attendance view: have an overall summary of the attendance of the student (show number of presence, excuse, absence in show student view)</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">See who has enrolled in attendance view - Fabienne</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;"><strong>Hotfix</strong>: dropdown-toggle not initialized after click event of enter results</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;"><strong>Hotfix</strong>: Teacher clicks show student but has not logged attendance</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;"><strong>Hotfix</strong>: duplicate Enrolment Next Term in Enter Results</p>
<p style="color: #000000; margin: 0in; margin-left: .375in;"><strong><span style="font-family: Calibri; font-size: 11.0pt;">Hotfix</span></strong><span style="font-family: Calibri; font-size: 11.0pt;">: </span><span style="font-family: Helvetica; font-size: 10.5pt; color: #222222;">Call to a member function getAttributes() on array in TeachersController.php line 218 - remove array declaration of $arr</span></p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><span style="background: #D99694;">Lang Admin logs in as Teacher to see assigned classrooms and attendance</span></p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Teacher focal points can see assigned classrooms and attendance</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Complete self-payment filter to show approved, pending, waiting for admin payment status</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><span style="background: #D99694;">Create separate view for </span><span style="background: #D99694;">cancelled payment-based regular and placement forms</span></p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><span style="background: #D99694;">Create separate view for </span><span style="background: #D99694;">cancelled regular enrolment and placement forms</span></p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><strong>Hotfix</strong>: Student can update DOB field</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><strong>Hotfix</strong>: enrolment for index view changed to show per form submission without schedule</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><strong>Hotfix</strong>: Show student comment in Enrolment index view</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><span style="background: #D99694;">Admin adds comment to enrolment and placement forms - Fabienne</span></p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;"><span style="background: #D99694;">Field copied up to PASH table</span></p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Include Payment status in regular enrolment form, placement form view</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><strong>Hotfix</strong>: Bug on teacher login url missing admin string</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Cancel -&gt; "Delete" text instead : admin interface</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">!!! Connection of Enrolment/ Placement model to PASH model</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Copy submitted view in ManageUser view</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Create link to admin classroom management (Temporary pdf print out view)</p>
<p style="color: #1f497d; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;"><span style="text-decoration: line-through;">Hotfix: Students wants to take placement test but assigned to a class with no teacher - Daniel </span></p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Hotfix: exempt admin from limit-cancel middleware</p>
<p style="color: #1f497d; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;"><span style="background: #D99694;">Admins can leave a comment on enrolment, placement, pash tables and should be dynamic - Include in </span><strong><span style="text-decoration: underline; background: #D99694;">cancel method</span></strong><span style="background: #D99694;"> and button for pash preview class - Daniel</span></p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Cancel button for admins in ManageUser view - no email to be sent</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Show "deleted_at" field in all tables (enrolment, placement, pash, manageUser views) for billing</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Improve placement forms table view and filtering</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Overall valid/approved filter in placement forms to take the test</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Extract email of students who will take placement exam</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Filter pending payment placement forms</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Filter disapproved payment placement forms (field == 0)</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><span style="background: #D99694;">Improve Assign Course table view for Admin</span></p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Assign course table shows payment status</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Assign course table can filter payment-based forms only</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Delete "Convoke" button</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;"><span style="background: #D99694;">Assign Course function includes convoked question and admin comment when assigning a course</span></p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;"><span style="background: #D99694;">Query automatically Level 1 and not-in-class students who submitted regular forms</span></p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><strong>Hotfix</strong>: Placement forms overall_approval field not updated automatically when DEPT == UNOG, etc.</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Send reminder email to students in class that have not enrolled next term - F</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><strong>Hotfix:</strong> required attribute in file attachment - D</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Add Pending filter in Placement forms - one for pending HR approval another for pending Language Admin approval</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Add all <strong>Comments,&nbsp; Placement test date (and online for English), </strong><strong>Preferred days and schedules </strong>to simple table list page of approved placement forms</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Only show view button in regular enrolment view if std_comments exists</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Update User Management View to be the same as placement and regular form tables</p>

<p style="margin: 0in; font-family: Calibri; font-size: 11.0pt;">&nbsp;</p>
<p style="margin: 0in; font-family: Calibri; font-size: 14.0pt;"><span style="text-decoration: underline;">Freeze: 11 Feb - 6 Mar 2019</span></p>
<p style="margin: 0in; font-family: Calibri; font-size: 11.0pt;">&nbsp;</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Development freeze on Online Enrolment Platform (Student Access and Views) - Hot fixes on issues only</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">&nbsp;</p>
<p style="margin: 0in; font-family: Calibri; font-size: 14.0pt;"><span style="text-decoration: underline;">Release Notes: 11 February 2019 - Preparation for 194 Enrollment</span></p>
<p style="margin: 0in; font-family: Calibri; font-size: 14.0pt;">&nbsp;</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><span style="text-decoration: line-through;">Control next level of student </span>- No need teachers assign the appropriate course</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">If student cancels his class this term, how will system react next term? Force him to placement form or regular form?</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Student<span style="text-decoration: line-through;"> can edit form and</span> update attachments<span style="text-decoration: line-through;"> too </span>via link provided by language admin</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Students could update attachments in selfpayment forms - Daniel</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;"><span style="background: lime;">Set-up email process to send link to upload attachment files when validating payments</span></p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;"><span style="background: lime;">Add controls and validation when accessing the page</span></p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><span style="background: lime;">Disapprove admin button/function for new user requests</span></p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Update My Profile view to include the PROFILE field and DEPT field separately (case: Interns)</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Update User table (not only SDDEXTR) when students update their profile and name</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Change of text in the enrolment forms and website as suggested by teachers (English)</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;"><span style="text-decoration: line-through;">Update FAQ to mention the valid status of online payment</span></p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">From Carol W, text on the placement form for English : "If you are in Geneva, please select one of the dates shown. If you are outside Geneva, please select the online option"</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Students form overall approval status field for easy query</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Overall approval status legend:</p>
<ul style="margin-left: .75in; direction: ltr; unicode-bidi: embed; margin-top: 0in; margin-bottom: 0in;">
<li style="margin-top: 0; margin-bottom: 0; vertical-align: middle;"><span style="font-family: Calibri; font-size: 11.0pt;">1 - approved </span></li>
<li style="margin-top: 0; margin-bottom: 0; vertical-align: middle;"><span style="font-family: Calibri; font-size: 11.0pt;">0 - disapproved</span></li>
<li style="margin-top: 0; margin-bottom: 0; vertical-align: middle;"><span style="font-family: Calibri; font-size: 11.0pt;">null - waiting/pending decision from HR focal point/Lang Admin</span></li>
<li style="margin-top: 0; margin-bottom: 0; vertical-align: middle;"><span style="font-family: Calibri; font-size: 11.0pt;">2 (only for self-payment) - pending valid payment document</span></li>
</ul>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">add approved status field in Enrolment and Placement tables</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Overall approval status of Self-payment forms depend on Lang Admin Secretariat</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Check box disclaimer of manager approval instead of emailing the manager</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Get text of disclaimer</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Change process flow (code) - no email for UNOG staff, only to HR focal points - Controller logic</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Change email template (white background) - regular and placement</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Change mail class variables - approval email to HR</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Change mail class variables - notify student via email about HR decision</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;"><strong><span style="background: lime;">Change approval reminder CRON settings to exclude manager email</span></strong></p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Comment box in enrolment form</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Add comment field in enrolment table - std_comments</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;"><span style="background: lime;">Admin view std_comments from regular enrolment forms</span></p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Include field in fillable array in model</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Enter text explaining 1 form is for 1 language before submit button</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Explanation of "flexible" button</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Get text</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Selfpay approval status in placement form view info</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Include Term details in email subject template of HR FP's</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Hotfix: Attendance Remarks shows in other weeks</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Hotfix: Multiple classes due to incorrect db input</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Hotfix: Te_Code fetch of course description is null</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Hotfix: Teacher select week and manage attendance ErrorException</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Take out L from attendance choices</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Redesign eform2 HR approval confirmation page;</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Additional enrolment values in Admin Dashboard</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Show placement test status on Submitted Forms page of the student</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><em><span style="text-decoration: line-through;">Check ajax method that checks if student was enrolled the past 2 terms including deleted_at field</span></em></p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><em><span style="text-decoration: line-through;">Check ajax method that checks if student priority including deleted_at field</span></em></p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><span style="text-decoration: line-through;">DPKO is non-paying?</span></p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;"><span style="text-decoration: line-through;">OSE Syria to be added in Org list?</span></p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Term create and edit views/controller methods updated</p>
<p style="color: #1f497d; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Complete creation of new course in controller</p>
<p style="color: #1f497d; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Edit in CourseController</p>
<p style="color: #1f497d; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Hotfix: Validation key for placement schedule</p>
<p style="color: #1f497d; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Update Enrolment Open email text</p>
<p style="margin: 0in; font-family: Calibri; font-size: 11.0pt; color: #1f497d;">&nbsp;</p>
<p style="margin: 0in; font-family: Calibri; font-size: 14.0pt;"><span style="text-decoration: underline;">Release Notes: 11 January 2019</span></p>
<p style="margin: 0in; font-family: Calibri; font-size: 11.0pt;">&nbsp;</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Copy 189 Term Results</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Quick edit of existing teachers</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Create teacher accounts and permissions</p>
<p style="color: #000000; margin: 0in; margin-left: .375in; font-family: Calibri; font-size: 11.0pt;">Clean teachers table - update email and index no.</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Create view of classes</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Create attendance tracking view</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Create Release Notes page</p>
<p style="margin: 0in; font-family: Calibri; font-size: 11.0pt; color: #1f497d;">&nbsp;</p>
<p style="margin: 0in; font-family: Calibri; font-size: 14.0pt;"><span style="text-decoration: underline;">Release Notes: 31 December 2018</span></p>
<p style="margin: 0in; font-family: Calibri; font-size: 11.0pt;">&nbsp;</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">sorting of waitlist - debugged orderBy priority and submission date</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">waitlist count is not automatically updated - refresh manually</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">classlist for teachers on 31 December</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">pending payment students / late approval of payments inserted to waitlisted classes</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">admin comments when moving students</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">link stat counter to term in admin dashboard</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Moving of students directly in PASH Table</p>
<p style="color: #000000; margin: 0in; font-family: Calibri; font-size: 11.0pt;">Table showing Classroom count each</p>


</section>
