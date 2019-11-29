<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// test routes for test queries
Route::get('test-query', 'WaitlistController@testQuery')->name('test-query');
Route::get('update-overall-approval', 'WaitlistController@updateOverallApproval')->name('update-overall-approval');
Route::get('send-auth-email', 'WaitlistController@sendAuthEmailIndividual')->name('send-auth-email');
Route::get('query-term', 'WaitlistController@queryTerm')->name('query-term');
Route::get('sddextr', 'WaitlistController@sddextr')->name('sddextr');
Route::get('test-method', 'WaitlistController@testMethod')->name('test-method');
Route::get('insert-record-to-preview-test', 'WaitlistController@insertRecordToPreview')->name('insert-record-to-preview-test');
Route::get('copy-students-to-waitlist', 'WaitlistController@copyStudentsToWaitlist')->name('copy-students-to-waitlist');

Route::get('pdfview', ['as'=>'pdfview','uses'=>'PreviewController@pdfView']);
Route::get('release-notes', function () { return view('release_notes'); })->name('release-notes');

/**
 * Authenticated User Routes
 */
Route::group(['middleware' => ['auth','isAdmin'], 'prefix' => 'admin'],function(){
    /**
     * Admin Routes
     */
    Route::get('send-broadcast-enrolment-is-open', 'SystemController@sendBroadcastEnrolmentIsOpen')->name('send-broadcast-enrolment-is-open');
    Route::get('send-broadcast-reminder', 'SystemController@sendBroadcastReminder')->name('send-broadcast-reminder');
    Route::get('send-reminder-to-current-students', 'SystemController@sendReminderToCurrentStudents')->name('send-reminder-to-current-students');
    Route::get('system-index', 'SystemController@systemIndex')->name('system-index');

    Route::resource('newuser', 'NewUserController',['only' => ['index', 'show', 'update']]);
    Route::get('edit-new-user', ['as'=>'edit-new-user','uses'=>'NewUserController@editNewUser']);
    Route::get('newuser-index-all', ['as'=>'newuser-index-all','uses'=>'NewUserController@newUserIndexAll']);

    Route::get('/', 'AdminController@adminIndex')->name('admin_dashboard');
    Route::get('user/import', 'AdminController@importUser')->name('import-user');
    Route::post('user/import', 'AdminController@handleImportUser')->name('bulk-import-user');
    Route::get('user/import-exist', 'AdminController@importExistingUser')->name('import-existing-user');
    Route::post('user/import-exist', 'AdminController@handleImportExistingUser')->name('bulk-import-existing-user');
    Route::get('user/set-session-term', 'AdminController@setSessionTerm')->name('set-session-term');

    Route::get('fully-approved-forms-not-in-class', 'AdminController@adminFullyApprovedFormsNotInClass')->name('fully-approved-forms-not-in-class');

    Route::get('move-to-pash', 'AdminController@moveToPash')->name('move-to-pash');
    Route::get('admin-view-classrooms', ['as'=>'admin-view-classrooms','uses'=>'AdminController@adminViewClassrooms']);

    Route::get('admin-student-email-view', 'AdminController@adminStudentEmailView')->name('admin-student-email-view');

    /**
     * Text Routes
     */
    Route::get('edit-enrolment-is-open-text/{id}',['as' => 'edit-enrolment-is-open-text', 'uses' => 'TextController@editEnrolmentIsOpenText'] );
    Route::get('view-enrolment-is-open-text/{id}',['as' => 'view-enrolment-is-open-text', 'uses' => 'TextController@viewEnrolmentIsOpenText'] );
    Route::put('store-enrolment-is-open-text/{id}',['as' => 'store-enrolment-is-open-text', 'uses' =>  'TextController@storeEnrolmentIsOpenText'] );
    Route::resource('text', 'TextController');
    Route::get('view-convocation-email-text',['as' => 'view-convocation-email-text', 'uses' => 'TextController@viewConvocationEmailText']);

    /**
     * Teachers Routes
     */
    Route::resource('teachers', 'TeachersController');
    Route::get('teacher-show-classrooms-per-teacher', ['as'=>'teacher-show-classrooms-per-teacher','uses'=>'TeachersController@teacherShowClassroomsPerTeacher']);

    Route::get('teacher-dashboard', 'TeachersController@teacherDashboard')->name('teacher-dashboard')->middleware('first-time-login');
    
    Route::put('ajax-teacher-update', ['as'=>'ajax-teacher-update','uses'=>'TeachersController@ajaxTeacherUpdate']);
    
    Route::get('teacher-view-classrooms', ['as'=>'teacher-view-classrooms','uses'=>'TeachersController@teacherViewClassrooms']);

    Route::get('teacher-view-all-classrooms', ['as'=>'teacher-view-all-classrooms','uses'=>'TeachersController@teacherViewAllClassrooms']);

    Route::post('teacher-show-students', ['as'=>'teacher-show-students','uses'=>'TeachersController@teacherShowStudents']);

    Route::post('teacher-enter-results', ['as'=>'teacher-enter-results','uses'=>'TeachersController@teacherEnterResults']);

    Route::put('ajax-save-results', ['as'=>'ajax-save-results','uses'=>'TeachersController@ajaxSaveResults']);

    Route::get('teacher-select-week/{code}', ['as'=>'teacher-select-week','uses'=>'TeachersController@teacherSelectWeek']);

    Route::get('teacher-week-table', ['as'=>'teacher-week-table','uses'=>'TeachersController@teacherWeekTable']);

    Route::get('teacher-manage-attendances', ['as'=>'teacher-manage-attendances','uses'=>'TeachersController@teacherManageAttendances']);

    Route::put('ajax-teacher-attendance-update', ['as'=>'ajax-teacher-attendance-update','uses'=>'TeachersController@ajaxTeacherAttendanceUpdate']);

    Route::get('ajax-get-remark', ['as'=>'ajax-get-remark','uses'=>'TeachersController@ajaxGetRemark']);

    Route::get('ajax-show-if-enrolled-next-term', ['as'=>'ajax-show-if-enrolled-next-term','uses'=>'TeachersController@ajaxShowIfEnrolledNextTerm']);

    Route::get('ajax-show-overall-attendance', ['as'=>'ajax-show-overall-attendance','uses'=>'TeachersController@ajaxShowOverallAttendance']);

    Route::get('teacher-assign-course-view', ['as'=>'teacher-assign-course-view','uses'=>'TeachersController@teacherAssignCourseView']);
    Route::get('teacher-check-schedule-count', ['as'=>'teacher-check-schedule-count','uses'=>'TeachersController@teacherCheckScheduleCount']);
    Route::put('teacher-save-assigned-course', ['as'=>'teacher-save-assigned-course','uses'=>'TeachersController@teacherSaveAssignedCourse']);
    Route::put('teacher-nothing-to-modify', ['as'=>'teacher-nothing-to-modify','uses'=>'TeachersController@teacherNothingToModify']);
    Route::get('teacher-enrolment-preview', ['as'=>'teacher-enrolment-preview','uses'=>'TeachersController@teacherEnrolmentPreview']);
    Route::delete('teacher-delete-form', ['as'=>'teacher-delete-form','uses'=>'TeachersController@teacherDeleteForm']);

    Route::resource('writing-tips', 'WritingTipController');
    Route::get('send-writing-tip-email/{writingTip}',  ['as'=>'send-writing-tip-email','uses'=>'WritingTipController@sendWritingTipEmail']);
    Route::post('selective-send-writing-tip-email/{writingTip}',  ['as'=>'selective-send-writing-tip-email','uses'=>'WritingTipController@selectiveSendWritingTipEmail']);

    /*
     * Preview Routes
     */
    Route::get('preview-class-status', 'PreviewController@previewClassStatus')->name('preview-class-status');
    Route::get('preview-course-3', 'PreviewController@previewCourse3')->name('preview-course-3');

    Route::get('preview-vsa-page-1', ['as'=>'preview-vsa-page-1','uses'=>'PreviewController@vsaPage1']);
    Route::get('preview-vsa-page-2', ['as'=>'preview-vsa-page-2','uses'=>'PreviewController@vsaPage2']);
    Route::get('preview-classrooms/{code}', ['as'=>'preview-classrooms','uses'=>'PreviewController@previewClassrooms']);

    Route::any('preview-validate-page', ['as'=>'preview-validate-page','uses'=>'PreviewController@getApprovedEnrolmentForms']);

    Route::post('preview-sort-page', ['as'=>'preview-sort-page','uses'=>'PreviewController@orderCodes']);
    
    Route::post('ajax-preview', ['as'=>'ajax-preview','uses'=>'PreviewController@ajaxPreview']);
    Route::post('ajax-preview-modal', ['as'=>'ajax-preview-modal','uses'=>'PreviewController@ajaxPreviewModal']);
    Route::get('ajax-get-priority', ['as'=>'ajax-get-priority','uses'=>'PreviewController@ajaxGetPriority']);
    Route::get('ajax-move-students-form', ['as'=>'ajax-move-students-form','uses'=>'PreviewController@ajaxMoveStudentsForm']);
    Route::post('ajax-move-students', ['as'=>'ajax-move-students','uses'=>'PreviewController@ajaxMoveStudents']);
    Route::post('ajax-select-classroom', ['as'=>'ajax-select-classroom','uses'=>'PreviewController@ajaxSelectClassroom']);
    Route::post('send-individual-convocation', ['as'=>'send-individual-convocation','uses'=>'PreviewController@sendIndividualConvocation']);
    Route::get('send-convocation', 'PreviewController@sendConvocation')->name('send-convocation');
    Route::get('preview-waitlisted', 'PreviewController@previewWaitlisted')->name('preview-waitlisted');
    Route::get('cancelled-convocation-view', 'PreviewController@cancelledConvocaitonView')->name('cancelled-convocation-view');
    Route::put('undelete-pash/{id}', ['as'=>'undelete-pash','uses'=>'PreviewController@unDeletePash']);
    
    Route::get('preview-merged-forms', 'PreviewController@previewMergedForms')->name('preview-merged-forms');
    Route::post('ajax-preview-course-boxes', ['as'=>'ajax-preview-course-boxes','uses'=>'PreviewController@ajaxPreviewCourseBoxes']);
    Route::get('ajax-preview-get-student-count', ['as'=>'ajax-preview-get-student-count','uses'=>'PreviewController@ajaxPreviewGetStudentCount']);
    Route::get('ajax-preview-get-student-priority-status', ['as'=>'ajax-preview-get-student-priority-status','uses'=>'PreviewController@ajaxPreviewGetStudentPriorityStatus']);
    Route::get('ajax-preview-get-student-current-class', ['as'=>'ajax-preview-get-student-current-class','uses'=>'PreviewController@ajaxPreviewGetStudentCurrentClass']);
    
    Route::get('ajax-preview-get-remarks', ['as'=>'ajax-preview-get-remarks','uses'=>'PreviewController@ajaxPreviewGetRemarks']);
    Route::put('ajax-preview-post-remarks', ['as'=>'ajax-preview-post-remarks','uses'=>'PreviewController@ajaxPreviewPostRemarks']);

    Route::get('ajax-select-teacher', ['as'=>'ajax-select-teacher','uses'=>'PreviewController@ajaxSelectTeacher']);
    Route::put('ajax-update-teacher', ['as'=>'ajax-update-teacher','uses'=>'PreviewController@ajaxUpdateTeacher']);

    Route::post('ajax-class-boxes', ['as'=>'ajax-class-boxes','uses'=>'PreviewController@ajaxClassBoxes']);
    Route::get('ajax-get-student-count-per-class', ['as'=>'ajax-get-student-count-per-class','uses'=>'PreviewController@ajaxGetStudentCountPerClass']);
    Route::get('view-classrooms-per-section/{code}', ['as'=>'view-classrooms-per-section','uses'=>'PreviewController@viewClassroomsPerSection']);

    Route::post('insert-record-to-preview', ['as' => 'insert-record-to-preview', 'uses' => 'PreviewController@insertRecordToPreview']);

    /**
     * User Routes
     */
    Route::resource('users', 'UserController');
    // separate password reset form
    Route::get('/user/{id}/passwordreset', ['as' => 'users.passwordreset', 'uses' => 'UserController@passwordReset' ]);
    Route::put('/user/{id}/resetpassword', ['as' => 'users.resetpassword', 'uses' => 'UserController@resetPassword' ]);
    // management of enrolment data per user
    Route::get('user/{id}/manage-user-enrolment-data', ['as' => 'manage-user-enrolment-data', 'uses' => 'UserController@manageUserEnrolmentData' ]);
    Route::get('user/{id}/enrol-student-to-course-form', ['as' => 'enrol-student-to-course-form', 'uses' => 'UserController@enrolStudentToCourseForm' ]);
    Route::post('user/enrol-student-to-course-insert', ['as' => 'enrol-student-to-course-insert', 'uses' => 'UserController@enrolStudentToCourseInsert' ]);
    Route::get('user/{id}/enrol-student-to-placement-form', ['as' => 'enrol-student-to-placement-form', 'uses' => 'UserController@enrolStudentToPlacementForm' ]);
    Route::post('user/enrol-student-to-placement-insert', ['as' => 'enrol-student-to-placement-insert', 'uses' => 'UserController@enrolStudentToPlacementInsert' ]);

    Route::get( 'user/switch/start/{id}', 'UserController@user_switch_start' );
    Route::get( 'user/switch/stop', 'UserController@user_switch_stop' );

    // update index numbers
    Route::get('update-index-view', ['as' => 'update-index-view', 'uses' => 'UserController@updateIndexView' ]);
    Route::get('update-PASH', ['as' => 'update-PASH', 'uses' => 'UserController@updatePASH' ]);
    Route::post('update-PASH-IndexID', ['as' => 'update-PASH-IndexID', 'uses' => 'UserController@updatePASHIndexID' ]);
    Route::post('update-PASH-trashed', ['as' => 'update-PASH-trashed', 'uses' => 'UserController@updatePASHTrashed' ]);
    Route::post('update-enrolment-index', ['as' => 'update-enrolment-index', 'uses' => 'UserController@updateEnrolmentIndex' ]);
    Route::post('update-placement-index', ['as' => 'update-placement-index', 'uses' => 'UserController@updatePlacementIndex' ]);
    Route::post('update-modifiedforms-index', ['as' => 'update-modifiedforms-index', 'uses' => 'UserController@updateModifiedFormsIndex' ]);

    /**
     * Billing Routes
     */
    Route::get('billing-index', ['as' => 'billing-index', 'uses' => 'BillingController@billingIndex' ]);
    Route::get('ajax-billing-table', ['as' => 'ajax-billing-table', 'uses' => 'BillingController@ajaxBillingTable' ]);
    Route::get('billing-json', function () { return view('billing.billing_table'); })->name('billing-json');

    Route::get('billing-admin-selfpaying-student-view', 'BillingController@billingAdminSelfpayingStudentView')->name('billing-admin-selfpaying-student-view');
    Route::get('ajax-selfpaying-student-table', ['as' => 'ajax-selfpaying-student-table', 'uses' => 'BillingController@ajaxSelfpayingStudentTable' ]);
    Route::get('billing-admin-selfpaying-view', 'BillingController@billingAdminSelfpayingView')->name('billing-admin-selfpaying-view');
    Route::get('ajax-selfpaying-by-year-table', ['as' => 'ajax-selfpaying-by-year-table', 'uses' => 'BillingController@ajaxSelfpayingByYearTable' ]);


    // Excel download table from view
    Route::get('excel', 'ExcelController@getBladeExcel');

    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');

    // Classroom routes
    Route::resource('classrooms', 'ClassroomController');
    Route::get('get-schedule-days', ['as' => 'get-schedule-days', 'uses' => 'ClassroomController@getScheduleDays' ]);

    Route::resource('course-schedule', 'CourseSchedController');
    Route::resource('schedules', 'ScheduleController');

    Route::post('store-non-standard-schedule', ['as' => 'store-non-standard-schedule', 'uses' => 'ScheduleController@storeNonStandardSchedule' ]);
    Route::resource('terms', 'TermController');
    Route::resource('courses', 'CourseController');
    Route::get('check-existing-tecode', ['as' => 'check-existing-tecode', 'uses' => 'CourseController@checkExistingTeCode' ]);

    Route::resource('organizations', 'OrgController');
    Route::resource('placement-schedule', 'PlacementScheduleController');

    // Enrolment forms controller
    Route::resource('preenrolment', 'PreenrolmentController');
    Route::get('/preenrolment/edit-fields/indexno/{indexno}/term/{term}/{tecode}/{form}', ['as' => 'edit-enrolment-fields', 'uses' => 'PreenrolmentController@editEnrolmentFields'])->where('tecode', '(.*)');
    Route::put('/preenrolment/update-fields/indexno/{indexno}/term/{term}/{tecode}/{form}', ['as'=>'update-enrolment-fields','uses'=>'PreenrolmentController@updateEnrolmentFields']);
    Route::get('/preenrolment/nothing-to-modify/indexno/{indexno}/term/{term}/{tecode}/{form}', ['as' => 'nothing-to-modify', 'uses' => 'PreenrolmentController@nothingToModify'])->where('tecode', '(.*)');

    Route::get('send-reminder-emails', 'PreenrolmentController@sendReminderEmails')->name('send-reminder-emails');
    // Enrolment form cancellation route for administrators
    Route::delete('/delete/user/{staff}/course/{tecode}/term/{term}/{form}', ['as' => 'enrolment.destroy', 'uses' => 'PreenrolmentController@destroy'])->where('tecode', '(.*)');
    // Enrolment form cancellation route for administrators
    Route::delete('delete-no-email', ['as' => 'delete-no-email', 'uses' => 'PreenrolmentController@deleteNoEmail']);    
    // Route to AJAX get student comments
    Route::post('ajax-std-comments', ['as'=>'ajax-std-comments','uses'=>'PreenrolmentController@ajaxStdComments']);
    
    Route::get('query-regular-forms-to-assign', ['as'=>'query-regular-forms-to-assign','uses'=>'PreenrolmentController@queryRegularFormsToAssign']);
    Route::get('query-orphan-forms-to-assign', ['as'=>'query-orphan-forms-to-assign','uses'=>'PreenrolmentController@queryOrphanFormsToAssign']);
    Route::get('admin-assign-course-view', ['as'=>'admin-assign-course-view','uses'=>'PreenrolmentController@adminAssignCourseView']);
    Route::get('admin-check-schedule-count', ['as'=>'admin-check-schedule-count','uses'=>'PreenrolmentController@adminCheckScheduleCount']);
    Route::put('admin-save-assigned-course', ['as'=>'admin-save-assigned-course','uses'=>'PreenrolmentController@adminSaveAssignedCourse']);
    Route::put('admin-nothing-to-modify', ['as'=>'admin-nothing-to-modify','uses'=>'PreenrolmentController@adminNothingToModify']);
    Route::get('admin-manage-user-assign-course-view', ['as'=>'admin-manage-user-assign-course-view','uses'=>'PreenrolmentController@adminManageUserAssignCourseView']);

    /**
     * Placement forms controller
     */
    Route::resource('placement-form', 'PlacementFormController');
    Route::get('send-reminder-emails-placement', 'PlacementFormController@sendReminderEmailsPlacement')->name('send-reminder-emails-placement');
    Route::get('/placement-form-filtered', ['as'=>'placement-form-filtered','uses'=>'PlacementFormController@getFilteredPlacementForms']);
    Route::get('/placement-form-approved-view', ['as'=>'placement-form-approved-view','uses'=>'PlacementFormController@getApprovedPlacementFormsView']);

    // route of edit placement form fields
    Route::get('/placement/edit-fields/{id}/id', ['as' => 'edit-placement-fields', 'uses' => 'PlacementFormController@editPlacementFields']);
    Route::put('/placement/update-fields/{id}/id', ['as'=>'update-placement-fields','uses'=>'PlacementFormController@updatePlacementFields']);

    // route of assign course form to placement view 
    Route::get('/placement-form-assign/{id}', ['as'=>'placement-form-assign','uses'=>'PlacementFormController@editAssignCourse']);
    
    Route::put('/placement-form-assign-course/{id}', ['as'=>'placement-form-assign-course','uses'=>'PlacementFormController@assignCourseToPlacement']);

    // Placement form cancellation route for administrators
    Route::delete('/delete/user/{staff}/lang/{lang}/term/{term}/{eform}', ['as' => 'placement.destroy', 'uses' => 'PlacementFormController@destroy'])->where('tecode', '(.*)');
    // Route to AJAX get student comments
    Route::post('ajax-placement-comments', ['as'=>'ajax-placement-comments','uses'=>'PlacementFormController@ajaxPlacementComments']);

    // Routes for managing placement exams
    Route::get('manage-exam-view', ['as'=>'manage-exam-view','uses'=>'PlacementFormController@manageExamView']); 
    Route::get('manage-exam-table', ['as'=>'manage-exam-table','uses'=>'PlacementFormController@manageExamtable']); 
    
    // Routes Placement Exam Results
    Route::post('exam-result-save', ['as' => 'exam-result-save', 'uses' => 'ExamResultController@examResultSave' ]);

    Route::resource('rooms', 'RoomsController');
    Route::resource('waitlist', 'WaitlistController');
    
    Route::resource('selfpayform', 'SelfPayController', ['only' => ['index', 'update']]);
    Route::get('selfpayform/{indexid}/{tecode}/{term}', ['as'=>'selfpayform.edit','uses'=>'SelfPayController@edit']);
    Route::get('selfpayform/index-placement-selfpay', ['as'=>'index-placement-selfpay','uses'=>'SelfPayController@indexPlacementSelfPay']);

    Route::get('selfpayform/approved-placement-selfpay', ['as'=>'approved-placement-selfpay','uses'=>'SelfPayController@approvedPlacementSelfPay']);
    Route::get('selfpayform/pending-placement-selfpay', ['as'=>'pending-placement-selfpay','uses'=>'SelfPayController@pendingPlacementSelfPay']);
    Route::get('selfpayform/cancelled-placement-selfpay', ['as'=>'cancelled-placement-selfpay','uses'=>'SelfPayController@cancelledPlacementSelfPay']);


    Route::get('selfpayform/edit-placement-selfpay/{indexid}/{language}/{term}', ['as'=>'edit-placement-selfpay','uses'=>'SelfPayController@editPlacementSelfPay']);
    Route::put('selfpayform/post-placement-selfpay/{indexid}', ['as'=>'post-placement-selfpay','uses'=>'SelfPayController@postPlacementSelfPay']);

    Route::get('selfpayform/show-schedule-selfpay', ['as'=>'show-schedule-selfpay','uses'=>'AjaxController@showScheduleSelfPay']);
    Route::post('selfpayform/post-decision-selfpay', ['as'=>'post-decision-selfpay','uses'=>'AjaxController@postDecisionSelfPay']);

    // route to admin attach documents to SelfPayment forms
    Route::get('admin-add-attachments/{indexid}/{lang}/{tecode}/{term}/{eform}', ['as'=>'admin-add-attachments','uses'=>'SelfPayController@adminAddAttachmentsView']);
    Route::get('admin-add-attachments-placement/{indexid}/{lang}/{term}/{eform}', ['as'=>'admin-add-attachments-placement','uses'=>'SelfPayController@adminAddAttachmentsPlacementView']);
    Route::put('admin-add-attachments-store', ['as'=>'admin-add-attachments-store','uses'=>'SelfPayController@adminAddAttachmentsStore']);
    Route::put('admin-add-attachments-placement-store', ['as'=>'admin-add-attachments-placement-store','uses'=>'SelfPayController@adminAddAttachmentsPlacementStore']);

    Route::get('/placement-form-approved', ['as'=>'placement-form-approved','uses'=>'ValidateFormsController@getApprovedPlacementForms']);

    Route::get('vsa-page-1', ['as'=>'vsa-page-1','uses'=>'ValidateFormsController@vsaPage1']);
    Route::get('vsa-page-2', ['as'=>'vsa-page-2','uses'=>'TempSortController@vsaPage2']);

    // temporary page for validating queries /admin/validate-page
    Route::any('validate-page', ['as'=>'validate-page','uses'=>'ValidateFormsController@getApprovedEnrolmentForms']);

    // temporary page for sorting queries /admin/sort-page
    Route::post('sort-page', ['as'=>'sort-page','uses'=>'TempSortController@orderCodes']);

});
Route::group(['middleware' => ['auth','isAdmin'], 'prefix' => 'admin-stats'],function(){
    //admin routes
    Route::get('stats', function () { return view('admin.adminStats'); })->name('stats');
});

