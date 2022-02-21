<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Course;
use App\FocalPoints;
use App\Jobs\SendBroadcastJob;
use App\Jobs\SendGeneralEmailJob;
use App\Mail\sendBroadcastEnrolmentIsOpen;
use App\Mail\sendConvocation;
use App\Mail\sendGeneralEmail;
use App\Mail\sendReminderToCurrentStudents;
use App\PlacementForm;
use App\Preenrolment;
use App\Repo;
use App\Teachers;
use App\Term;
use App\Text;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Session;
use Illuminate\Support\Facades\Validator;

class SystemController extends Controller
{
    public function systemIndex(Request $request)
    {
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        $term = Term::where('Term_Code', Session::get('Term'))->first();
        $texts = Text::get();
        $onGoingTermObj = \App\Helpers\GlobalFunction::instance()->currentTermObject();
        $onGoingTerm = Term::where('Term_Code', $onGoingTermObj->Term_Code)->first();

        return view('system.system-index', compact('terms', 'term', 'texts', 'onGoingTerm'));
    }

    public function sendToFocalPoints(Request $request)
    {
        // query focal points
        $focalPoints = FocalPoints::select('email')
            ->groupBy('email')
            ->get()
            ->pluck('email');

        $chunkedFocalPoints = $focalPoints->chunk(40);
        foreach ($chunkedFocalPoints as $emailFocalPoints) {
            $this->sendGeneralEmailJob($emailFocalPoints);
        }

        $request->session()->flash('success', 'Email sent to ' . $focalPoints->count() . ' focal points.');
        return back();
    }

    public function sendToMissionOffices(Request $request)
    {
        // query mission office emails
        $missionOffices = DB::table('tblLTP_Mission_Offices')->select('email')
            ->groupBy('email')
            ->get()
            ->pluck('email');

        $chunkedMissionOffices = $missionOffices->chunk(40);
        foreach ($chunkedMissionOffices as $emailchunkedMissionOffices) {
            $this->sendGeneralEmailJob($emailchunkedMissionOffices);
        }

        $request->session()->flash('success', 'Email sent to mission offices.');
        return back();
    }

    public function sendGeneralEmailJob($unique_email_address)
    {
        $baseDelay = Carbon::now();

        $getDelay = cache('_jobs.' . SendGeneralEmailJob::class, $baseDelay);

        $setDelay = Carbon::parse(
            $getDelay
        )->addSeconds(60);

        // insert data to cache table
        cache([
            '_jobs.' . SendGeneralEmailJob::class => $setDelay
        ], 5);

        $job = (new SendGeneralEmailJob($unique_email_address))->delay($setDelay);
        dispatch($job);
    }

    public function sendGeneralEmail(Request $request)
    {
        // query students who have logged in
        $query_email_addresses = User::where('must_change_password', 0)
            ->where('mailing_list', 1)
            ->select('email')
            ->groupBy('email')
            ->get()
            ->pluck('email');

        $term = \App\Helpers\GlobalFunction::instance()->currentTermObject();
        if (!$term) {
            $request->session()->flash('warning', 'No emails sent! Create a valid term.');
            return redirect()->back();
        }

        $query_students_current_year = Repo::where('Term', $term->Term_Code)
            ->select('INDEXID')
            ->groupBy('INDEXID')
            ->with('users')
            ->get()
            ->pluck('users.email');

        $merge = $query_email_addresses->merge($query_students_current_year);
        $unique_email_address = $merge->unique();

        // dd($merge->unique());

        // $sddextr_email_address = 'allyson.frias@gmail.com';
        $unique_email_address_chunked = $unique_email_address->chunk(50);
        foreach ($unique_email_address_chunked as $unique_email_address_chunk) {
            $this->sendGeneralEmailJob($unique_email_address_chunk);
            // Mail::to($sddextr_email_address)->send(new sendGeneralEmail($sddextr_email_address));
        }

        $request->session()->flash('success', 'Email sent!');
        return back();
    }

