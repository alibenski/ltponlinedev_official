<?php

use Illuminate\Support\Facades\Route;

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
Route::get('testQuery', 'TestController@testQuery')->name('testQuery');
Route::get('captcha', 'TestController@captcha')->name('captcha');
Route::post('post-captcha', 'TestController@postCaptcha')->name('post-captcha');

Route::get('send-email-approval-hr', 'WaitlistController@sendEmailApprovalHR')->name('send-email-approval-hr');
Route::get('test-query', 'WaitlistController@testQuery')->name('test-query');
Route::get('update-overall-approval', 'WaitlistController@updateOverallApproval')->name('update-overall-approval');
Route::get('send-auth-email', 'WaitlistController@sendAuthEmailIndividual')->name('send-auth-email');
Route::get('query-term', 'WaitlistController@queryTerm')->name('query-term');
Route::get('sddextr', 'WaitlistController@sddextr')->name('sddextr');
Route::get('test-method', 'WaitlistController@testMethod')->name('test-method');
Route::get('insert-record-to-preview-test', 'WaitlistController@insertRecordToPreview')->name('insert-record-to-preview-test');
Route::get('copy-students-to-waitlist', 'WaitlistController@copyStudentsToWaitlist')->name('copy-students-to-waitlist');

Route::get('pdfview', ['as' => 'pdfview', 'uses' => 'PreviewController@pdfView']);
Route::get('release-notes', function () {
    return view('release_notes');
})->name('release-notes');

/**
 * Late Account User Register Routes
 */
Route::get('late-new-user-form', 'Auth\LateRegisterController@lateNewUserForm')->name('late-new-user-form');
Route::post('late-register', 'Auth\LateRegisterController@lateRegister')->name('late-register');

/**
 * Authenticated User Routes
 */