//middleware to prevent back button and access cache
Route::group(['middleware' => 'prevent-back-history'],function(){
    Auth::routes();
    Route::match(['get','post'], 'register', function(){
    return redirect('/');
});
    Route::get('/','WelcomeController@index');
});

Route::middleware(['auth'])->group(function () {
    Route::middleware(['first-time-login'])->group(function () { //middleware to force user to change password 
        //home page routes
        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/home-how-to-check-status', 'HomeController@homeHowToCheckStatus')->name('home-how-to-check-status');
        Route::get('/whatorg', ['as'=>'whatorg','uses'=>'HomeController@whatorg']);
        // Route::get('/whatorg', ['as'=>'whatorg','uses'=>'HomeController@whatorg'])->middleware('opencloseenrolment');
        Route::post('/whatform', ['as'=>'whatform','uses'=>'HomeController@whatform'])->middleware('check-prev-url');
        Route::get('/submitted', ['as'=>'submitted','uses'=>'HomeController@previousSubmitted']);
        Route::get('/previous-submitted', ['as'=>'previous-submitted','uses'=>'HomeController@previousSubmitted']);
        Route::get('/history', ['as'=>'history','uses'=>'HomeController@history']);
        Route::post('/showform', ['as'=>'submitted.show','uses'=>'HomeController@showMod']);
        //Route::delete('/delete/user/{staff}/course/{tecode}', ['as' => 'submitted.destroy', 'uses' => 'HomeController@destroy'])->where('tecode', '(.*)');
        
        // cancellation routes with date limit middleware
        Route::delete('/delete/user/{staff}/course/{tecode}/term/{term}/{form}', ['middleware' => 'limit-cancel','as' => 'submitted.destroy', 'uses' => 'HomeController@destroy'])->where('tecode', '(.*)');
        Route::delete('/delete/user/{staff}/lang/{lang}/term/{term}/{eform}', ['middleware' => 'limit-cancel','as' => 'submittedPlacement.destroy', 'uses' => 'HomeController@destroyPlacement'])->where('tecode', '(.*)');
        
        //apply auth middleware only so students could edit their profile
        Route::resource('students', 'StudentController');
    });
    Route::get('password/expired', 'FirstTimeLoginController@expired')
        ->name('password.expired');
    Route::post('password/post_expired', 'FirstTimeLoginController@postExpired')
        ->name('password.post_expired');
    
    // route to cancel of convoked course class 
    Route::delete('cancel-convocation/{codeindexidclass}', ['middleware' => 'limit-cancel','as' => 'cancel-convocation', 'uses' => 'PreviewController@cancelConvocation']);

    // route to re-attach documents for SelfPayment forms
    Route::get('add-attachments/{indexid}/{lang}/{tecode}/{term}/{date}/{eform}', ['as'=>'add-attachments','uses'=>'SelfPayController@addAttachmentsView']);
    Route::get('add-attachments-placement/{indexid}/{lang}/{term}/{date}/{eform}', ['as'=>'add-attachments-placement','uses'=>'SelfPayController@addAttachmentsPlacementView']);
    Route::put('add-attachments-store', ['as'=>'add-attachments-store','uses'=>'SelfPayController@addAttachmentsStore']);
    Route::put('add-attachments-placement-store', ['as'=>'add-attachments-placement-store','uses'=>'SelfPayController@addAttachmentsPlacementStore']);
});