    public function sendEmailToEnrolledStudentsOfSelectedTerm(Request $request)
    {
        $term = Session::get('Term');
        if (!$term) {
            $request->session()->flash('warning', 'No emails sent! Select a valid term.');
            return back();
        }

        $query_students_regular_enrolment = Preenrolment::where('Term', $term)
            ->where('overall_approval', 1)
            ->select('INDEXID')
            ->groupBy('INDEXID')
            ->with('users')
            ->get()
            ->pluck('users.email');

        $query_students_regular_placement = PlacementForm::where('Term', $term)
            ->where('overall_approval', 1)
            ->select('INDEXID')
            ->groupBy('INDEXID')
            ->with('users')
            ->get()
            ->pluck('users.email');

        $merge = $query_students_regular_enrolment->merge($query_students_regular_placement);
        $unique_email_address = $merge->unique();

        $countOfEmails = count($unique_email_address);
        // dd($term, $query_students_regular_enrolment, $query_students_regular_placement, $unique_email_address);

        // $sddextr_email_address = 'allyson.frias@gmail.com';
        $unique_email_address_chunked = $unique_email_address->chunk(50);
        foreach ($unique_email_address_chunked as $unique_email_address_chunk) {
            $this->sendGeneralEmailJob($unique_email_address_chunk);
        }

        $request->session()->flash('success', 'Email sent to ' . $countOfEmails . ' students!');
        return back();
    }

    public function sendGeneralEmailToConvokedStudentsOfSelectedTerm(Request $request)
    {
        $term = Session::get('Term');
        if (!$term) {
            $request->session()->flash('warning', 'No emails sent! Select a valid term.');
            return back();
        }
        $convocation_all = Repo::where('Term', Session::get('Term'))->get();
        // with('classrooms')->get()->pluck('classrooms.Code', 'CodeIndexIDClass');

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

        $countOfEmails = $convocation_diff3->count();
        // $sddextr_email_address = 'allyson.frias@gmail.com';
        $email_container = [];
        foreach ($convocation_diff3 as $value) {
            // Mail::to($value->users->email)->send(new sendGeneralEmail($value->users->email));
            $email_container[] = $value->users->email;
        }
        $unique_email_address_chunked = array_chunk($email_container, 50, true);
        foreach ($unique_email_address_chunked as $unique_email_address_chunk) {
            $this->sendGeneralEmailJob($unique_email_address_chunk);
        }

        $request->session()->flash('success', 'Email sent to ' . $countOfEmails . ' students!');
        return back();
    }

    /**
     * Send broadcast reminder email to all students who have logged in
     * Use during START of enrolment 
     * @param  Request $request 
     * @return HTML Closure           
     */
    public function sendBroadcastEnrolmentIsOpen(Request $request)
    {
        // query students who have logged in
        $query_email_addresses = User::where('must_change_password', 0)
            ->where('mailing_list', 1)
            ->select('email')
            ->groupBy('email')
            ->get()
            ->pluck('email');

        $term = \App\Helpers\GlobalFunction::instance()->currentTermObject();
        if (!$term) {
            $request->session()->flash('warning', 'No emails sent! Create a valid term.');
            return back();
        }

        $query_students_current_year = Repo::where('Term', $term->Term_Code)
            ->select('INDEXID')
            ->groupBy('INDEXID')
            ->with('users')
            ->get()
            ->pluck('users.email');

        $merge = $query_email_addresses->merge($query_students_current_year);
        $unique_email_address = $merge->unique();
        // $unique_email_address = $unique_email_address->toArray();

        // dd($merge->unique());

        // $sddextr_email_address_sample = ['annegretbrauss@web.de-','allyson.frias@gmail.com','kkepaladin@yahoo.com'];
        // $emailArrayIterator = new \ArrayIterator($sddextr_email_address_sample);
        // foreach (new \LimitIterator($emailArrayIterator, 1) as $email) {
        //     var_dump($email);
        // }

        // $emailArrayIterator = new \ArrayIterator($unique_email_address);
        $emailError = [];
        $validEmails = [];
        // foreach (new \LimitIterator($emailArrayIterator, 252) as $sddextr_email_address) {
        $start = microtime(true);
        foreach ($unique_email_address as $sddextr_email_address) {
            $my_data = ['email' => $sddextr_email_address,];
            $validator = Validator::make($my_data, [
                'email' => 'email',
            ]);
            if ($validator->fails()) {
                $emailError[] = $sddextr_email_address;
            }
            $validEmails[] = $sddextr_email_address;
        }
        $unique_email_address_valid = $validEmails;

        $sent = [];

        $filt = array_diff($unique_email_address_valid, $sent);
        // dd($unique_email_address_valid, $filt);
        $unique_email_address_valid = collect($filt);

        $unique_email_address_chunked = $unique_email_address_valid->chunk(50);
        foreach ($unique_email_address_chunked as $unique_email_address_chunk) {
            $this->sendBroadcastEmail($unique_email_address_chunk);
        }
        $time_elapsed_secs = microtime(true) - $start;
        // dd($time_elapsed_secs, $unique_email_address_chunked);
        $request->session()->flash('success', 'Broadcast email sent! Error sending to: ' . json_encode($emailError));
        return back();
    }