Route::group(['middleware' => ['auth', 'isAdmin', 'first-time-login'], 'prefix' => 'admin'], function () {
    /**
     * Admin Routes
     */
    Route::get('change-index-number-form', 'ChangeIndexNumberController@changeIndexNumberForm');

    Route::get('no-show-list', 'AdminController@noShowList')->name('no-show-list');

    Route::get('admin-export-ocha', 'AdminController@adminExportOcha')->name('admin-export-ocha');
    Route::get('admin-extract-data', 'AdminController@adminExtractData')->name('admin-extract-data');
    Route::get('admin-extract-data-2018', 'AdminController@adminExtractData2018')->name('admin-extract-data-2018');

    Route::get('admin-export-moodle', 'AdminController@adminExportMoodle')->name('admin-export-moodle');
    Route::get('admin-query-export-moodle', 'AdminController@adminQueryExportMoodle')->name('admin-query-export-moodle');
    Route::get('admin-placement-export-moodle', 'AdminController@adminPlacementExportMoodle')->name('admin-placement-export-moodle');

    Route::get('late-user-management', 'Auth\LateRegisterController@lateUserManagement')->name('late-user-management');
    Route::post('generate-URL', ['as' => 'generate-URL', 'uses' => 'Auth\LateRegisterController@generateRandomURL']);
    Route::post('generate-URL-late-enrolment', ['as' => 'generate-URL-late-enrolment', 'uses' => 'LateEnrolmentController@generateRandomURL']);

    Route::get('send-convo-to-language-smb', 'SystemController@sendConvoToLanguageSmb')->name('send-convo-to-language-smb');
    Route::get('send-broadcast-enrolment-is-open', 'SystemController@sendBroadcastEnrolmentIsOpen')->name('send-broadcast-enrolment-is-open');
    Route::get('send-general-email', 'SystemController@sendGeneralEmail')->name('send-general-email');
    Route::get('send-email-to-enrolled-students-of-selected-term', 'SystemController@sendEmailToEnrolledStudentsOfSelectedTerm')->name('send-email-to-enrolled-students-of-selected-term');
    Route::get('send-general-email-to-convoked-students-of-selected-term', 'SystemController@sendGeneralEmailToConvokedStudentsOfSelectedTerm')->name('send-general-email-to-convoked-students-of-selected-term');
    Route::get('send-broadcast-reminder', 'SystemController@sendBroadcastReminder')->name('send-broadcast-reminder');
    Route::get('send-reminder-to-current-students', 'SystemController@sendReminderToCurrentStudents')->name('send-reminder-to-current-students');
    Route::get('system-index', 'SystemController@systemIndex')->name('system-index');
    Route::get('send-to-focal-points', 'SystemController@sendToFocalPoints')->name('send-to-focal-points');
    Route::get('send-to-mission-offices', 'SystemController@sendToMissionOffices')->name('send-to-mission-offices');
    Route::post('send-to-manual-email-adds', 'SystemController@sendToManualEmailAdds')->name('send-to-manual-email-adds');

    Route::resource('newuser', 'NewUserController', ['only' => ['index', 'show', 'update']]);
    Route::get('edit-new-user', ['as' => 'edit-new-user', 'uses' => 'NewUserController@editNewUser']);
    Route::get('newuser-index-all', ['as' => 'newuser-index-all', 'uses' => 'NewUserController@newUserIndexAll']);

    Route::get('/', 'AdminController@adminIndex')->name('admin_dashboard');
    Route::get('user/import', 'AdminController@importUser')->name('import-user');
    Route::post('user/import', 'AdminController@handleImportUser')->name('bulk-import-user');
    Route::get('user/import-exist', 'AdminController@importExistingUser')->name('import-existing-user');
    Route::post('user/import-exist', 'AdminController@handleImportExistingUser')->name('bulk-import-existing-user');
    Route::get('user/set-session-term', 'AdminController@setSessionTerm')->name('set-session-term');

    Route::get('fully-approved-forms-not-in-class', 'AdminController@adminFullyApprovedFormsNotInClass')->name('fully-approved-forms-not-in-class');

    Route::get('move-to-pash', 'AdminController@moveToPash')->name('move-to-pash');
    Route::get('admin-view-classrooms', ['as' => 'admin-view-classrooms', 'uses' => 'AdminController@adminViewClassrooms']);

    Route::get('admin-student-email-view', 'AdminController@adminStudentEmailView')->name('admin-student-email-view');
    Route::get('get-admin-all-current-student-in-term', 'AdminController@getAdminAllCurrentStudentInTerm')->name('get-admin-all-current-student-in-term');
    Route::get('admin-students-with-waitlist-view', 'AdminController@adminStudentsWithWaitlistView')->name('admin-students-with-waitlist-view');
    Route::get('get-admin-all-current-student-with-waitlist-in-term', 'AdminController@getAdminAllCurrentStudentWithWaitlistInTerm')->name('get-admin-all-current-student-with-waitlist-in-term');

    Route::get('admin-excel-schedule', ['as' => 'admin-excel-schedule', 'uses' => 'AdminController@adminExcelSchedule']);
    /*
     * Reporting Routes
     */
    Route::get('reports/custom-billing-view', 'ReportsController@reportsCustomBillingView')->name('reports/custom-billing-view');
    Route::get('reports-custom-billing', 'ReportsController@reportsCustomBilling')->name('reports-custom-billing');
    Route::get('reports/all-students-per-year-or-term-view', 'ReportsController@reportAllStudentsPerYearOrTermView')->name('reports/all-students-per-year-or-term-view');
    Route::get('reports-all-students-per-year-or-term', 'ReportsController@reportAllStudentsPerYearOrTerm')->name('reports-all-students-per-year-or-term');
    Route::get('reports/ltp-stats-view-students-per-term', 'ReportsController@viewStudentsPerTerm')->name('reports/ltp-stats-view-students-per-term');
    Route::get('reports/stats-students-per-term', 'ReportsController@statsStudentsPerTerm')->name('reports/stats-students-per-term');
    Route::get('reports', 'ReportsController@baseView')->name('reports');
    Route::get('get-reports-table', ['as' => 'get-reports-table', 'uses' => 'ReportsController@getReportsTable']);
    Route::get('reports/ltp-stats-graph-view', 'ReportsController@ltpStatsGraphView')->name('reports/ltp-stats-graph-view');
    Route::get('get-ltp-stats-graph-view', ['as' => 'get-ltp-stats-graph-view', 'uses' => 'ReportsController@getLtpStatsGraphView']);
    Route::get('reports/ltp-stats-graph-view-by-language', 'ReportsController@ltpStatsGraphViewByLanguage')->name('reports/ltp-stats-graph-view-by-language');
    Route::get('get-ltp-stats-graph-view-by-language', ['as' => 'get-ltp-stats-graph-view-by-language', 'uses' => 'ReportsController@getLtpStatsGraphViewByLanguage']);

    Route::get('reports/report-by-org-admin-view', 'ReportsController@reportByOrgAdminView')->name('reports/report-by-org-admin-view');
    Route::get('reports/report-by-org-email-view', 'ReportsController@reportByOrgEmailView')->name('reports/report-by-org-email-view');
    Route::get('reports/report-by-org-email-ajax', 'ReportsController@reportByOrgEmailAjax')->name('reports/report-by-org-email-ajax');
    Route::get('send-email-report-by-org', 'ReportsController@sendEmailReportByOrg')->name('send-email-report-by-org');

    Route::get('cancelled-term-language', ['as' => 'cancelled-term-language', 'uses' => 'ReportsController@cancelledTermLanguage']);
    Route::get('courses-term-language', ['as' => 'courses-term-language', 'uses' => 'ReportsController@coursesTermLanguage']);
    Route::get('classes-term-language', ['as' => 'classes-term-language', 'uses' => 'ReportsController@classesTermLanguage']);
    Route::get('total-classes-per-term', ['as' => 'total-classes-per-term', 'uses' => 'ReportsController@totalClassesPerTerm']);

    /**
     * Text Routes
     */
    Route::get('edit-enrolment-is-open-text/{id}', ['as' => 'edit-enrolment-is-open-text', 'uses' => 'TextController@editEnrolmentIsOpenText']);
    Route::get('view-enrolment-is-open-text/{id}', ['as' => 'view-enrolment-is-open-text', 'uses' => 'TextController@viewEnrolmentIsOpenText']);
    Route::put('store-enrolment-is-open-text/{id}', ['as' => 'store-enrolment-is-open-text', 'uses' =>  'TextController@storeEnrolmentIsOpenText']);
    Route::get('view-general-email-text/{id}', ['as' => 'view-general-email-text', 'uses' => 'TextController@viewGeneralEmailText']);
    Route::resource('text', 'TextController');
    Route::get('view-convocation-email-text', ['as' => 'view-convocation-email-text', 'uses' => 'TextController@viewConvocationEmailText']);
    Route::get('view-default-email-waitlist-text', ['as' => 'view-default-email-waitlist-text', 'uses' => 'TextController@viewDefaultEmailWaitlistText']);
    Route::get('view-custom-email-waitlist-text', ['as' => 'view-custom-email-waitlist-text', 'uses' => 'TextController@viewCustomEmailWaitlistText']);

    /**
     * Teachers Routes
     */
    Route::resource('teachers', 'TeachersController');
    Route::post('mark-no-show', ['as' => 'mark-no-show', 'uses' => 'TeachersController@markNoShow']);
    Route::post('undo-no-show', ['as' => 'undo-no-show', 'uses' => 'TeachersController@undoNoShow']);

    Route::get('teacher-search-user', ['as' => 'teacher-search-user', 'uses' => 'TeachersController@teacherSearchUser']);
    Route::get('teacher-ltpdata-view/{id}', ['as' => 'teacher-ltpdata-view', 'uses' => 'TeachersController@teacherLtpdataView']);

    Route::get('teacher-email-classrooms-to-teachers', ['as' => 'teacher-email-classrooms-to-teachers', 'uses' => 'TeachersController@teacherEmailClassroomsToTeachers']);

    Route::get('teacher-show-classrooms-per-teacher', ['as' => 'teacher-show-classrooms-per-teacher', 'uses' => 'TeachersController@teacherShowClassroomsPerTeacher']);
    Route::get('teacher-email-classrooms-to-teachers-view', ['as' => 'teacher-email-classrooms-to-teachers-view', 'uses' => 'TeachersController@teacherEmailClassroomsToTeachersView']);

    Route::get('teacher-dashboard', 'TeachersController@teacherDashboard')->name('teacher-dashboard')->middleware('first-time-login');

    Route::put('ajax-teacher-update', ['as' => 'ajax-teacher-update', 'uses' => 'TeachersController@ajaxTeacherUpdate']);

    Route::get('teacher-view-classrooms', ['as' => 'teacher-view-classrooms', 'uses' => 'TeachersController@teacherViewClassrooms']);

    Route::get('teacher-view-all-classrooms', ['as' => 'teacher-view-all-classrooms', 'uses' => 'TeachersController@teacherViewAllClassrooms']);

    Route::post('teacher-show-students', ['as' => 'teacher-show-students', 'uses' => 'TeachersController@teacherShowStudents']);

    Route::post('teacher-show-student-emails-only', ['as' => 'teacher-show-student-emails-only', 'uses' => 'TeachersController@teacherShowStudentEmailsOnly']);

    Route::post('teacher-enter-results', ['as' => 'teacher-enter-results', 'uses' => 'TeachersController@teacherEnterResults']);

    Route::put('ajax-save-results', ['as' => 'ajax-save-results', 'uses' => 'TeachersController@ajaxSaveResults']);

    Route::get('teacher-select-week/{code}', ['as' => 'teacher-select-week', 'uses' => 'TeachersController@teacherSelectWeek']);

    Route::get('teacher-week-table', ['as' => 'teacher-week-table', 'uses' => 'TeachersController@teacherWeekTable']);

    Route::get('teacher-manage-attendances', ['as' => 'teacher-manage-attendances', 'uses' => 'TeachersController@teacherManageAttendances']);

    Route::put('ajax-teacher-attendance-update', ['as' => 'ajax-teacher-attendance-update', 'uses' => 'TeachersController@ajaxTeacherAttendanceUpdate']);

    Route::get('ajax-get-remark', ['as' => 'ajax-get-remark', 'uses' => 'TeachersController@ajaxGetRemark']);

    Route::get('ajax-show-if-enrolled-next-term', ['as' => 'ajax-show-if-enrolled-next-term', 'uses' => 'TeachersController@ajaxShowIfEnrolledNextTerm']);
    Route::get('ajax-show-if-enrolled-next-term-placement', ['as' => 'ajax-show-if-enrolled-next-term-placement', 'uses' => 'TeachersController@ajaxShowIfEnrolledNextTermPlacement']);
    Route::get('ajax-check-if-assigned', ['as' => 'ajax-check-if-assigned', 'uses' => 'TeachersController@ajaxCheckIfAssigned']);
    Route::get('ajax-check-if-not-assigned', ['as' => 'ajax-check-if-not-assigned', 'uses' => 'TeachersController@ajaxCheckIfNotAssigned']);

    Route::get('ajax-show-overall-attendance', ['as' => 'ajax-show-overall-attendance', 'uses' => 'TeachersController@ajaxShowOverallAttendance']);

    Route::get('teacher-assign-course-view', ['as' => 'teacher-assign-course-view', 'uses' => 'TeachersController@teacherAssignCourseView']);
    Route::get('teacher-check-schedule-count', ['as' => 'teacher-check-schedule-count', 'uses' => 'TeachersController@teacherCheckScheduleCount']);
    Route::put('teacher-save-assigned-course', ['as' => 'teacher-save-assigned-course', 'uses' => 'TeachersController@teacherSaveAssignedCourse']);
    Route::put('teacher-nothing-to-modify', ['as' => 'teacher-nothing-to-modify', 'uses' => 'TeachersController@teacherNothingToModify']);
    Route::put('teacher-verify-and-not-assign', ['as' => 'teacher-verify-and-not-assign', 'uses' => 'TeachersController@teacherVerifyAndNotAssign']);
    Route::get('teacher-enrolment-preview', ['as' => 'teacher-enrolment-preview', 'uses' => 'TeachersController@teacherEnrolmentPreview']);
    Route::get('teacher-enrolment-preview-table-view', ['as' => 'teacher-enrolment-preview-table-view', 'uses' => 'TeachersController@teacherEnrolmentPreviewTableView']);
    Route::get('teacher-enrolment-preview-table', ['as' => 'teacher-enrolment-preview-table', 'uses' => 'TeachersController@teacherEnrolmentPreviewTable']);
    Route::delete('teacher-delete-form', ['as' => 'teacher-delete-form', 'uses' => 'TeachersController@teacherDeleteForm']);

    Route::resource('writing-tips', 'WritingTipController');
    Route::get('send-writing-tip-email/{writingTip}',  ['as' => 'send-writing-tip-email', 'uses' => 'WritingTipController@sendWritingTipEmail']);
    Route::post('selective-send-writing-tip-email/{writingTip}',  ['as' => 'selective-send-writing-tip-email', 'uses' => 'WritingTipController@selectiveSendWritingTipEmail']);

    /*
     * Preview Routes
     */
    Route::get('preview-class-status', 'PreviewController@previewClassStatus')->name('preview-class-status');
    Route::get('preview-course-3', 'PreviewController@previewCourse3')->name('preview-course-3');

    Route::get('preview-vsa-page-1', ['as' => 'preview-vsa-page-1', 'uses' => 'PreviewController@vsaPage1']);
    Route::get('preview-vsa-page-2', ['as' => 'preview-vsa-page-2', 'uses' => 'PreviewController@vsaPage2']);
    Route::get('preview-classrooms/{code}', ['as' => 'preview-classrooms', 'uses' => 'PreviewController@previewClassrooms']);

    Route::any('insert-priority-1', ['as' => 'insert-priority-1', 'uses' => 'PreviewController@insertPriority1']);
    Route::any('insert-priority-2', ['as' => 'insert-priority-2', 'uses' => 'PreviewController@insertPriority2']);
    Route::any('insert-priority-2-placement', ['as' => 'insert-priority-2-placement', 'uses' => 'PreviewController@insertPriority2Placement']);
    Route::any('insert-priority-3', ['as' => 'insert-priority-3', 'uses' => 'PreviewController@insertPriority3']);
    Route::any('insert-priority-4', ['as' => 'insert-priority-4', 'uses' => 'PreviewController@insertPriority4']);
    Route::any('insert-priority-5', ['as' => 'insert-priority-5', 'uses' => 'PreviewController@insertPriority5']);

    Route::any('order-codes', ['as' => 'order-codes', 'uses' => 'PreviewController@orderCodes']);
    Route::any('assign-course-sched-to-student', ['as' => 'assign-course-sched-to-student', 'uses' => 'PreviewController@assignCourseScheduleToStudent']);
    Route::any('check-code-if-exists-in-preview', ['as' => 'check-code-if-exists-in-preview', 'uses' => 'PreviewController@checkCodeIfExistsInPreview']);
    Route::any('create-classrooms', ['as' => 'create-classrooms', 'uses' => 'PreviewController@createClassrooms']);
    Route::any('check-duplicate-in-preview', ['as' => 'check-duplicate-in-preview', 'uses' => 'PreviewController@checkDuplicatesInPreview']);
    Route::any('check-undefined-offset', ['as' => 'check-undefined-offset', 'uses' => 'PreviewController@checkUndefinedOffset']);


    Route::post('preview-sort-page', ['as' => 'preview-sort-page', 'uses' => 'PreviewController@orderCodes']);

    Route::post('ajax-preview', ['as' => 'ajax-preview', 'uses' => 'PreviewController@ajaxPreview']);
    Route::post('ajax-preview-modal', ['as' => 'ajax-preview-modal', 'uses' => 'PreviewController@ajaxPreviewModal']);
    Route::get('ajax-get-priority', ['as' => 'ajax-get-priority', 'uses' => 'PreviewController@ajaxGetPriority']);
    Route::get('ajax-move-students-form', ['as' => 'ajax-move-students-form', 'uses' => 'PreviewController@ajaxMoveStudentsForm']);
    Route::post('ajax-move-students', ['as' => 'ajax-move-students', 'uses' => 'PreviewController@ajaxMoveStudents']);
    Route::post('ajax-select-classroom', ['as' => 'ajax-select-classroom', 'uses' => 'PreviewController@ajaxSelectClassroom']);
    Route::post('send-individual-convocation', ['as' => 'send-individual-convocation', 'uses' => 'PreviewController@sendIndividualConvocation']);
    Route::get('send-convocation', 'PreviewController@sendConvocation')->name('send-convocation');
    Route::get('preview-waitlisted', 'PreviewController@previewWaitlisted')->name('preview-waitlisted');
    Route::get('cancelled-convocation-view', 'PreviewController@cancelledConvocaitonView')->name('cancelled-convocation-view');
    Route::put('undelete-pash/{id}', ['as' => 'undelete-pash', 'uses' => 'PreviewController@unDeletePash']);

    Route::get('preview-merged-forms', 'PreviewController@previewMergedForms')->name('preview-merged-forms');
    Route::post('ajax-preview-course-boxes', ['as' => 'ajax-preview-course-boxes', 'uses' => 'PreviewController@ajaxPreviewCourseBoxes']);
    Route::post('ajax-preview-get-student-count', ['as' => 'ajax-preview-get-student-count', 'uses' => 'PreviewController@ajaxPreviewGetStudentCount']);
    Route::post('ajax-preview-get-pending-placement-count', ['as' => 'ajax-preview-get-pending-placement-count', 'uses' => 'PreviewController@ajaxPreviewGetPendingPlacementCount']);
    Route::post('ajax-preview-get-student-priority-status', ['as' => 'ajax-preview-get-student-priority-status', 'uses' => 'PreviewController@ajaxPreviewGetStudentPriorityStatus']);
    Route::post('ajax-preview-get-student-current-class', ['as' => 'ajax-preview-get-student-current-class', 'uses' => 'PreviewController@ajaxPreviewGetStudentCurrentClass']);

    Route::get('ajax-preview-get-remarks', ['as' => 'ajax-preview-get-remarks', 'uses' => 'PreviewController@ajaxPreviewGetRemarks']);
    Route::put('ajax-preview-post-remarks', ['as' => 'ajax-preview-post-remarks', 'uses' => 'PreviewController@ajaxPreviewPostRemarks']);

    Route::get('ajax-select-teacher', ['as' => 'ajax-select-teacher', 'uses' => 'PreviewController@ajaxSelectTeacher']);
    Route::put('ajax-update-teacher', ['as' => 'ajax-update-teacher', 'uses' => 'PreviewController@ajaxUpdateTeacher']);

    Route::post('ajax-class-boxes', ['as' => 'ajax-class-boxes', 'uses' => 'PreviewController@ajaxClassBoxes']);
    Route::post('ajax-get-student-count-per-class', ['as' => 'ajax-get-student-count-per-class', 'uses' => 'PreviewController@ajaxGetStudentCountPerClass']);
    Route::post('ajax-get-no-show-count-per-class', ['as' => 'ajax-get-no-show-count-per-class', 'uses' => 'PreviewController@ajaxGetNoShowCountPerClass']);
    Route::get('view-classrooms-per-section/{code}', ['as' => 'view-classrooms-per-section', 'uses' => 'PreviewController@viewClassroomsPerSection']);

    Route::post('insert-record-to-preview', ['as' => 'insert-record-to-preview', 'uses' => 'PreviewController@insertRecordToPreview']);

    /**
     * User Routes
     */
    Route::resource('users', 'UserController');
    // separate password reset form
    Route::get('/user/{id}/passwordreset', ['as' => 'users.passwordreset', 'uses' => 'UserController@passwordReset']);
    Route::put('/user/{id}/resetpassword', ['as' => 'users.resetpassword', 'uses' => 'UserController@resetPassword']);
    // management of enrolment data per user
    Route::get('user/{id}/manage-user-enrolment-data', ['as' => 'manage-user-enrolment-data', 'uses' => 'UserController@manageUserEnrolmentData']);
    Route::get('user/{id}/manage-user-enrolment-data-by-history', ['as' => 'manage-user-enrolment-data-by-history', 'uses' => 'UserController@manageUserEnrolmentDataByHistory']);
    Route::get('user/{id}/enrol-student-to-course-form', ['as' => 'enrol-student-to-course-form', 'uses' => 'UserController@enrolStudentToCourseForm']);
    Route::post('user/enrol-student-to-course-insert', ['as' => 'enrol-student-to-course-insert', 'uses' => 'UserController@enrolStudentToCourseInsert']);
    Route::get('user/{id}/enrol-student-to-placement-form', ['as' => 'enrol-student-to-placement-form', 'uses' => 'UserController@enrolStudentToPlacementForm']);
    Route::post('user/enrol-student-to-placement-insert', ['as' => 'enrol-student-to-placement-insert', 'uses' => 'UserController@enrolStudentToPlacementInsert']);

    // import existing student from SDDEXTR to Users
    Route::get('user/import-existing-from-sddextr-form', ['as' => 'import-existing-from-sddextr-form', 'uses' => 'UserController@importExistingFromSDDEXTRForm']);
    Route::post('user/import-existing-from-sddextr', ['as' => 'import-existing-from-sddextr', 'uses' => 'UserController@importExistingFromSDDEXTR']);

    // update contract expiry field
    Route::put('/user/user-update-contract', ['as' => 'user-update-contract', 'uses' => 'UserController@userUpdateContract']);
    // adminLTEv3 views
    Route::get('user/{id}/view-user-profile', ['as' => 'view-user-profile', 'uses' => 'UserController@viewUserProfile']);

    Route::get('user/switch/start/{id}', 'UserController@user_switch_start');
    Route::get('user/switch/stop', 'UserController@user_switch_stop');

    // update index numbers
    Route::get('update-index-view', ['as' => 'update-index-view', 'uses' => 'UserController@updateIndexView']);
    Route::get('update-PASH', ['as' => 'update-PASH', 'uses' => 'UserController@updatePASH']);
    Route::post('update-PASH-IndexID', ['as' => 'update-PASH-IndexID', 'uses' => 'UserController@updatePASHIndexID']);
    Route::post('update-PASH-trashed', ['as' => 'update-PASH-trashed', 'uses' => 'UserController@updatePASHTrashed']);
    Route::post('update-enrolment-index', ['as' => 'update-enrolment-index', 'uses' => 'UserController@updateEnrolmentIndex']);
    Route::post('update-placement-index', ['as' => 'update-placement-index', 'uses' => 'UserController@updatePlacementIndex']);
    Route::post('update-modifiedforms-index', ['as' => 'update-modifiedforms-index', 'uses' => 'UserController@updateModifiedFormsIndex']);

    /**
     * Contract Routes
     */
    Route::get('get-contract-file', ['as' => 'get-contract-file', 'uses' => 'ContractsController@getContractFile']);

    /**
     * Billing Routes
     */
    Route::get('billing-index', ['as' => 'billing-index', 'uses' => 'BillingController@billingIndex']);
    Route::get('ajax-billing-table', ['as' => 'ajax-billing-table', 'uses' => 'BillingController@ajaxBillingTable']);
    Route::get('billing-json', function () {
        return view('billing.billing_table');
    })->name('billing-json');

    Route::get('billing-admin-selfpaying-student-view', 'BillingController@billingAdminSelfpayingStudentView')->name('billing-admin-selfpaying-student-view');
    Route::get('ajax-selfpaying-student-table', ['as' => 'ajax-selfpaying-student-table', 'uses' => 'BillingController@ajaxSelfpayingStudentTable']);
    Route::get('billing-admin-selfpaying-view', 'BillingController@billingAdminSelfpayingView')->name('billing-admin-selfpaying-view');
    Route::get('ajax-selfpaying-by-year-table', ['as' => 'ajax-selfpaying-by-year-table', 'uses' => 'BillingController@ajaxSelfpayingByYearTable']);

    // Waitlist routes
    Route::get('waitListOneListCount', 'WaitlistController@waitListOneListCount')->name('waitListOneListCount');
    Route::get('noClassStudentCount', 'WaitlistController@noClassStudentCount')->name('noClassStudentCount');
    Route::get('waitListOneList/{te_code}', ['as' => 'waitListOneList', 'uses' => 'WaitlistController@waitListOneList']);
    Route::get('ajax-check-if-waitlisted', ['as' => 'ajax-check-if-waitlisted', 'uses' => 'WaitlistController@ajaxCheckIfWaitlisted']);
    Route::get('waitlist-modal-form', ['as' => 'waitlist-modal-form', 'uses' => 'WaitlistController@waitlistModalForm']);
    Route::get('default-email-waitlist', ['as' => 'default-email-waitlist', 'uses' => 'WaitlistController@defaultEmailWaitlist']);
    Route::post('send-default-waitlist-email', ['as' => 'send-default-waitlist-email', 'uses' => 'WaitlistController@sendDefaultWaitlistEmail']);
    Route::get('custom-email-waitlist', ['as' => 'custom-email-waitlist', 'uses' => 'WaitlistController@customEmailWaitlist']);
    Route::post('send-custom-waitlist-email', ['as' => 'send-custom-waitlist-email', 'uses' => 'WaitlistController@sendCustomWaitlistEmail']);

    // Excel download table from view
    Route::get('excel', 'ExcelController@getBladeExcel');

    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');

    // Classroom routes
    Route::resource('classrooms', 'ClassroomController');
    Route::get('get-schedule-days', ['as' => 'get-schedule-days', 'uses' => 'ClassroomController@getScheduleDays']);
    Route::get('ajax-index-calendar', ['as' => 'ajax-index-calendar', 'uses' => 'ClassroomController@ajaxIndexCalendar']);
    Route::get('index-calendar', ['as' => 'index-calendar', 'uses' => 'ClassroomController@indexCalendar']);
    Route::post('view-calendar', ['as' => 'view-calendar', 'uses' => 'ClassroomController@viewCalendar']);

    Route::resource('course-schedule', 'CourseSchedController');
    Route::resource('schedules', 'ScheduleController');

    Route::post('store-non-standard-schedule', ['as' => 'store-non-standard-schedule', 'uses' => 'ScheduleController@storeNonStandardSchedule']);
    Route::put('update-non-standard-schedule/{id}', ['as' => 'update-non-standard-schedule', 'uses' => 'ScheduleController@updateNonStandardSchedule']);

    Route::resource('terms', 'TermController');
    Route::resource('courses', 'CourseController');
    Route::get('check-existing-tecode', ['as' => 'check-existing-tecode', 'uses' => 'CourseController@checkExistingTeCode']);

    Route::resource('organizations', 'OrgController');
    Route::resource('placement-schedule', 'PlacementScheduleController');

    // Enrolment forms controller
    Route::resource('preenrolment', 'PreenrolmentController');
    Route::get('/preenrolment/edit-fields/indexno/{indexno}/term/{term}/{tecode}/{form}', ['as' => 'edit-enrolment-fields', 'uses' => 'PreenrolmentController@editEnrolmentFields'])->where('tecode', '(.*)');
    Route::put('/preenrolment/update-fields/indexno/{indexno}/term/{term}/{tecode}/{form}', ['as' => 'update-enrolment-fields', 'uses' => 'PreenrolmentController@updateEnrolmentFields']);
    Route::get('/preenrolment/nothing-to-modify/indexno/{indexno}/term/{term}/{tecode}/{form}', ['as' => 'nothing-to-modify', 'uses' => 'PreenrolmentController@nothingToModify'])->where('tecode', '(.*)');

    Route::post('check-if-pash-record-exists', ['as' => 'check-if-pash-record-exists', 'uses' => 'AjaxController@checkIfPashRecordExists']);

    Route::get('send-reminder-emails', 'PreenrolmentController@sendReminderEmails')->name('send-reminder-emails');
    // Enrolment form cancellation route for administrators
    Route::delete('/delete/user/{staff}/course/{tecode}/term/{term}/{form}', ['as' => 'enrolment.destroy', 'uses' => 'PreenrolmentController@destroy'])->where('tecode', '(.*)');
    // Enrolment form cancellation route for administrators
    Route::delete('delete-no-email', ['as' => 'delete-no-email', 'uses' => 'PreenrolmentController@deleteNoEmail']);
    // Route to AJAX get student comments
    Route::post('ajax-std-comments', ['as' => 'ajax-std-comments', 'uses' => 'PreenrolmentController@ajaxStdComments']);

    Route::get('query-regular-forms-to-assign', ['as' => 'query-regular-forms-to-assign', 'uses' => 'PreenrolmentController@queryRegularFormsToAssign']);
    Route::get('query-orphan-forms-to-assign', ['as' => 'query-orphan-forms-to-assign', 'uses' => 'PreenrolmentController@queryOrphanFormsToAssign']);
    Route::get('admin-assign-course-view', ['as' => 'admin-assign-course-view', 'uses' => 'PreenrolmentController@adminAssignCourseView']);
    Route::get('admin-check-schedule-count', ['as' => 'admin-check-schedule-count', 'uses' => 'PreenrolmentController@adminCheckScheduleCount']);
    Route::put('admin-save-assigned-course', ['as' => 'admin-save-assigned-course', 'uses' => 'PreenrolmentController@adminSaveAssignedCourse']);
    Route::put('admin-nothing-to-modify', ['as' => 'admin-nothing-to-modify', 'uses' => 'PreenrolmentController@adminNothingToModify']);
    Route::put('admin-verify-and-not-assign', ['as' => 'admin-verify-and-not-assign', 'uses' => 'PreenrolmentController@adminVerifyAndNotAssign']);
    Route::get('admin-manage-user-assign-course-view', ['as' => 'admin-manage-user-assign-course-view', 'uses' => 'PreenrolmentController@adminManageUserAssignCourseView']);

    /**
     * Placement forms controller
     */
    Route::resource('placement-form', 'PlacementFormController');
    Route::get('send-reminder-emails-placement', 'PlacementFormController@sendReminderEmailsPlacement')->name('send-reminder-emails-placement');
    Route::get('/placement-form-filtered', ['as' => 'placement-form-filtered', 'uses' => 'PlacementFormController@getFilteredPlacementForms']);
    Route::get('/placement-form-approved-view', ['as' => 'placement-form-approved-view', 'uses' => 'PlacementFormController@getApprovedPlacementFormsView']);

    // route of edit placement form fields
    Route::get('/placement/edit-fields/{id}/id', ['as' => 'edit-placement-fields', 'uses' => 'PlacementFormController@editPlacementFields']);
    Route::put('/placement/update-fields/{id}/id', ['as' => 'update-placement-fields', 'uses' => 'PlacementFormController@updatePlacementFields']);

    // route of assign course form to placement view 
    Route::get('/placement-form-assign/{id}', ['as' => 'placement-form-assign', 'uses' => 'PlacementFormController@editAssignCourse']);

    Route::put('/placement-form-assign-course/{id}', ['as' => 'placement-form-assign-course', 'uses' => 'PlacementFormController@assignCourseToPlacement']);

    // Placement form cancellation route for administrators
    Route::delete('/delete/user/{staff}/lang/{lang}/term/{term}/{eform}', ['as' => 'placement.destroy', 'uses' => 'PlacementFormController@destroy'])->where('tecode', '(.*)');
    // Route to AJAX get student comments
    Route::post('ajax-placement-comments', ['as' => 'ajax-placement-comments', 'uses' => 'PlacementFormController@ajaxPlacementComments']);

    // Routes for managing placement exams
    Route::get('manage-exam-view', ['as' => 'manage-exam-view', 'uses' => 'PlacementFormController@manageExamView']);
    Route::get('manage-exam-table', ['as' => 'manage-exam-table', 'uses' => 'PlacementFormController@manageExamtable']);

    // Routes Placement Exam Results
    Route::post('exam-result-save', ['as' => 'exam-result-save', 'uses' => 'ExamResultController@examResultSave']);

    Route::resource('rooms', 'RoomsController');
    Route::resource('waitlist', 'WaitlistController');

    Route::resource('selfpayform', 'SelfPayController', ['only' => ['index', 'update']]);
    Route::get('selfpayform/{indexid}/{tecode}/{term}', ['as' => 'selfpayform.edit', 'uses' => 'SelfPayController@edit']);
    Route::get('selfpayform/index-placement-selfpay', ['as' => 'index-placement-selfpay', 'uses' => 'SelfPayController@indexPlacementSelfPay']);
    Route::get('selfpayform/waitlisted-and-valid-cancelled-forms-view', ['as' => 'waitlisted-and-valid-cancelled-forms-view', 'uses' => 'SelfPayController@waitlistedAndValidCancelledFormsView']);
    Route::post('selfpayform/waitlisted-and-valid-cancelled-forms', ['as' => 'waitlisted-and-valid-cancelled-forms', 'uses' => 'SelfPayController@waitlistedAndValidCancelledForms']);

    Route::get('selfpayform/approved-placement-selfpay', ['as' => 'approved-placement-selfpay', 'uses' => 'SelfPayController@approvedPlacementSelfPay']);
    Route::get('selfpayform/pending-placement-selfpay', ['as' => 'pending-placement-selfpay', 'uses' => 'SelfPayController@pendingPlacementSelfPay']);
    Route::get('selfpayform/cancelled-placement-selfpay', ['as' => 'cancelled-placement-selfpay', 'uses' => 'SelfPayController@cancelledPlacementSelfPay']);


    Route::get('selfpayform/edit-placement-selfpay/{indexid}/{language}/{term}', ['as' => 'edit-placement-selfpay', 'uses' => 'SelfPayController@editPlacementSelfPay']);
    Route::put('selfpayform/post-placement-selfpay/{indexid}', ['as' => 'post-placement-selfpay', 'uses' => 'SelfPayController@postPlacementSelfPay']);

    Route::get('selfpayform/show-schedule-selfpay', ['as' => 'show-schedule-selfpay', 'uses' => 'AjaxController@showScheduleSelfPay']);
    Route::post('selfpayform/post-decision-selfpay', ['as' => 'post-decision-selfpay', 'uses' => 'AjaxController@postDecisionSelfPay']);

    // route to admin attach documents to SelfPayment forms
    Route::get('admin-add-attachments/{indexid}/{lang}/{tecode}/{term}/{eform}', ['as' => 'admin-add-attachments', 'uses' => 'SelfPayController@adminAddAttachmentsView']);
    Route::get('admin-add-attachments-placement/{indexid}/{lang}/{term}/{eform}', ['as' => 'admin-add-attachments-placement', 'uses' => 'SelfPayController@adminAddAttachmentsPlacementView']);
    Route::put('admin-add-attachments-store', ['as' => 'admin-add-attachments-store', 'uses' => 'SelfPayController@adminAddAttachmentsStore']);
    Route::put('admin-add-attachments-placement-store', ['as' => 'admin-add-attachments-placement-store', 'uses' => 'SelfPayController@adminAddAttachmentsPlacementStore']);

    Route::get('/placement-form-approved', ['as' => 'placement-form-approved', 'uses' => 'ValidateFormsController@getApprovedPlacementForms']);

    Route::get('vsa-page-1', ['as' => 'vsa-page-1', 'uses' => 'ValidateFormsController@vsaPage1']);
    Route::get('vsa-page-2', ['as' => 'vsa-page-2', 'uses' => 'TempSortController@vsaPage2']);

    // temporary page for validating queries /admin/validate-page
    Route::any('validate-page', ['as' => 'validate-page', 'uses' => 'ValidateFormsController@getApprovedEnrolmentForms']);

    // temporary page for sorting queries /admin/sort-page
    Route::post('sort-page', ['as' => 'sort-page', 'uses' => 'TempSortController@orderCodes']);
});

