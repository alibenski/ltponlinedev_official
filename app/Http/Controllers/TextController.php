<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Course;
use App\Repo;
use App\Teachers;
use App\Term;
use App\Text;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class TextController extends Controller
{
    public function viewCustomEmailWaitlistText(Request $request)
    {
        # code...
    }

    public function viewDefaultEmailWaitlistText(Request $request)
    {
        if (Session::has('Term')) {
            $term = Term::where('Term_Code', Session::get('Term'))->first();
            $firstDayMonth = date('d F', strtotime($term->Term_Begin));
            $lastDayMonth = Carbon::parse($term->Term_Begin)->addDays(13)->format('d F Y');

            return view('texts.view-default-email-waitlist-text', compact('term', 'firstDayMonth', 'lastDayMonth'));
        }

        return "Nothing to show. No term selected.";
    }

    public function viewGeneralEmailText($id)
    {
        $text = Text::find($id);
        return view('texts.view-general-email-text', compact('text'));
    }

    public function viewEnrolmentIsOpenText($id)
    {
        $text = Text::find($id);
        return view('texts.view-enrolment-is-open-text', compact('text'));
    }

    public function editEnrolmentIsOpenText($id)
    {
        $text = Text::find($id);
        return view('texts.edit-enrolment-is-open-text', compact('text'));
    }

    public function storeEnrolmentIsOpenText(Request $request, $id)
    {
        $text = Text::find($id);

        if (!is_null($request->subject)) {
            $text->subject = $request->subject;
        }
        if (!is_null($request->textValue)) {
            $text->text = $request->textValue;
        }

        $text->save();

        if ($id == 1) {
            return redirect(route('view-enrolment-is-open-text', ['id' => $id]));
        }

        if ($id == 3) {
            $data = "Custom Waitlist Text Saved";
            return response()->json($data);
        }

        return redirect(route('view-general-email-text', ['id' => $id]));
    }

    public function viewConvocationEmailText()
    {
        if (Session::has('Term')) {
            $convocation_all = Repo::where('Term', Session::get('Term'))->get();

            // query students who will be put in waitlist
            $convocation_waitlist = Repo::where('Term', Session::get('Term'))->whereHas('classrooms', function ($query) {
                $query->whereNull('Tch_ID')
                    ->orWhere('Tch_ID', '=', 'TBD');
            })
                ->get();

            // query students who will receive convocation
            $convocation = Repo::where('Term', Session::get('Term'))->whereHas('classrooms', function ($query) {
                $query->whereNotNull('Tch_ID')
                    ->where('Tch_ID', '!=', 'TBD');
            })
                // ->where('Te_Code','!=','F3R2')
                ->get();


            $convocation_diff = $convocation_all->diff($convocation);
            $convocation_diff2 = $convocation_waitlist->diff($convocation_diff);
            $convocation_diff3 = $convocation->diff($convocation_waitlist); // send email convocation to this collection

            // $cours3 = Preview::where('Te_Code','=','F3R2')->get();

            // dd($cours3,$convocation_all, $convocation_waitlist, $convocation, $convocation_diff,$convocation_diff2,$convocation_diff3);

            $convocation_diff3 = $convocation_diff3->where('convocation_email_sent', null)->first();
            // $convocation_diff3 = $convocation_diff3->where('INDEXID', '17942');

            if (!$convocation_diff3) {

                // get term values
                $term = Session::get('Term');
                // get term values and convert to strings
                $term_en = Term::where('Term_Code', $term)->first()->Term_Name;
                $term_fr = Term::where('Term_Code', $term)->first()->Term_Name_Fr;

                $term_season_en = Term::where('Term_Code', $term)->first()->Comments;
                $term_season_fr = Term::where('Term_Code', $term)->first()->Comments_fr;

                $term_date_time = Term::where('Term_Code', $term)->first()->Term_Begin;
                $term_year = new Carbon($term_date_time);
                $term_year = $term_year->year;

                // get cancel date limit
                $queryCancelDateLimit = Term::where('Term_Code', $term)->first()->Cancel_Date_Limit;
                $cancel_date_limit = new Carbon($queryCancelDateLimit);
                $cancel_date_limit->subDay();

                $termCancelMonth = date('F', strtotime($cancel_date_limit));
                $termCancelDate = date('d', strtotime($cancel_date_limit));
                $termCancelYear = date('Y', strtotime($cancel_date_limit));

                // cancel limit date convert to string
                $cancel_date_limit_string = date('d F Y', strtotime($cancel_date_limit));

                // translate 
                $termCancelMonthFr = __('months.' . $termCancelMonth, [], 'fr');
                $cancel_date_limit_string_fr = $termCancelDate . ' ' . $termCancelMonthFr . ' ' . $termCancelYear;

                $course_name_en = '(course name in english)';
                $course_name_fr = '(course name in french)';

                $schedule = 'Monday Wednesday  : 08:00am - 10:00am';
                // $room = $value->CodeClass; 
                // get schedule and room details from classroom table
                $classrooms = Classroom::where('Code', 'F1R1-15-194-1')->get();

                $teacher = '(teacher name here)';
                $teacher_email = 'teacher email here';

                $staff_name = '(student name here)';
                $staff_email = '(student email here)';

                return view('texts.view-convocation-email-text', compact('staff_name', 'course_name_en', 'course_name_fr', 'classrooms', 'teacher', 'teacher_email', 'term_en', 'term_fr', 'schedule', 'term_season_en', 'term_season_fr', 'term_year', 'cancel_date_limit_string', 'cancel_date_limit_string_fr'));
            }

            $value = $convocation_diff3;

            $course_name = Course::where('Te_Code_New', $value->Te_Code)->first();
            $course_name_en = $course_name->EDescription;
            $course_name_fr = $course_name->FDescription;

            $schedule = $value->schedules->name;
            // $room = $value->CodeClass; 
            // get schedule and room details from classroom table
            $classrooms = Classroom::where('Code', $value->CodeClass)->get();


            $teacher_id = $value->classrooms->Tch_ID;
            $teacher = Teachers::where('Tch_ID', $teacher_id)->first()->Tch_Name;
            $teacher_email = Teachers::where('Tch_ID', $teacher_id)->first()->email;

            // get term values
            $term = $value->Term;
            // get term values and convert to strings
            $term_en = Term::where('Term_Code', $term)->first()->Term_Name;
            $term_fr = Term::where('Term_Code', $term)->first()->Term_Name_Fr;

            $term_season_en = Term::where('Term_Code', $term)->first()->Comments;
            $term_season_fr = Term::where('Term_Code', $term)->first()->Comments_fr;

            $term_date_time = Term::where('Term_Code', $term)->first()->Term_Begin;
            $term_year = new Carbon($term_date_time);
            $term_year = $term_year->year;

            // get cancel date limit
            $queryCancelDateLimit = Term::where('Term_Code', $term)->first()->Cancel_Date_Limit;
            $cancel_date_limit = new Carbon($queryCancelDateLimit);
            $cancel_date_limit->subDay();

            $termCancelMonth = date('F', strtotime($cancel_date_limit));
            $termCancelDate = date('d', strtotime($cancel_date_limit));
            $termCancelYear = date('Y', strtotime($cancel_date_limit));

            // cancel limit date convert to string
            $cancel_date_limit_string = date('d F Y', strtotime($cancel_date_limit));

            // translate 
            $termCancelMonthFr = __('months.' . $termCancelMonth, [], 'fr');
            $cancel_date_limit_string_fr = $termCancelDate . ' ' . $termCancelMonthFr . ' ' . $termCancelYear;

            $staff_name = $value->users->name;
            $staff_email = $value->users->email;

            return view('texts.view-convocation-email-text', compact('staff_name', 'course_name_en', 'course_name_fr', 'classrooms', 'teacher', 'teacher_email', 'term_en', 'term_fr', 'schedule', 'term_season_en', 'term_season_fr', 'term_year', 'cancel_date_limit_string', 'cancel_date_limit_string_fr'));
        }
        
        return redirect()->route('admin_dashboard');
    }
}