// route to update email of student - this should be outside of auth middleware for the student to have access to this route 
Route::get('/verify/{student}/{temp_email}/{update_token}', ['as' => 'verify.updateProfileConfirmed', 'uses' => 'StudentController@updateProfileConfirmed' ]);
// route for ajax select on vsa-page-2
Route::post('select-ajax-admin', ['as'=>'select-ajax-admin','uses'=>'AjaxController@selectAjaxAdmin']);

//route for ajax jquery on organization
Route::get('org-select-ajax', ['as'=>'org-select-ajax','uses'=>'AjaxController@ajaxOrgSelect']);
// route for ajax jquery on certain dates
Route::get('get-date-ajax', ['as'=>'get-date-ajax','uses'=>'AjaxController@ajaxGetDate']);
// route for ajax jquery to compare organization in whatorg page
Route::post('org-compare-ajax', ['as'=>'org-compare-ajax','uses'=>'AjaxController@ajaxOrgCompare']);
// route for ajax jquery if forms have been cancelled or deleted
Route::get('is-cancelled-ajax', ['as'=>'is-cancelled-ajax','uses'=>'AjaxController@ajaxIsCancelled']);

Route::get('get-term-data-ajax', ['as'=>'get-term-data-ajax','uses'=>'AjaxController@ajaxGetTermData']);