//middleware to prevent back button and access cache
Route::group(['middleware' => 'prevent-back-history'], function () {
    Auth::routes();
    Route::match(['get', 'post'], 'register', function () {
        return redirect('/');
    });
    Route::get('/', 'WelcomeController@index');
});

Route::middleware(['auth'])->group(function () {
    Route::middleware(['first-time-login'])->group(function () { //middleware to force user to change password 
        //home page routes
        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/home-how-to-check-status', 'HomeController@homeHowToCheckStatus')->name('home-how-to-check-status');
        Route::get('/read-me-first', ['as' => 'read-me-first', 'uses' => 'HomeController@readMeFirst']);
        Route::get('/whatorg', ['as' => 'whatorg', 'uses' => 'HomeController@whatorg']);

        // late registration routes
        Route::get('/late-what-org', ['as' => 'late-what-org', 'uses' => 'LateEnrolmentController@lateWhatOrg']);
        Route::post('/late-what-form', ['as' => 'late-what-form', 'uses' => 'LateEnrolmentController@lateWhatForm']);
        Route::get('/late-registration', ['as' => 'late-registration', 'uses' => 'LateEnrolmentController@lateRegistration']);
        Route::post('/store-late-registration', ['as' => 'store-late-registration', 'uses' => 'LateEnrolmentController@storeLateRegistration']);
        Route::get('/late-selfpay-form', ['as' => 'late-selfpay-form', 'uses' => 'LateEnrolmentController@lateSelfpayForm']);
        Route::post('/store-late-selfpay-form', ['as' => 'store-late-selfpay-form', 'uses' => 'LateEnrolmentController@storeLateSelfpayForm']);
        Route::get('late-check-placement-form-ajax', ['as' => 'late-check-placement-form-ajax', 'uses' => 'LateEnrolmentController@lateCheckPlacementFormAjax']);
        Route::post('late-check-placement-course-ajax', ['as' => 'late-check-placement-course-ajax', 'uses' => 'LateEnrolmentController@lateCheckPlacementCourseAjax']);
        Route::get('late-check-enrolment-entries-ajax', ['as' => 'late-check-enrolment-entries-ajax', 'uses' => 'LateEnrolmentController@lateCheckEnrolmentEntriesAjax']);
        Route::get('late-check-placement-entries-ajax', ['as' => 'late-check-placement-entries-ajax', 'uses' => 'LateEnrolmentController@lateCheckPlacementEntriesAjax']);
        Route::get('late-check-selfpay-entries-ajax', ['as' => 'late-check-selfpay-entries-ajax', 'uses' => 'LateEnrolmentController@lateCheckSelfpayEntriesAjax']);
        Route::get('late-check-selfpay-placement-entries-ajax', ['as' => 'late-check-selfpay-placement-entries-ajax', 'uses' => 'LateEnrolmentController@lateCheckSelfpayPlacementEntriesAjax']);

        Route::post('/whatform', ['as' => 'whatform', 'uses' => 'HomeController@whatform'])->middleware('check-prev-url');
        Route::get('/submitted', ['as' => 'submitted', 'uses' => 'HomeController@previousSubmitted']);
        Route::get('/previous-submitted', ['as' => 'previous-submitted', 'uses' => 'HomeController@previousSubmitted']);
        Route::get('/history', ['as' => 'history', 'uses' => 'HomeController@history']);
        Route::post('/showform', ['as' => 'submitted.show', 'uses' => 'HomeController@showMod']);
        //Route::delete('/delete/user/{staff}/course/{tecode}', ['as' => 'submitted.destroy', 'uses' => 'HomeController@destroy'])->where('tecode', '(.*)');

        Route::get('student-edit-enrolment-form-view/{term}/{indexid}/{tecode}', ['as' => 'student-edit-enrolment-form-view', 'uses' => 'PreenrolmentController@studentEditEnrolmentFormView']);
        Route::post('student-update-enrolment-form', ['as' => 'student-update-enrolment-form', 'uses' => 'PreenrolmentController@studentUpdateEnrolmentForm']);
        Route::get('student-edit-placement-form-view/{id}', ['as' => 'student-edit-placement-form-view', 'uses' => 'PlacementFormController@studentEditPlacementFormView']);
        Route::post('student-update-placement-form', ['as' => 'student-update-placement-form', 'uses' => 'PlacementFormController@studentUpdatePlacementForm']);
        Route::post('select-ajax-student-edit', ['as' => 'select-ajax-student-edit', 'uses' => 'AjaxController@selectAjaxStudentEdit']);

        // cancellation routes with date limit middleware
        Route::delete('/delete/user/{staff}/course/{tecode}/term/{term}/{form}', ['middleware' => 'limit-cancel', 'as' => 'submitted.destroy', 'uses' => 'HomeController@destroy'])->where('tecode', '(.*)');
        Route::delete('/delete/user/{staff}/lang/{lang}/term/{term}/{eform}', ['middleware' => 'limit-cancel', 'as' => 'submittedPlacement.destroy', 'uses' => 'HomeController@destroyPlacement'])->where('tecode', '(.*)');

        //apply auth middleware only so students could edit their profile
        Route::resource('students', 'StudentController');
    });
    Route::get('password/expired', 'FirstTimeLoginController@expired')
        ->name('password.expired');
    Route::post('password/post_expired', 'FirstTimeLoginController@postExpired')
        ->name('password.post_expired');

    // route to cancel of convoked course class 
    Route::delete('cancel-convocation/{codeindexidclass}', ['middleware' => 'limit-cancel', 'as' => 'cancel-convocation', 'uses' => 'PreviewController@cancelConvocation']);

    // route to re-attach documents for SelfPayment forms
    Route::get('add-attachments/{indexid}/{lang}/{tecode}/{term}/{date}/{eform}', ['as' => 'add-attachments', 'uses' => 'SelfPayController@addAttachmentsView']);
    Route::get('add-attachments-placement/{indexid}/{lang}/{term}/{date}/{eform}', ['as' => 'add-attachments-placement', 'uses' => 'SelfPayController@addAttachmentsPlacementView']);
    Route::put('add-attachments-store', ['as' => 'add-attachments-store', 'uses' => 'SelfPayController@addAttachmentsStore']);
    Route::put('add-attachments-placement-store', ['as' => 'add-attachments-placement-store', 'uses' => 'SelfPayController@addAttachmentsPlacementStore']);

    // route for student printing certificates
    Route::get('pdfAttestation', ['as' => 'pdfAttestation', 'uses' => 'PrinterController@pdfAttestation']);
    Route::get('pdfAttestationBefore2019', ['as' => 'pdfAttestationBefore2019', 'uses' => 'PrinterController@pdfAttestationBefore2019']);

    Route::get('thankyou', function () {
        return view('thankyou');
    })->name('thankyou');
    Route::get('thankyouPlacement', function () {
        return view('thankyouPlacement');
    })->name('thankyouPlacement');
    Route::get('thankyouSelfPay', function () {
        return view('thankyouSelfPay');
    })->name('thankyouSelfPay');
});

