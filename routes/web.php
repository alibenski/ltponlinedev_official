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
Route::group(['middleware' => 'prevent-back-history'],function(){
    Auth::routes();
    Route::get('/','WelcomeController@index');
});
Route::resource('noform', 'NoFormController', ['only' => ['create', 'store', 'edit']]);
Route::post('select-ajax3', ['as'=>'select-ajax3','uses'=>'NoFormController@selectAjax3']);
Route::post('select-ajax4', ['as'=>'select-ajax4','uses'=>'NoFormController@selectAjax4']);
//url routing for approval page
Route::get('/approval/{staff}/{tecode}', ['as' => 'approval.getform',       'uses' => 'ApprovalController@getForm' ]);
Route::put('/approval/user/{staff}/course/{tecode}',      ['as' => 'approval.updateform',     'uses' => 'ApprovalController@updateForm' ])->where('tecode', '(.*)'); // where clause accepts routes with slashes
Route::resource('myform', 'RepoController');
Route::post('select-ajax', ['as'=>'select-ajax','uses'=>'RepoController@selectAjax']);
Route::post('select-ajax2', ['as'=>'select-ajax2','uses'=>'RepoController@selectAjax2']);
//Route::post('select-ajax', ['as'=>'select-ajax','uses'=>'HomeController@selectAjax']);
Route::resource('classrooms', 'ClassroomController');
Route::resource('schedules', 'ScheduleController');
Route::resource('terms', 'TermController');
Route::resource('courses', 'CourseController');
Route::resource('students', 'HomeController');
Route::get('eform', function () { return view('confirmation_page_unog'); })->name('eform');
Route::get('eform2', function () { return view('confirmation_page_hr'); })->name('eform2');
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
if ( Auth::check())
{
    Route::get('/','LoginController@index');
}
else
{
    Route::get('/','WelcomeController@index');
}
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/submitted', ['as'=>'submmitted','uses'=>'HomeController@index2']);

// show routes in webpage
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
    });
//e-mail template preview on browser
//use Illuminate\Mail\Markdown;

//Route::get('mail-preview', function () {
//    $markdown = new Markdown(view(), config('mail.markdown'));

//    return $markdown->render('emails.approval');
//});