Route::post('ajax-show-modal', ['as' => 'ajax-show-modal', 'uses' => 'AjaxController@ajaxShowModal']);
Route::post('ajax-show-modal-placement', ['as' => 'ajax-show-modal-placement', 'uses' => 'AjaxController@ajaxShowModalPlacement']);

//placement form routes
Route::get('/placementinfo', ['as'=>'placementinfo','uses'=>'PlacementFormController@getPlacementInfo']); // ->middleware('prevent-access-placement');
Route::post('/postplacementinfo-additional', ['as'=>'postplacementinfo-additional','uses'=>'PlacementFormController@postPlacementInfoAdditional']);
Route::post('check-placement-sched-ajax', ['as'=>'check-placement-sched-ajax','uses'=>'AjaxController@ajaxCheckPlacementSched']);
Route::post('/postplacementinfo', ['as'=>'postplacementinfo','uses'=>'PlacementFormController@postPlacementInfo']);
Route::post('/postSelfPayPlacementInfo', ['as'=>'postSelfPayPlacementInfo','uses'=>'PlacementFormController@postSelfPayPlacementInfo']);

//fee-paying form routes
Route::resource('selfpayform', 'SelfPayController', ['only' => ['create', 'store']]);

//if already selected YES to continue course, go to these routes
Route::resource('noform', 'NoFormController', ['only' => ['create', 'store', 'edit']]);