// route to update email of student - this should be outside of auth middleware for the student to have access to this route 
Route::get('/verify/{student}/{temp_email}/{update_token}', ['as' => 'verify.updateProfileConfirmed', 'uses' => 'StudentController@updateProfileConfirmed']);
// route for ajax select on vsa-page-2
Route::post('select-ajax-admin', ['as' => 'select-ajax-admin', 'uses' => 'AjaxController@selectAjaxAdmin']);

//route for ajax jquery on organization
Route::get('org-select-ajax', ['as' => 'org-select-ajax', 'uses' => 'AjaxController@ajaxOrgSelect']);
// route for ajax jquery on certain dates
Route::get('get-date-ajax', ['as' => 'get-date-ajax', 'uses' => 'AjaxController@ajaxGetDate']);
// route for ajax jquery to compare organization in whatorg page
Route::post('org-compare-ajax', ['as' => 'org-compare-ajax', 'uses' => 'AjaxController@ajaxOrgCompare']);
// route for ajax jquery if forms have been cancelled or deleted
Route::get('is-cancelled-ajax', ['as' => 'is-cancelled-ajax', 'uses' => 'AjaxController@ajaxIsCancelled']);

Route::get('get-term-data-ajax', ['as' => 'get-term-data-ajax', 'uses' => 'AjaxController@ajaxGetTermData']);

