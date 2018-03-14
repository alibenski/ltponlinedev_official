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
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');
    Route::resource('classrooms', 'ClassroomController');
    Route::resource('course-schedule', 'CourseSchedController');
    Route::resource('schedules', 'ScheduleController');
    Route::resource('terms', 'TermController');
    Route::resource('courses', 'CourseController');
    Route::resource('organizations', 'OrgController');
});

//middleware to prevent back button and access cache
Route::group(['middleware' => 'prevent-back-history'],function(){
    Auth::routes();
    Route::get('/','WelcomeController@index');
});

//home page routes
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/whatorg', ['as'=>'whatorg','uses'=>'HomeController@whatorg'])->middleware('opencloseenrolment');
Route::post('/whatform', ['as'=>'whatform','uses'=>'HomeController@whatform'])->middleware('check-prev-url');
Route::get('/submitted', ['as'=>'submitted','uses'=>'HomeController@index2']);
Route::get('/history', ['as'=>'history','uses'=>'HomeController@history']);
Route::post('/showform', ['as'=>'submitted.show','uses'=>'HomeController@showMod']);
//Route::delete('/delete/user/{staff}/course/{tecode}', ['as' => 'submitted.destroy', 'uses' => 'HomeController@destroy'])->where('tecode', '(.*)');
Route::delete('/delete/user/{staff}/course/{tecode}', ['middleware' => 'limit-cancel','as' => 'submitted.destroy', 'uses' => 'HomeController@destroy'])->where('tecode', '(.*)');
//apply auth middleware only so students could edit their profile
Route::resource('students', 'StudentController');
Route::get('/verify/{student}/{temp_email}/{update_token}', ['as' => 'verify.updateProfileConfirmed', 'uses' => 'StudentController@updateProfileConfirmed' ]);
//route for ajax jquery on organization
Route::get('org-select-ajax', ['as'=>'org-select-ajax','uses'=>'AjaxController@ajaxOrgSelect']);
// route for ajax jquery on certain dates
Route::get('get-date-ajax', ['as'=>'get-date-ajax','uses'=>'AjaxController@ajaxGetDate']);

//fee-paying form routes
Route::resource('selfpayform', 'SelfPayController', ['only' => ['create', 'store', 'edit']]);

//if already selected YES to continue course, go to these routes
Route::resource('noform', 'NoFormController', ['only' => ['create', 'store', 'edit']]);

//main UN staff form routes
Route::resource('myform', 'RepoController');

//main controller used for ajax jquery on all forms - myform, noform, selfpayform
Route::post('select-ajax', ['as'=>'select-ajax','uses'=>'AjaxController@selectAjax']);
Route::post('select-ajax2', ['as'=>'select-ajax2','uses'=>'AjaxController@selectAjax2']);

//url routing for manager approval page
Route::get('/approval/{staff}/{tecode}', ['as' => 'approval.getform', 'uses' => 'ApprovalController@getForm' ]);
Route::put('/approval/user/{staff}/course/{tecode}', ['as' => 'approval.updateform', 'uses' => 'ApprovalController@updateForm' ])->where('tecode', '(.*)'); // where clause accepts routes with slashes

//url routing for hr partner approval page
Route::get('/approvalhr/{staff}/{tecode}', ['as' => 'approval.getform2hr','uses' => 'ApprovalController@getForm2hr' ]);
Route::put('/approvalhr/user/{staff}/course/{tecode}',      ['as' => 'approval.updateform2hr','uses' => 'ApprovalController@updateForm2hr' ])->where('tecode', '(.*)'); // where clause accepts routes with slashes

//public pages
Route::get('eform', function () { return view('confirmation_page_unog'); })->name('eform');
Route::get('eform2', function () { return view('confirmation_page_hr'); })->name('eform2');
Route::get('confirmationLinkUsed', function () { return view('confirmationLinkUsed'); })->name('confirmationLinkUsed');
Route::get('confirmationLinkExpired', function () { return view('confirmationLinkExpired'); })->name('confirmationLinkExpired');

//Route::get('/', function () { return view('welcome'); });
Auth::routes();
    // Authentication Routes...
//    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
//    Route::post('login', 'Auth\LoginController@login');
//    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    // Registration Routes...
//    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//    Route::post('register', 'Auth\RegisterController@register');
    // Password Reset Routes...
//    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
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