//main UN staff form routes
Route::resource('myform', 'RepoController');
Route::post('check-placement-course-ajax', ['as'=>'check-placement-course-ajax','uses'=>'AjaxController@ajaxCheckPlacementCourse']);

//main controller used for ajax jquery on all forms - myform, noform, selfpayform
Route::get('ajax-check-batch-has-ran', ['as'=>'ajax-check-batch-has-ran','uses'=>'AjaxController@ajaxCheckBatchHasRan']);
Route::post('select-ajax', ['as'=>'select-ajax','uses'=>'AjaxController@selectAjax']);
Route::post('select-ajax2', ['as'=>'select-ajax2','uses'=>'AjaxController@selectAjax2']);
Route::post('select-ajax-level-one', ['as'=>'select-ajax-level-one','uses'=>'AjaxController@selectAjaxLevelOne']);
Route::get('check-placement-form-ajax', ['as'=>'check-placement-form-ajax','uses'=>'AjaxController@ajaxCheckPlacementForm']);
Route::get('check-placement-entries-ajax', ['as'=>'check-placement-entries-ajax','uses'=>'AjaxController@ajaxCheckPlacementEntries']);
Route::get('check-enrolment-entries-ajax', ['as'=>'check-enrolment-entries-ajax','uses'=>'AjaxController@ajaxCheckEnrolmentEntries']);
Route::get('check-selfpay-entries-ajax', ['as'=>'check-selfpay-entries-ajax','uses'=>'AjaxController@ajaxCheckSelfpayEntries']);
Route::get('check-selfpay-placement-entries-ajax', ['as'=>'check-selfpay-placement-entries-ajax','uses'=>'AjaxController@ajaxCheckSelfpayPlacementEntries']);
Route::get('ajax-show-full-select-dropdown', ['as'=>'ajax-show-full-select-dropdown','uses'=>'AjaxController@ajaxShowFullSelectDropdown']);
Route::get('ajax-change-hr-approval', ['as'=>'ajax-change-hr-approval','uses'=>'AjaxController@ajaxChangeHRApproval']);
Route::get('ajax-change-org-in-form', ['as'=>'ajax-change-org-in-form','uses'=>'AjaxController@ajaxChangeOrgInForm']);
Route::get('ajax-convert-to-selfpay', ['as'=>'ajax-convert-to-selfpay','uses'=>'AjaxController@ajaxConvertToSelfpay']);
Route::get('ajax-convert-to-regular', ['as'=>'ajax-convert-to-regular','uses'=>'AjaxController@ajaxConvertToRegular']);
Route::get('ajax-show-language-dropdown', ['as'=>'ajax-show-language-dropdown','uses'=>'AjaxController@ajaxShowLanguageDropdown']);


