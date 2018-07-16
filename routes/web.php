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
Route::group(['middleware' => ['auth','isAdmin'], 'prefix' => 'admin'],function(){
    //admin routes
    Route::get('/', function () { return view('admin.index'); })->name('admin_dashboard');
    Route::get('user/import', 'AdminController@importUser')->name('import-user');
    Route::post('user/import', 'AdminController@handleImportUser')->name('bulk-import-user');
    Route::get('user/import-exist', 'AdminController@importExistingUser')->name('import-existing-user');
    Route::post('user/import-exist', 'AdminController@handleImportExistingUser')->name('bulk-import-existing-user');
    Route::resource('users', 'UserController');

    // separate password reset form
    Route::get('/user/{id}/passwordreset', ['as' => 'users.passwordreset', 'uses' => 'UserController@passwordReset' ]);
    Route::put('/user/{id}/resetpassword', ['as' => 'users.resetpassword', 'uses' => 'UserController@resetPassword' ]);
    // management of enrolment data per user
    Route::get('user/{id}/manage-user-enrolment-data', ['as' => 'manage-user-enrolment-data', 'uses' => 'UserController@manageUserEnrolmentData' ]);
    
    // page for tagging students as pass or fail
    Route::get('pashqtcur', ['as' => 'pashqtcur', 'uses' => 'ResultsController@pashqtcur' ]);

    // Excel download table from view
    Route::get('excel', 'ExcelController@getBladeExcel');

    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');
    Route::resource('classrooms', 'ClassroomController');
    Route::resource('course-schedule', 'CourseSchedController');
    Route::resource('schedules', 'ScheduleController');
    Route::resource('terms', 'TermController');
    Route::resource('courses', 'CourseController');
    Route::resource('organizations', 'OrgController');
    Route::resource('placement-schedule', 'PlacementScheduleController');
    Route::resource('preenrolment', 'PreenrolmentController');
    Route::resource('placement-form', 'PlacementFormController');
    Route::resource('teachers', 'TeachersController');
    Route::resource('rooms', 'RoomsController');
    Route::get('/placement-form-approved', ['as'=>'placement-form-approved','uses'=>'PlacementFormController@getApprovedPlacementForms']);
});
Route::group(['middleware' => ['auth','isAdmin'], 'prefix' => 'admin-stats'],function(){
    //admin routes
    Route::get('stats', function () { return view('admin.adminStats'); })->name('stats');
});

//middleware to prevent back button and access cache
Route::group(['middleware' => 'prevent-back-history'],function(){
    Auth::routes();
    Route::get('/','WelcomeController@index');
});

Route::middleware(['auth'])->group(function () {
    Route::middleware(['first-time-login'])->group(function () { //middleware to force user to change password 
        //home page routes
        Route::get('/home', 'HomeController@index')->name('home');
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
        Route::get('/verify/{student}/{temp_email}/{update_token}', ['as' => 'verify.updateProfileConfirmed', 'uses' => 'StudentController@updateProfileConfirmed' ]);
            });
    Route::get('password/expired', 'FirstTimeLoginController@expired')
        ->name('password.expired');
    Route::post('password/post_expired', 'FirstTimeLoginController@postExpired')
        ->name('password.post_expired');
});

//route for ajax jquery on organization
Route::get('org-select-ajax', ['as'=>'org-select-ajax','uses'=>'AjaxController@ajaxOrgSelect']);
// route for ajax jquery on certain dates
Route::get('get-date-ajax', ['as'=>'get-date-ajax','uses'=>'AjaxController@ajaxGetDate']);
// route for ajax jquery to compare organization in whatorg page
Route::post('org-compare-ajax', ['as'=>'org-compare-ajax','uses'=>'AjaxController@ajaxOrgCompare']);
// route for ajax jquery if forms have been cancelled or deleted
Route::get('is-cancelled-ajax', ['as'=>'is-cancelled-ajax','uses'=>'AjaxController@ajaxIsCancelled']);

Route::get('get-term-data-ajax', ['as'=>'get-term-data-ajax','uses'=>'AjaxController@ajaxGetTermData']);

//placement form routes
Route::get('/placementinfo', ['as'=>'placementinfo','uses'=>'PlacementFormController@getPlacementInfo']); // ->middleware('prevent-access-placement');
Route::post('/postplacementinfo-additional', ['as'=>'postplacementinfo-additional','uses'=>'PlacementFormController@postPlacementInfoAdditional']);
Route::post('check-placement-sched-ajax', ['as'=>'check-placement-sched-ajax','uses'=>'AjaxController@ajaxCheckPlacementSched']);
Route::post('/postplacementinfo', ['as'=>'postplacementinfo','uses'=>'PlacementFormController@postPlacementInfo']);
Route::post('/postSelfPayPlacementInfo', ['as'=>'postSelfPayPlacementInfo','uses'=>'PlacementFormController@postSelfPayPlacementInfo']);