Route::post('ajax-show-modal', ['as' => 'ajax-show-modal', 'uses' => 'AjaxController@ajaxShowModal']);
Route::post('ajax-show-modal-placement', ['as' => 'ajax-show-modal-placement', 'uses' => 'AjaxController@ajaxShowModalPlacement']);

//placement form routes
Route::get('/placementinfo', ['as' => 'placementinfo', 'uses' => 'PlacementFormController@getPlacementInfo']); // ->middleware('prevent-access-placement');
Route::post('/postplacementinfo-additional', ['as' => 'postplacementinfo-additional', 'uses' => 'PlacementFormController@postPlacementInfoAdditional']);
Route::post('check-placement-sched-ajax', ['as' => 'check-placement-sched-ajax', 'uses' => 'AjaxController@ajaxCheckPlacementSched']);
Route::post('/postplacementinfo', ['as' => 'postplacementinfo', 'uses' => 'PlacementFormController@postPlacementInfo']);
Route::post('/postSelfPayPlacementInfo', ['as' => 'postSelfPayPlacementInfo', 'uses' => 'PlacementFormController@postSelfPayPlacementInfo']);

//fee-paying form routes
Route::resource('selfpayform', 'SelfPayController', ['only' => ['create', 'store']]);

//if already selected YES to continue course, go to these routes
Route::resource('noform', 'NoFormController', ['only' => ['create', 'store', 'edit']]);