// ajax use to get section number of cs_unique
Route::get('get-section-no-ajax', ['as'=>'get-section-no-ajax','uses'=>'AjaxController@ajaxGetSectionNo']);
Route::get('show-section-ajax', ['as'=>'show-section-ajax','uses'=>'AjaxController@ajaxShowSection']);
Route::post('delete-day-param-ajax', ['as'=>'delete-day-param-ajax','uses'=>'AjaxController@ajaxDeleteDayParam']);
Route::get('get-section-param-ajax', ['as'=>'get-section-param-ajax','uses'=>'AjaxController@ajaxGetSectionParam']);

Route::group(['middleware' => 'open-close-approval-routes'],function(){
    //url routing for manager approval page
    Route::get('/approval/{staff}/{tecode}/{id}/{form}/{term}', ['as' => 'approval.getform', 'uses' => 'ApprovalController@getForm' ]);

    //url routing for manager placement test approval page
    Route::get('/approval/{staff}/{lang}/placement/{id}/{form}/{term}', ['as' => 'approval.getplacementformdata', 'uses' => 'ApprovalController@getPlacementFormData' ]);    
});

Route::group(['middleware' => 'open-close-approval-routes-hr'],function(){
    //url routing for hr partner approval page
    Route::get('/approvalhr/{staff}/{tecode}/{id}/{form}/{term}', ['as' => 'approval.getform2hr','uses' => 'ApprovalController@getForm2hr' ]);

    //url routing for hr partner placement test approval page
    Route::get('/approvalhr/{staff}/{lang}/placement/{id}/{form}/{term}', ['as' => 'approval.getplacementformdata2hr','uses' => 'ApprovalController@getPlacementFormData2hr' ]);  
});

    Route::put('/approval/user/{staff}/course/{tecode}/{formcount}/{term}', ['as' => 'approval.updateform', 'uses' => 'ApprovalController@updateForm' ])->where('tecode', '(.*)'); // where clause accepts routes with slashes
    Route::put('/approvalhr/user/{staff}/course/{tecode}/{formcount}/{term}', ['as' => 'approval.updateform2hr','uses' => 'ApprovalController@updateForm2hr' ])->where('tecode', '(.*)'); // where clause accepts routes with slashes
    Route::put('/approval/user/{staff}/lang/{lang}/{formcount}/{term}', ['as' => 'approval.updateplacementformdata', 'uses' => 'ApprovalController@updatePlacementFormData' ]);
    Route::put('/approvalhr/user/{staff}/lang/{lang}/{formcount}/{term}', ['as' => 'approval.updateplacementformdata2hr','uses' => 'ApprovalController@updatePlacementFormData2hr' ]);
    
