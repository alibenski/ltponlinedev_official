<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Torgan;
use App\FocalPoints;
use App\Language;
use App\Course;
use App\User;
use App\SDDEXTR;
use App\Repo;
use App\Preenrolment;
use App\Term;
use Session;
use Carbon\Carbon;
use DB;
use App\Mail\updateEmail;

class StudentController extends Controller
{
    /**
     * Call Middleware instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('prevent-back-history');
        $this->middleware('redirect-if-not-profile')->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_user = Auth::user()->indexno;
        //query last UN Language Course enrolled in the past based on PASHQ table
        $repos_lang = Repo::orderBy('Term', 'desc')->where('INDEXID', $current_user)->first();
        //query the current term based on year and Term_End column is greater than today's date
        //whereYear('Term_End', $now_year)  
                        //->first();
        $now_date = Carbon::now()->toDateString();
        $terms = Term::orderBy('Term_Code', 'desc')
                ->whereDate('Term_End', '>=', $now_date)
                ->get()->min();
        $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');
        //query submitted forms based from tblLTP_Enrolment table
        $forms_submitted = Preenrolment::distinct('Te_Code')
            ->where('INDEXID', '=', $current_user)
            ->where('Term', $next_term_code )->get();
        $next_term = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min();
        
        return view('students.index')->withRepos_lang($repos_lang)->withForms_submitted($forms_submitted)->withNext_term($next_term);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = User::find($id);
        
        return view('students.edit')->withStudent($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate data
        $this->validate($request, array(
                // 'title' => 'required|',
                // 'lastName' => 'required|string',
                // 'firstName' => 'required|string',
                'email' => 'required_without_all:title,lastName,firstName,org,contactNo,jobAppointment,gradeLevel|email',
                // 'org' => 'required|',
                // 'contactNo' => 'required|integer',
                // 'jobAppointment' => 'required|string',
                // 'gradeLevel' => 'required|string',

            ));        
        
        // Save the data to db
        $student = User::findOrFail($id);

        if (is_null($request->input('email'))) {
            $student->sddextr->FIRSTNAME = $request->input('firstName');
            $student->sddextr->save();

            // Set flash data with message
            $request->session()->flash('success', 'Update successful.');
        } else {
            $input = $request->except('email', '_token', '_method');
            $filter_input = array_filter($input, 'strlen');
            $sddextr_data = SDDEXTR::updateOrCreate($filter_input);
            $sddextr_data->save();
            dd($filter_input);

            $student->temp_email = $request->input('email');
            $student->update_token = Str::random(60);
            $student->save();         

            $this->sendUpdateEmail($student);

            // Set flash data with message
            $request->session()->flash('success', 'Update Confirmation Email sent to your email address.');
        }

        return redirect()->route('home');
    }

    public function sendUpdateEmail($student)
    {
        // handle invalid email by catching error of Mail method

        // send confirmation e-mail
        Mail::to($student['temp_email'])->send(new updateEmail($student));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