//main UN staff form routes
Route::resource('myform', 'RepoController');
Route::post('check-placement-course-ajax', ['as' => 'check-placement-course-ajax', 'uses' => 'AjaxController@ajaxCheckPlacementCourse']);

//main controller used for ajax jquery on all forms - myform, noform, selfpayform
Route::get('ajax-check-batch-has-ran', ['as' => 'ajax-check-batch-has-ran', 'uses' => 'AjaxController@ajaxCheckBatchHasRan']);
Route::post('select-ajax', ['as' => 'select-ajax', 'uses' => 'AjaxController@selectAjax']);
Route::post('select-ajax-all-courses', ['as' => 'select-ajax-all-courses', 'uses' => 'AjaxController@selectAjaxAllCourses']);
Route::post('select-ajax2', ['as' => 'select-ajax2', 'uses' => 'AjaxController@selectAjax2']);
Route::post('select-ajax-level-one', ['as' => 'select-ajax-level-one', 'uses' => 'AjaxController@selectAjaxLevelOne']);
Route::get('check-placement-form-ajax', ['as' => 'check-placement-form-ajax', 'uses' => 'AjaxController@ajaxCheckPlacementForm']);
Route::get('check-placement-entries-ajax', ['as' => 'check-placement-entries-ajax', 'uses' => 'AjaxController@ajaxCheckPlacementEntries']);
Route::get('check-enrolment-entries-ajax', ['as' => 'check-enrolment-entries-ajax', 'uses' => 'AjaxController@ajaxCheckEnrolmentEntries']);
Route::get('check-selfpay-entries-ajax', ['as' => 'check-selfpay-entries-ajax', 'uses' => 'AjaxController@ajaxCheckSelfpayEntries']);
Route::get('check-selfpay-placement-entries-ajax', ['as' => 'check-selfpay-placement-entries-ajax', 'uses' => 'AjaxController@ajaxCheckSelfpayPlacementEntries']);
Route::get('ajax-show-full-select-dropdown', ['as' => 'ajax-show-full-select-dropdown', 'uses' => 'AjaxController@ajaxShowFullSelectDropdown']);
Route::get('ajax-change-hr-approval', ['as' => 'ajax-change-hr-approval', 'uses' => 'AjaxController@ajaxChangeHRApproval']);
Route::get('ajax-change-org-in-form', ['as' => 'ajax-change-org-in-form', 'uses' => 'AjaxController@ajaxChangeOrgInForm']);
Route::get('ajax-convert-to-selfpay', ['as' => 'ajax-convert-to-selfpay', 'uses' => 'AjaxController@ajaxConvertToSelfpay']);
Route::get('ajax-convert-to-regular', ['as' => 'ajax-convert-to-regular', 'uses' => 'AjaxController@ajaxConvertToRegular']);
Route::get('ajax-show-language-dropdown', ['as' => 'ajax-show-language-dropdown', 'uses' => 'AjaxController@ajaxShowLanguageDropdown']);
Route::put('ajax-exclude-from-billing', ['as' => 'ajax-exclude-from-billing', 'uses' => 'AjaxController@ajaxExcludeFromBilling']);
Route::get('ajax-select-country', ['as' => 'ajax-select-country', 'uses' => 'AjaxController@ajaxSelectCountry']);
Route::get('ajax-file-attach-badge-cdl', ['as' => 'ajax-file-attach-badge-cdl', 'uses' => 'AjaxController@ajaxFileAttachBadgeCdl']);
Route::get('ajax-file-attach-msu', ['as' => 'ajax-file-attach-msu', 'uses' => 'AjaxController@ajaxFileAttachMSU']);
Route::get('ajax-file-attach-spouse', ['as' => 'ajax-file-attach-spouse', 'uses' => 'AjaxController@ajaxFileAttachSpouse']);
Route::get('ajax-file-attach-spouse-1', ['as' => 'ajax-file-attach-spouse-1', 'uses' => 'AjaxController@ajaxFileAttachSpouse1']);
Route::get('ajax-file-attach-spouse-2', ['as' => 'ajax-file-attach-spouse-2', 'uses' => 'AjaxController@ajaxFileAttachSpouse2']);
Route::get('ajax-file-attach-retired', ['as' => 'ajax-file-attach-retired', 'uses' => 'AjaxController@ajaxFileAttachRetired']);
Route::get('ajax-file-attach-serv', ['as' => 'ajax-file-attach-serv', 'uses' => 'AjaxController@ajaxFileAttachServ']);
Route::get('ajax-file-attach-ngo', ['as' => 'ajax-file-attach-ngo', 'uses' => 'AjaxController@ajaxFileAttachNgo']);
Route::get('ajax-file-attach-press', ['as' => 'ajax-file-attach-press', 'uses' => 'AjaxController@ajaxFileAttachPress']);