//public pages
Route::get('eform', function () { return view('confirmation_page_unog'); })->name('eform');
Route::get('eform2', function () { return view('confirmation_page_hr'); })->name('eform2');
Route::get('confirmationLinkUsed', function () { return view('confirmationLinkUsed'); })->name('confirmationLinkUsed');
Route::get('confirmationLinkExpired', function () { return view('confirmationLinkExpired'); })->name('confirmationLinkExpired');
Route::get('updateLinkExpired', function () { return view('updateLinkExpired'); })->name('updateLinkExpired');
Route::get('new_user_msg', function () { return view('new_user_msg'); })->name('new_user_msg');
Route::get('page_not_available', function () { return view('page_not_available'); })->name('page_not_available');
Route::get('thankyou', function () { return view('thankyou'); })->name('thankyou');
Route::get('thankyouPlacement', function () { return view('thankyouPlacement'); })->name('thankyouPlacement');
Route::get('thankyouSelfPay', function () { return view('thankyouSelfPay'); })->name('thankyouSelfPay');

// route for verification of UN staff
Route::resource('newuser', 'NewUserController',['only' => ['create', 'store']]);

// route for web form request to create new account of UN staff
Route::get('get-new-new-user', ['as' => 'get-new-new-user','uses' => 'NewUserController@getNewNewUser' ]);
Route::post('post-new-new-user', ['as' => 'post-new-new-user','uses' => 'NewUserController@postNewNewUser' ]);