    /**
     * Send broadcast reminder email to all students EXCEPT students who have already submitted a form
     * @param  Request $request 
     * @return HTML Closure           
     */
    public function sendBroadcastReminder(Request $request)
    {
        // query students who have logged in
        $query_email_addresses = User::where('must_change_password', 0)
            ->where('mailing_list', 1)
            ->select('email')
            ->groupBy('email')
            ->get()
            ->pluck('email');

        $term = \App\Helpers\GlobalFunction::instance()->currentTermObject();
        if (!$term) {
            $request->session()->flash('warning', 'No emails sent! Create a valid term.');
            return back();
        }

        $selectedTerm = $request->session()->get('Term');
        $queryStudentsAlreadyEnrolled = Preenrolment::where('Term', $selectedTerm)
            ->select('INDEXID')
            ->groupBy('INDEXID')
            ->with('users')
            ->get()
            ->pluck('users.email');

        $queryStudentsAlreadyPlaced = PlacementForm::where('Term', $selectedTerm)
            ->select('INDEXID')
            ->groupBy('INDEXID')
            ->with('users')
            ->get()
            ->pluck('users.email');

        $mergeNotToBeEmailed =  $queryStudentsAlreadyEnrolled->merge($queryStudentsAlreadyPlaced);
        $uniqueEmailAddressNotEmailed = $mergeNotToBeEmailed->unique();

        $differenceInEmails = array_diff($query_email_addresses->toArray(), $uniqueEmailAddressNotEmailed->toArray()); // get difference
        $differenceInEmails = array_unique($differenceInEmails); // remove dupes

        $collectDifferenceEmails = collect($differenceInEmails);

        $query_students_current_year = Repo::where('Term', $term->Term_Code)
            ->select('INDEXID')
            ->groupBy('INDEXID')
            ->with('users')
            ->get()
            ->pluck('users.email');

        $differenceInEmails2 = array_diff($query_students_current_year->toArray(), $uniqueEmailAddressNotEmailed->toArray()); // get difference
        $differenceInEmails2 = array_unique($differenceInEmails2); // remove dupes

        $collectDifferenceEmails2 = collect($differenceInEmails2);

        $merge = $collectDifferenceEmails->merge($collectDifferenceEmails2);

        $unique_email_address = $merge->unique();

        // dd($uniqueEmailAddressNotEmailed, $collectDifferenceEmails, $unique_email_address);

        // $sddextr_email_address = 'allyson.frias@gmail.com';
        $emailError = [];
        $validEmails = [];
        foreach ($unique_email_address as $sddextr_email_address) {
            $my_data = ['email' => $sddextr_email_address,];
            $validator = Validator::make($my_data, [
                'email' => 'email',
            ]);
            if ($validator->fails()) {
                $emailError[] = $sddextr_email_address;
            }
            $validEmails[] = $sddextr_email_address;
        }
        $unique_email_address_valid = $validEmails;
        $unique_email_address_valid = collect($unique_email_address_valid);

        $unique_email_address_chunked = $unique_email_address_valid->chunk(50);
        foreach ($unique_email_address_chunked as $unique_email_address_chunk) {
            $this->sendBroadcastEmail($unique_email_address_chunk);
        }

        $request->session()->flash('success', 'Broadcast reminder email sent! Error sending to: ' . json_encode($emailError));
        return back();
    }