// ajax use to get section number of cs_unique
Route::get('get-section-no-ajax', ['as' => 'get-section-no-ajax', 'uses' => 'AjaxController@ajaxGetSectionNo']);
Route::get('show-section-ajax', ['as' => 'show-section-ajax', 'uses' => 'AjaxController@ajaxShowSection']);
Route::post('delete-day-param-ajax', ['as' => 'delete-day-param-ajax', 'uses' => 'AjaxController@ajaxDeleteDayParam']);
Route::get('get-section-param-ajax', ['as' => 'get-section-param-ajax', 'uses' => 'AjaxController@ajaxGetSectionParam']);

Route::group(['middleware' => 'open-close-approval-routes'], function () {
    //url routing for manager approval page
    Route::get('/approval/{staff}/{tecode}/{id}/{form}/{term}', ['as' => 'approval.getform', 'uses' => 'ApprovalController@getForm']);

    //url routing for manager placement test approval page
    Route::get('/approval/{staff}/{lang}/placement/{id}/{form}/{term}', ['as' => 'approval.getplacementformdata', 'uses' => 'ApprovalController@getPlacementFormData']);
});

Route::group(['middleware' => 'open-close-approval-routes-hr'], function () {
    //url routing for hr partner approval page
    Route::get('/approvalhr/{id}/{term}', ['as' => 'approval.getform2hr', 'uses' => 'ApprovalController@getForm2hr']);

    //url routing for hr partner placement test approval page
    Route::get('/approvalhr/placement/{id}/{term}', ['as' => 'approval.getplacementformdata2hr', 'uses' => 'ApprovalController@getPlacementFormData2hr']);
});