// route for verification of non-UN staff
Route::get('get-new-outside-user', ['as' => 'get-new-outside-user','uses' => 'NewUserController@getNewOutsideUser' ]);
Route::post('post-new-outside-user', ['as' => 'post-new-outside-user','uses' => 'NewUserController@postNewOutsideUser' ]);

// route for web form request to create new account of non-UN staff
Route::get('get-new-outside-user-form', ['as' => 'get-new-outside-user-form','uses' => 'NewUserController@getNewOutsideUserForm' ]);
Route::post('post-new-outside-user-form', ['as' => 'post-new-outside-user-form','uses' => 'NewUserController@postNewOutsideUserForm' ]);

//Route::get('/', function () { return view('welcome'); });

Auth::routes();
Route::match(['get','post'], 'register', function(){
    return redirect('/');
});

    // Authentication Routes...
   // Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
   // Route::post('login', 'Auth\LoginController@login');
   // Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    // Registration Routes...
   // Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
   // Route::post('register', 'Auth\RegisterController@register');
    // Password Reset Routes...
   // Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
   // Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
   // Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
   // Route::post('password/reset', 'Auth\ResetPasswordController@reset');


//if ( Auth::check())
//{
    //Route::get('/','DashboardController@index');
//}
//else
//{
    //Route::get('/','WelcomeController@index');
//}

// show list routes in webpage
Route::get('simpleroutes', function() {
    $routeCollection = Route::getRoutes();
    echo "<table style='width:100%'>";
        echo "<tr>";
            echo "<td width='10%'><h4>uri</h4></td>";
            echo "<td width='10%'><h4>Name</h4></td>";
            echo "<td width='10%'><h4>Type</h4></td>";
            echo "<td width='10%'><h4>Method</h4></td>";
            echo "<td width='10%'><h4>Method Name</h4></td>";
        echo "</tr>";
        foreach ($routeCollection as $value) {
            echo "<tr>";
                echo "<td>" . $value->uri . "</td>";
                echo "<td>" . $value->getName() . "</td>";
                echo "<td>" . $value->getPrefix() . "</td>";
                echo "<td>" . $value->getActionMethod() . "</td>";
                echo "<td>" . $value->getActionName() . "</td>";
            echo "</tr>";
        }
    echo "</table>";
})->middleware(['auth','isAdmin']);
//e-mail template preview on browser
//use Illuminate\Mail\Markdown;

//Route::get('mail-preview', function () {
//    $markdown = new Markdown(view(), config('mail.markdown'));

//    return $markdown->render('emails.approval');
//});