//fee-paying form routes
Route::resource('selfpayform', 'SelfPayController', ['only' => ['create', 'store', 'edit']]);

//if already selected YES to continue course, go to these routes
Route::resource('noform', 'NoFormController', ['only' => ['create', 'store', 'edit']]);

//main UN staff form routes
Route::resource('myform', 'RepoController');
Route::post('check-placement-course-ajax', ['as'=>'check-placement-course-ajax','uses'=>'AjaxController@ajaxCheckPlacementCourse']);

//main controller used for ajax jquery on all forms - myform, noform, selfpayform
Route::post('select-ajax', ['as'=>'select-ajax','uses'=>'AjaxController@selectAjax']);
Route::post('select-ajax2', ['as'=>'select-ajax2','uses'=>'AjaxController@selectAjax2']);
Route::post('select-ajax-level-one', ['as'=>'select-ajax-level-one','uses'=>'AjaxController@selectAjaxLevelOne']);
Route::get('check-placement-form-ajax', ['as'=>'check-placement-form-ajax','uses'=>'AjaxController@ajaxCheckPlacementForm']);
Route::get('check-placement-entries-ajax', ['as'=>'check-placement-entries-ajax','uses'=>'AjaxController@ajaxCheckPlacementEntries']);
Route::get('check-enrolment-entries-ajax', ['as'=>'check-enrolment-entries-ajax','uses'=>'AjaxController@ajaxCheckEnrolmentEntries']);
Route::get('check-selfpay-entries-ajax', ['as'=>'check-selfpay-entries-ajax','uses'=>'AjaxController@ajaxCheckSelfpayEntries']);
Route::get('check-selfpay-placement-entries-ajax', ['as'=>'check-selfpay-placement-entries-ajax','uses'=>'AjaxController@ajaxCheckSelfpayPlacementEntries']);

// ajax use to get section number of cs_unique
Route::get('get-section-no-ajax', ['as'=>'get-section-no-ajax','uses'=>'AjaxController@ajaxGetSectionNo']);
Route::get('show-section-ajax', ['as'=>'show-section-ajax','uses'=>'AjaxController@ajaxShowSection']);

Route::group(['middleware' => 'open-close-approval-routes'],function(){
    //url routing for manager approval page
    Route::get('/approval/{staff}/{tecode}/{id}/{form}/{term}', ['as' => 'approval.getform', 'uses' => 'ApprovalController@getForm' ]);
    Route::put('/approval/user/{staff}/course/{tecode}/{formcount}/{term}', ['as' => 'approval.updateform', 'uses' => 'ApprovalController@updateForm' ])->where('tecode', '(.*)'); // where clause accepts routes with slashes

    //url routing for hr partner approval page
    Route::get('/approvalhr/{staff}/{tecode}/{id}/{form}/{term}', ['as' => 'approval.getform2hr','uses' => 'ApprovalController@getForm2hr' ]);
    Route::put('/approvalhr/user/{staff}/course/{tecode}/{formcount}/{term}', ['as' => 'approval.updateform2hr','uses' => 'ApprovalController@updateForm2hr' ])->where('tecode', '(.*)'); // where clause accepts routes with slashes

    //url routing for manager placement test approval page
    Route::get('/approval/{staff}/{lang}/placement/{id}/{form}/{term}', ['as' => 'approval.getplacementformdata', 'uses' => 'ApprovalController@getPlacementFormData' ]);
    Route::put('/approval/user/{staff}/lang/{lang}/{formcount}/{term}', ['as' => 'approval.updateplacementformdata', 'uses' => 'ApprovalController@updatePlacementFormData' ]);

    //url routing for hr partner placement test approval page
    Route::get('/approvalhr/{staff}/{lang}/placement/{id}/{form}/{term}', ['as' => 'approval.getplacementformdata2hr','uses' => 'ApprovalController@getPlacementFormData2hr' ]);
    Route::put('/approvalhr/user/{staff}/lang/{lang}/{formcount}/{term}', ['as' => 'approval.updateplacementformdata2hr','uses' => 'ApprovalController@updatePlacementFormData2hr' ]);
});


//public pages
Route::get('eform', function () { return view('confirmation_page_unog'); })->name('eform');
Route::get('eform2', function () { return view('confirmation_page_hr'); })->name('eform2');
Route::get('confirmationLinkUsed', function () { return view('confirmationLinkUsed'); })->name('confirmationLinkUsed');
Route::get('confirmationLinkExpired', function () { return view('confirmationLinkExpired'); })->name('confirmationLinkExpired');
Route::get('new_user_msg', function () { return view('new_user_msg'); })->name('new_user_msg');
Route::get('page_not_available', function () { return view('page_not_available'); })->name('page_not_available');
Route::get('thankyou', function () { return view('thankyou'); })->name('thankyou');
Route::get('thankyouSelfPay', function () { return view('thankyouSelfPay'); })->name('thankyouSelfPay');
Route::resource('newuser', 'NewUserController');
//Route::get('/', function () { return view('welcome'); });
Auth::routes();

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