Route::put('/approval/user/{staff}/course/{tecode}/{formcount}/{term}', ['as' => 'approval.updateform', 'uses' => 'ApprovalController@updateForm'])->where('tecode', '(.*)'); // where clause accepts routes with slashes
Route::put('/approvalhr/user/{staff}/course/{tecode}/{formcount}/{term}', ['as' => 'approval.updateform2hr', 'uses' => 'ApprovalController@updateForm2hr'])->where('tecode', '(.*)'); // where clause accepts routes with slashes
Route::put('/approval/user/{staff}/lang/{lang}/{formcount}/{term}', ['as' => 'approval.updateplacementformdata', 'uses' => 'ApprovalController@updatePlacementFormData']);
Route::put('/approvalhr/user/{staff}/lang/{lang}/{formcount}/{term}', ['as' => 'approval.updateplacementformdata2hr', 'uses' => 'ApprovalController@updatePlacementFormData2hr']);

// route for public page reports per organization
Route::get('/report-by-org/{param}/{org}/{term}/{year}', ['as' => 'report-by-org', 'uses' => 'ReportsController@reportByOrg']);
Route::get('report-by-org-admin', 'ReportsController@reportByOrgAdmin')->name('report-by-org-admin');

//public pages
Route::get('eform', function () {
    return view('confirmation_page_unog');
})->name('eform');
Route::get('eform2', function () {
    return view('confirmation_page_hr');
})->name('eform2');
Route::get('confirmationLinkUsed', function () {
    return view('confirmationLinkUsed');
})->name('confirmationLinkUsed');
Route::get('confirmationLinkExpired', function () {
    return view('confirmationLinkExpired');
})->name('confirmationLinkExpired');
Route::get('updateLinkExpired', function () {
    return view('updateLinkExpired');
})->name('updateLinkExpired');
Route::get('new_user_msg', function () {
    return view('new_user_msg');
})->name('new_user_msg');
Route::get('page_not_available', function () {
    return view('page_not_available');
})->name('page_not_available');


// route for verification of UN staff
Route::resource('newuser', 'NewUserController', ['only' => ['create', 'store']]);

// route for web form request to create new account of UN staff
// Route::get('get-new-new-user', ['as' => 'get-new-new-user', 'uses' => 'NewUserController@getNewNewUser']);
// Route::post('post-new-new-user', ['as' => 'post-new-new-user', 'uses' => 'NewUserController@postNewNewUser']);

// route for verification of non-UN staff
Route::get('get-new-outside-user', ['as' => 'get-new-outside-user', 'uses' => 'NewUserController@getNewOutsideUser']);
Route::post('post-new-outside-user', ['as' => 'post-new-outside-user', 'uses' => 'NewUserController@postNewOutsideUser']);

// route for web form request to create new account of non-UN staff
Route::get('get-new-outside-user-form', ['as' => 'get-new-outside-user-form', 'uses' => 'NewUserController@getNewOutsideUserForm']);
Route::post('post-new-outside-user-form', ['as' => 'post-new-outside-user-form', 'uses' => 'NewUserController@postNewOutsideUserForm']);

//Route::get('/', function () { return view('welcome'); });

Auth::routes();
Route::match(['get', 'post'], 'register', function () {
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
Route::get('simpleroutes', function () {
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
})->middleware(['auth', 'isAdmin']);
//e-mail template preview on browser
//use Illuminate\Mail\Markdown;

//Route::get('mail-preview', function () {
//    $markdown = new Markdown(view(), config('mail.markdown'));

//    return $markdown->render('emails.approval');
//});
