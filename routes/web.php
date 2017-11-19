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
Route::resource('myform', 'RepoController');
Route::post('select-ajax', ['as'=>'select-ajax','uses'=>'RepoController@selectAjax']);
Route::post('select-ajax2', ['as'=>'select-ajax2','uses'=>'RepoController@selectAjax2']);
//Route::post('select-ajax', ['as'=>'select-ajax','uses'=>'HomeController@selectAjax']);
Route::resource('classrooms', 'ClassroomController');
Route::resource('schedules', 'ScheduleController');
Route::resource('terms', 'TermController');
Route::resource('courses', 'CourseController');
Route::resource('students', 'HomeController');
Route::get('eform', function () { return view('eform'); });
//Route::get('/', function () { return view('welcome'); });
if ( Auth::check())
{
    Route::get('/','LoginController@index');
}
else
{
    Route::get('/','WelcomeController@index');
}
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
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