    /**
     * Sends a reminder email ONLY to students who are in a class in the current term but have yet to enrol for the next term
     * @param  Request $request 
     * @return HTML Closure 
     */
    public function sendReminderToCurrentStudents(Request $request)
    {
        $term = \App\Helpers\GlobalFunction::instance()->currentTermObject();
        $selectedTerm = $request->session()->get('Term');
        // query all students enrolled to current term
        $query_students_current_term = Repo::where('Term', $term->Term_Code)->get();

        $arr1 = [];
        $arr0 = [];
        foreach ($query_students_current_term as $key => $value) {
            $arr0[] = $value->INDEXID;

            $query_not_enrolled_stds = Preenrolment::where('INDEXID', $value->INDEXID)->where('Term', $selectedTerm)->get();
            foreach ($query_not_enrolled_stds as $key2 => $value2) {
                $arr1[] = $value2->INDEXID;
            }
        }

        $arr3 = [];
        foreach ($query_students_current_term as $key5 => $value5) {
            $query_not_enrolled_stds_pl = PlacementForm::where('INDEXID', $value5->INDEXID)->where('Term', $selectedTerm)->get();
            foreach ($query_not_enrolled_stds_pl as $key6 => $value6) {
                $arr3[] = $value6->INDEXID;
            }
        }

        $arr0 = array_unique($arr0); // remove dupes
        $arr1 = array_unique($arr1); // remove dupes
        $arr3 = array_unique($arr3); // remove dupes

        $difference = array_diff($arr0, $arr1); // get difference
        $difference = array_unique($difference); // remove dupes

        $diff = array_diff($difference, $arr3);
        $diff = array_unique($diff);

        $difftest = array_diff($arr1, $arr3);
        $difftest = array_unique($difftest);

        $emailError = [];
        $validEmails = [];
        foreach ($diff as $value3) {
            $query_email_addresses = User::where('indexno', $value3)->get(['email']);
            foreach ($query_email_addresses as $value4) {
                $sddextr_email_address = $value4->email;

                $my_data = ['email' => $sddextr_email_address,];
                $validator = Validator::make($my_data, [
                    'email' => 'email',
                ]);
                if ($validator->fails()) {
                    $emailError[] = $sddextr_email_address;
                }
                $validEmails[] = $sddextr_email_address;
            }
        }

        $unique_email_address_valid = $validEmails;
        $unique_email_address_valid = collect($unique_email_address_valid);

        $unique_email_address_chunked = $unique_email_address_valid->chunk(50);
        foreach ($unique_email_address_chunked as $unique_email_address_chunk) {
            $this->sendBroadcastEmail($unique_email_address_chunk);
        }

        $request->session()->flash('success', 'Reminder email sent to ' . count($validEmails) . ' students! Error sending to: ' . json_encode($emailError));
        return back();
    }

    public function sendBroadcastEmail($unique_email_address)
    {
        $baseDelay = Carbon::now();

        $getDelay = cache('_jobs.' . SendBroadcastJob::class, $baseDelay);

        $setDelay = Carbon::parse(
            $getDelay
        )->addSeconds(60);

        // insert data to cache table
        cache([
            '_jobs.' . SendBroadcastJob::class => $setDelay
        ], 5);

        $job = (new SendBroadcastJob($unique_email_address))->delay($setDelay);
        dispatch($job);
    }
}
