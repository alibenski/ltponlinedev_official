<?php

namespace App\Http\Controllers;

use App\AdditionalFile;
use App\AdminComment;
use App\AdminCommentPlacement;
use App\Classroom;
use App\ContractFile;
use App\Identity2File;
use App\Course;
use App\Day;
use App\File;
use App\Http\Controllers\PlacementFormController;
use App\Language;
use App\Mail\MailtoApprover;
use App\Mail\MailtoStudentSelfpay;
use App\Mail\MailtoStudentSelfpayPlacement;
use App\PlacementForm;
use App\Preenrolment;
use App\Repo;
use App\SDDEXTR;
use App\Schedule;
use App\Term;
use App\Torgan;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Session;

class SelfPayController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('prevent-back-history');
        $this->middleware('opencloseenrolment')->only(['create', 'store']);
        // $this->middleware('checksubmissionselfpay')->except(['index','update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $languages = DB::table('languages')->pluck("name", "code")->all();
        $org = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);
        // $terms = Term::orderBy('Term_Code', 'desc')->get();

        if (!Session::has('Term')) {
            $selfpayforms = null;
            return view('selfpayforms.index', compact('selfpayforms', 'languages', 'org'));
        }

        $selfpayforms = Preenrolment::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count')->where('is_self_pay_form', '1')->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count');
        // ->orderBy('created_at', 'asc')->get();
        // $selfpayforms = new Preenrolment;
        // $currentQueries = \Request::query();
        $queries = [];

        $columns = [
            'L', 'DEPT', 'Te_Code', 'overall_approval', 'selfpay_approval',
        ];

        foreach ($columns as $column) {
            if (\Request::filled($column)) {
                $selfpayforms = $selfpayforms->where($column, \Request::input($column));
                $queries[$column] = \Request::input($column);
            }
        }
        if (Session::has('Term')) {
            $selfpayforms = $selfpayforms->where('Term', Session::get('Term'));
            $queries['Term'] = Session::get('Term');
        }

        if (\Request::filled('search')) {
            $name = \Request::input('search');
            $selfpayforms = $selfpayforms->with('users')
                ->whereHas('users', function ($q) use ($name) {
                    return $q->where('name', 'LIKE', '%' . $name . '%')->orWhere('email', 'LIKE', '%' . $name . '%');
                });
            $queries['search'] = \Request::input('search');
        }

        if (\Request::filled('sort')) {
            $selfpayforms = $selfpayforms->orderBy('created_at', \Request::input('sort'));
            $queries['sort'] = \Request::input('sort');
        }

        $selfpayforms = $selfpayforms->paginate(20)->appends($queries);
        return view('selfpayforms.index', compact('selfpayforms', 'languages', 'org'));
    }

    public function storeBackIdContractAttachments($request, $index_id, $term_id, $language_id, $course_id, $data_id, $userId, $placement_form)
    {
        $form_type = 'placement_id';
        if ($placement_form == 0) {
            $form_type = 'enrolment_id';
        }
        // create contract and additional files id and save enrolment id(s)
        if ($request->hasFile('identityfile2')) {
            $request->file('identityfile2');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_back_id_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile2->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('identityfile2'), $time . '_back_id_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile2->extension());
            //Create new record in db table
            $attachment_identity_2_file = new Identity2File([
                'user_id' => $userId->id,
                'actor_id' => Auth::user()->id,
                $form_type => $data_id->id,
                'filename' => $filename,
                'size' => $request->identityfile2->getClientSize(),
                'path' => $filestore,
            ]);
            $attachment_identity_2_file->save();
        }
        if ($request->hasFile('contractFile')) {
            $request->file('contractFile');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_contract_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->contractFile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('contractFile'), $time . '_contract_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->contractFile->extension());
            //Create new record in db table
            $attachment_contract_file = new ContractFile([
                'user_id' => $userId->id,
                'actor_id' => Auth::user()->id,
                $form_type => $data_id->id,
                'filename' => $filename,
                'size' => $request->contractFile->getClientSize(),
                'path' => $filestore,
            ]);
            $attachment_contract_file->save();
        }
        if ($request->hasFile('addFile0')) {
            $request->file('addFile0');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_additional_file_0_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->addFile0->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('addFile0'), $time . '_additional_file_0_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->addFile0->extension());
            //Create new record in db table
            $attachment_add_file_0 = new AdditionalFile([
                'user_id' => $userId->id,
                'actor_id' => Auth::user()->id,
                $form_type => $data_id->id,
                'filename' => $filename,
                'size' => $request->addFile0->getClientSize(),
                'path' => $filestore,
            ]);
            $attachment_add_file_0->save();
        }
        if ($request->hasFile('addFile1')) {
            $request->file('addFile1');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_additional_file_1_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->addFile1->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('addFile1'), $time . '_additional_file_1_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->addFile1->extension());
            //Create new record in db table
            $attachment_add_file_1 = new AdditionalFile([
                'user_id' => $userId->id,
                'actor_id' => Auth::user()->id,
                $form_type => $data_id->id,
                'filename' => $filename,
                'size' => $request->addFile1->getClientSize(),
                'path' => $filestore,
            ]);
            $attachment_add_file_1->save();
        }
        if ($request->hasFile('addFile2')) {
            $request->file('addFile2');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_additional_file_2_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->addFile2->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('addFile2'), $time . '_additional_file_2_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->addFile2->extension());
            //Create new record in db table
            $attachment_add_file_2 = new AdditionalFile([
                'user_id' => $userId->id,
                'actor_id' => Auth::user()->id,
                $form_type => $data_id->id,
                'filename' => $filename,
                'size' => $request->addFile2->getClientSize(),
                'path' => $filestore,
            ]);
            $attachment_add_file_2->save();
        }
    }

    public function adminAddAttachmentsView($indexid, $lang, $tecode, $term, $eform)
    {
        $selfpayforms = Preenrolment::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count')
            ->where('INDEXID', $indexid)
            ->where('L', $lang)
            ->where('Te_Code', $tecode)
            ->where('Term', $term)
            ->where('eform_submit_count', $eform)
            ->where('is_self_pay_form', '1')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count')
            ->get();

        if (count($selfpayforms) < 1) {
            return redirect()->route('updateLinkExpired');
        }

        $selfpayforms_placement = PlacementForm::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('is_self_pay_form', '1')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->get();



        return view('selfpayforms.admin-add-attachments', compact('selfpayforms'));
    }

    public function adminAddAttachmentsStore(Request $request)
    {
        $this->validate($request, [
            'identityfile' => 'mimes:pdf,doc,docx|max:8000',
            'identityfile2' => 'mimes:pdf,doc,docx|max:8000',
            'payfile' => 'mimes:pdf,doc,docx|max:8000',
            'contractFile' => 'mimes:pdf,doc,docx|max:8000',
            'addFile0' => 'mimes:pdf,doc,docx|max:8000',
        ]);

        $index_id = $request->INDEXID;
        $term_id = $request->Term;
        $language_id = $request->L;
        $course_id = $request->Te_Code;
        $eform_submit_count = $request->eform_submit_count;
        $userId = User::where('indexno', $index_id)->select('id')->first();
        $placement_form = 0;

        $selfpayforms = Preenrolment::where('INDEXID', $index_id)
            ->where('L', $language_id)
            ->where('Te_Code', $course_id)
            ->where('Term', $term_id)
            ->where('eform_submit_count', $eform_submit_count)
            ->where('is_self_pay_form', '1')
            ->get();

        // save who modified the form
        foreach ($selfpayforms as $value) {
            $value->modified_by = Auth::user()->id;
            $value->UpdatedOn = Carbon::now();
            $value->save(['timestamps' => FALSE]);
        }

        //Store the attachments to storage path and save in db table
        if ($request->hasFile('identityfile')) {
            $request->file('identityfile');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('identityfile'), $time . '_id_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile->extension());

            $attachment_identity_file = File::find($request->identity_id);
            $attachment_identity_file->update([ //change to create new
                // 'user_id' => get the id of form owner
                // 'actor_id' => Auth::user()->id,
                'filename' => $filename,
                'size' => $request->identityfile->getClientSize(),
                'path' => $filestore,
            ]);
        }
        if ($request->hasFile('payfile')) {
            $request->file('payfile');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->payfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('payfile'), $time . '_payment_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->payfile->extension());

            $attachment_pay_file = File::find($request->payment_id);
            $attachment_pay_file->update([ //change to create new
                // 'user_id' => get the id of form owner
                // 'actor_id' => Auth::user()->id,
                'filename' => $filename,
                'size' => $request->payfile->getClientSize(),
                'path' => $filestore,
            ]);
        }

        foreach ($selfpayforms as $data_id) {
            $this->storeBackIdContractAttachments($request, $index_id, $term_id, $language_id, $course_id, $data_id, $userId, $placement_form);
        }

        $request->session()->flash('success', 'Files have successfully been uploaded.');
        return redirect(route('selfpayform.index'));
    }

    public function adminAddAttachmentsPlacementView($indexid, $lang, $term, $eform)
    {
        $selfpayforms_placement = PlacementForm::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count')
            ->where('INDEXID', $indexid)
            ->where('L', $lang)
            ->where('Term', $term)
            ->where('eform_submit_count', $eform)
            ->where('is_self_pay_form', '1')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count')
            ->get();

        if (count($selfpayforms_placement) < 1) {
            return redirect()->route('updateLinkExpired');
        }

        return view('selfpayforms.admin-add-attachments-placement', compact('selfpayforms_placement'));
    }

    public function adminAddAttachmentsPlacementStore(Request $request)
    {
        $this->validate($request, [
            'identityfile' => 'mimes:pdf,doc,docx|max:8000',
            'identityfile2' => 'mimes:pdf,doc,docx|max:8000',
            'payfile' => 'mimes:pdf,doc,docx|max:8000',
            'contractFile' => 'mimes:pdf,doc,docx|max:8000',
            'addFile0' => 'mimes:pdf,doc,docx|max:8000',
        ]);

        $index_id = $request->INDEXID;
        $term_id = $request->Term;
        $language_id = $request->L;
        $course_id = $request->Te_Code;
        $eform_submit_count = $request->eform_submit_count;
        $userId = User::where('indexno', $index_id)->select('id')->first();
        $placement_form = 1;

        $selfpayforms = PlacementForm::where('INDEXID', $index_id)
            ->where('L', $language_id)
            ->where('Term', $term_id)
            ->where('eform_submit_count', $eform_submit_count)
            ->where('is_self_pay_form', '1')
            ->get();

        // save who modified the form
        foreach ($selfpayforms as $value) {
            $value->modified_by = Auth::user()->id;
            $value->UpdatedOn = Carbon::now();
            $value->save(['timestamps' => FALSE]);
        }

        //Store the attachments to storage path and save in db table
        if ($request->hasFile('identityfile')) {
            $request->file('identityfile');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('identityfile'), $time . '_id_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile->extension());

            $attachment_identity_file = File::find($request->identity_id);
            $attachment_identity_file->update([ //change to create new
                // 'user_id' => get the id of form owner
                // 'actor_id' => Auth::user()->id,
                'filename' => $filename,
                'size' => $request->identityfile->getClientSize(),
                'path' => $filestore,
            ]);
        }
        if ($request->hasFile('payfile')) {
            $request->file('payfile');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->payfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('payfile'), $time . '_payment_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->payfile->extension());

            $attachment_pay_file = File::find($request->payment_id);
            $attachment_pay_file->update([ //change to create new
                // 'user_id' => get the id of form owner
                // 'actor_id' => Auth::user()->id,
                'filename' => $filename,
                'size' => $request->payfile->getClientSize(),
                'path' => $filestore,
            ]);
        }

        foreach ($selfpayforms as $data_id) {
            $this->storeBackIdContractAttachments($request, $index_id, $term_id, $language_id, $course_id, $data_id, $userId, $placement_form);
        }

        $request->session()->flash('success', 'Files have been successfully uploaded.');
        return redirect(route('index-placement-selfpay'));
    }


    public function addAttachmentsView($indexid, $lang, $tecode, $term, $date, $eform)
    {
        if (Auth::user()->indexno != $indexid) {
            abort('401');
        }

        $selfpayforms = Preenrolment::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count')
            ->where('INDEXID', $indexid)
            ->where('L', $lang)
            ->where('Te_Code', $tecode)
            ->where('Term', $term)
            ->where('UpdatedOn', $date)
            ->where('eform_submit_count', $eform)
            ->where('is_self_pay_form', '1')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count')
            ->get();

        if (count($selfpayforms) < 1) {
            return redirect()->route('updateLinkExpired');
        }

        $selfpayforms_placement = PlacementForm::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('is_self_pay_form', '1')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->get();



        return view('selfpayforms.add-attachments', compact('selfpayforms'));
    }

    public function addAttachmentsStore(Request $request)
    {
        $this->validate($request, [
            'identityfile' => 'mimes:pdf,doc,docx|max:8000',
            'identityfile2' => 'mimes:pdf,doc,docx|max:8000',
            'payfile' => 'mimes:pdf,doc,docx|max:8000',
            'contractFile' => 'mimes:pdf,doc,docx|max:8000',
            'addFile0' => 'mimes:pdf,doc,docx|max:8000',
        ]);

        $index_id = $request->INDEXID;
        $term_id = $request->Term;
        $language_id = $request->L;
        $course_id = $request->Te_Code;
        $eform_submit_count = $request->eform_submit_count;
        $userId = User::where('indexno', $index_id)->select('id')->first();
        $placement_form = 0;

        $selfpayforms = Preenrolment::where('INDEXID', $index_id)
            ->where('L', $language_id)
            ->where('Te_Code', $course_id)
            ->where('Term', $term_id)
            ->where('eform_submit_count', $eform_submit_count)
            ->where('is_self_pay_form', '1')
            ->get();

        // save who modified the form
        foreach ($selfpayforms as $value) {
            $value->modified_by = Auth::user()->id;
            $value->UpdatedOn = Carbon::now();
            $value->save(['timestamps' => FALSE]);
        }

        //Store the attachments to storage path and save in db table
        if ($request->hasFile('identityfile')) {
            $request->file('identityfile');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('identityfile'), $time . '_id_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile->extension());

            $attachment_identity_file = File::find($request->identity_id);
            $attachment_identity_file->update([ //change to create new
                // 'user_id' => get the id of form owner
                // 'actor_id' => Auth::user()->id,
                'filename' => $filename,
                'size' => $request->identityfile->getClientSize(),
                'path' => $filestore,
            ]);
        }
        if ($request->hasFile('payfile')) {
            $request->file('payfile');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_' .  $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->payfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('payfile'), $time . '_payment_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->payfile->extension());

            $attachment_pay_file = File::find($request->payment_id);
            $attachment_pay_file->update([ //change to create new
                // 'user_id' => get the id of form owner
                // 'actor_id' => Auth::user()->id,
                'filename' => $filename,
                'size' => $request->payfile->getClientSize(),
                'path' => $filestore,
            ]);
        }

        foreach ($selfpayforms as $data_id) {
            $this->storeBackIdContractAttachments($request, $index_id, $term_id, $language_id, $course_id, $data_id, $userId, $placement_form);
        }

        Mail::raw("Selfpay Student Attachment Update (Regular Form): " . Auth::user()->name . ' ( ' . $index_id . ' )', function ($message) {
            $message->from('clm_onlineregistration@unog.ch', 'CLM Online Registration Administrator');
            $message->to('clm_language@un.org')->subject('Notification: Selfpay Student Attachment Update (Regular Form)');
        });

        $request->session()->flash('success', 'Thank you. Files successfully uploaded.');
        return redirect()->route('home');
    }

    public function addAttachmentsPlacementView($indexid, $lang, $term, $date, $eform)
    {
        if (Auth::user()->indexno != $indexid) {
            abort('401');
        }

        $selfpayforms_placement = PlacementForm::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count')
            ->where('INDEXID', $indexid)
            ->where('L', $lang)
            ->where('Term', $term)
            ->where('UpdatedOn', $date)
            ->where('eform_submit_count', $eform)
            ->where('is_self_pay_form', '1')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count')
            ->get();

        if (count($selfpayforms_placement) < 1) {
            return redirect()->route('updateLinkExpired');
        }

        return view('selfpayforms.add-attachments-placement', compact('selfpayforms_placement'));
    }

    public function addAttachmentsPlacementStore(Request $request)
    {
        $this->validate($request, [
            'identityfile' => 'mimes:pdf,doc,docx|max:8000',
            'identityfile2' => 'mimes:pdf,doc,docx|max:8000',
            'payfile' => 'mimes:pdf,doc,docx|max:8000',
            'contractFile' => 'mimes:pdf,doc,docx|max:8000',
            'addFile0' => 'mimes:pdf,doc,docx|max:8000',
        ]);

        $index_id = $request->INDEXID;
        $term_id = $request->Term;
        $language_id = $request->L;
        $course_id = $request->Te_Code;
        $eform_submit_count = $request->eform_submit_count;
        $userId = User::where('indexno', $index_id)->select('id')->first();
        $placement_form = 1;

        $selfpayforms = PlacementForm::where('INDEXID', $index_id)
            ->where('L', $language_id)
            ->where('Term', $term_id)
            ->where('eform_submit_count', $eform_submit_count)
            ->where('is_self_pay_form', '1')
            ->get();

        // save who modified the form
        foreach ($selfpayforms as $value) {
            $value->modified_by = Auth::user()->id;
            $value->UpdatedOn = Carbon::now();
            $value->save(['timestamps' => FALSE]);
        }

        //Store the attachments to storage path and save in db table
        if ($request->hasFile('identityfile')) {
            $request->file('identityfile');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('identityfile'), $time . '_id_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile->extension());

            $attachment_identity_file = File::find($request->identity_id);
            $attachment_identity_file->update([ //change to create new
                // 'user_id' => get the id of form owner
                // 'actor_id' => Auth::user()->id,
                'filename' => $filename,
                'size' => $request->identityfile->getClientSize(),
                'path' => $filestore,
            ]);
        }
        if ($request->hasFile('payfile')) {
            $request->file('payfile');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->payfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('payfile'), $time . '_payment_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->payfile->extension());

            $attachment_pay_file = File::find($request->payment_id);
            $attachment_pay_file->update([ //change to create new
                // 'user_id' => get the id of form owner
                // 'actor_id' => Auth::user()->id,
                'filename' => $filename,
                'size' => $request->payfile->getClientSize(),
                'path' => $filestore,
            ]);
        }

        foreach ($selfpayforms as $data_id) {
            $this->storeBackIdContractAttachments($request, $index_id, $term_id, $language_id, $course_id, $data_id, $userId, $placement_form);
        }

        Mail::raw("Selfpay Student Attachment Update (Placement Form): " . Auth::user()->name . ' ( ' . $index_id . ' )', function ($message) {
            $message->from('clm_onlineregistration@unog.ch', 'CLM Online Registration Administrator');
            $message->to('clm_language@un.org')->subject('Notification: Selfpay Student Attachment Update (Placement Form)');
        });

        $request->session()->flash('success', 'Thank you. Files successfully uploaded.');
        return redirect()->route('home');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $indexid, $tecode, $term)
    {
        $selfpay_student = Preenrolment::select('id', 'INDEXID', 'L', 'Te_Code', 'Term', 'eform_submit_count', 'profile', 'DEPT', 'flexibleBtn', 'flexibleFormat', 'attachment_id', 'attachment_pay')->where('is_self_pay_form', '1')->where('INDEXID', $indexid)->where('Te_Code', $tecode)->where('Term', $term)->first();

        $show_sched_selfpay = Preenrolment::where('INDEXID', $indexid)->where('is_self_pay_form', '1')->where('Te_Code', $tecode)->where('Term', $term)->get();

        $show_admin_comments = Preenrolment::select('CodeIndexID', 'INDEXID', 'Te_Code', 'Term', 'profile', 'DEPT', 'flexibleBtn', 'flexibleFormat')->where('INDEXID', $indexid)->where('Te_Code', $tecode)->where('is_self_pay_form', '1')->where('Term', $term)->first()->adminComment;

        $backSideId = Identity2File::select('path')->orderBy('id', 'desc')->where('enrolment_id', $selfpay_student->id)->get();
        $contractFiles = ContractFile::select('path')->orderBy('id', 'desc')->where('enrolment_id', $selfpay_student->id)->get();
        $additionalFiles = AdditionalFile::select('path')->orderBy('id', 'desc')->where('enrolment_id', $selfpay_student->id)->get();

        return view('selfpayforms.edit', compact('selfpay_student', 'show_sched_selfpay', 'show_admin_comments', 'backSideId', 'contractFiles', 'additionalFiles'));
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
        $this->validate($request, array(
            'Term' => 'required|',
            'INDEXID' => 'required|',
            'Te_Code' => 'required|',
            // 'admin_comment_show' => 'required|',
            'submit-approval' => 'required|',
        ));

        $forms = Preenrolment::orderBy('Term', 'desc')
            ->where('INDEXID', $request->INDEXID)
            ->where('Term', $request->Term)
            ->where('Te_Code', $request->Te_Code)
            ->where('eform_submit_count', $request->eform_submit_count)
            ->where('is_self_pay_form', '1')
            ->get();

        foreach ($forms as $form) {
            $enrolment_record = Preenrolment::where('id', $form->id)->first();
            // $enrolment_record->Comments = $request->admin_comment_show;
            $enrolment_record->selfpay_approval = $request['submit-approval'];
            $enrolment_record->overall_approval = $request['submit-approval'];
            $enrolment_record->save();
        }

        $update_element = $enrolment_record->UpdatedOn->toDateTimeString(); // convert Carbon to string
        $request->request->add(['UpdatedOn' => $update_element]); //add to request

        // save comments in the comments table and associate it to the enrolment form
        foreach ($forms as $form) {
            $admin_comment = new AdminComment;
            $admin_comment->comments = $request->admin_comment_show;
            $admin_comment->CodeIndexID = $form->CodeIndexID;
            $admin_comment->user_id = Auth::user()->id;
            $admin_comment->save();
        }

        // get term values and convert to strings
        $term = $request->Term;
        $term_en = Term::where('Term_Code', $term)->first()->Term_Name;
        $term_fr = Term::where('Term_Code', $term)->first()->Term_Name_Fr;

        $term_season_en = Term::where('Term_Code', $term)->first()->Comments;
        $term_season_fr = Term::where('Term_Code', $term)->first()->Comments_fr;

        $term_date_time = Term::where('Term_Code', $term)->first()->Term_Begin;
        $term_year = new Carbon($term_date_time);
        $term_year = $term_year->year;

        $staff_email = User::where('indexno', $request->INDEXID)->first();
        Mail::to($staff_email)->send(new MailtoStudentSelfpay($request, $term_season_en, $term_year));

        // $request->session()->flash('success', 'Enrolment form status updated. Student has also been emailed about this.'); 
        return redirect(route('selfpayform.index'));
    }

    public function indexPlacementSelfPay(Request $request)
    {
        $languages = DB::table('languages')->pluck("name", "code")->all();
        $org = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);
        $terms = Term::orderBy('Term_Code', 'desc')->get();

        if (!Session::has('Term')) {
            $selfpayforms = null;
            return view('selfpayforms.index-placement-selfpay', compact('selfpayforms', 'languages', 'org', 'terms'));
        }

        $selfpayforms = PlacementForm::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count')->where('is_self_pay_form', '1')->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count');

        $queries = [];

        $columns = [
            'L', 'DEPT', 'overall_approval', 'selfpay_approval',
        ];


        foreach ($columns as $column) {
            if (\Request::filled($column)) {
                $selfpayforms = $selfpayforms->where($column, \Request::input($column));
                $queries[$column] = \Request::input($column);
            }
        }

        if (Session::has('Term')) {
            $selfpayforms = $selfpayforms->where('Term', Session::get('Term'));
            $queries['Term'] = Session::get('Term');
        }

        if (\Request::filled('search')) {
            $name = \Request::input('search');
            $selfpayforms = $selfpayforms->with('users')
                ->whereHas('users', function ($q) use ($name) {
                    return $q->where('name', 'LIKE', '%' . $name . '%')->orWhere('email', 'LIKE', '%' . $name . '%');
                });
            $queries['search'] = \Request::input('search');
        }

        if (\Request::filled('sort')) {
            $selfpayforms = $selfpayforms->orderBy('created_at', \Request::input('sort'));
            $queries['sort'] = \Request::input('sort');
        }


        $selfpayforms = $selfpayforms->paginate(20)->appends($queries);
        return view('selfpayforms.index-placement-selfpay', compact('selfpayforms', 'languages', 'org', 'terms'));
    }

    public function approvedPlacementSelfPay(Request $request)
    {
        $languages = DB::table('languages')->pluck("name", "code")->all();
        $org = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);
        $terms = Term::orderBy('Term_Code', 'desc')->get();

        if (is_null($request->Term)) {
            // $selfpayforms = null;
            $selfpayforms = PlacementForm::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')->where('Term', $request->session()->get('Term'))
                ->where('is_self_pay_form', '1')->where('selfpay_approval', '1')
                ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at');

            $selfpayforms = $selfpayforms->paginate(30);
            return view('selfpayforms.approvedSelfpayPlacementForms', compact('selfpayforms', 'languages', 'org', 'terms'));
        }

        $selfpayforms = PlacementForm::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('is_self_pay_form', '1')->where('selfpay_approval', '1')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at');

        $queries = [];

        $columns = [
            'L', 'DEPT', 'Term',
        ];


        foreach ($columns as $column) {
            if (\Request::filled($column)) {
                $selfpayforms = $selfpayforms->where($column, \Request::input($column));
                $queries[$column] = \Request::input($column);
            }
        }

        if (\Request::filled('sort')) {
            $selfpayforms = $selfpayforms->orderBy('created_at', \Request::input('sort'));
            $queries['sort'] = \Request::input('sort');
        }

        $selfpayforms = $selfpayforms->paginate(30)->appends($queries);
        return view('selfpayforms.approvedSelfpayPlacementForms', compact('selfpayforms', 'languages', 'org', 'terms'));
    }

    public function editPlacementSelfPay(Request $request, $indexid, $language, $term)
    {
        $selfpay_student = PlacementForm::where('INDEXID', $indexid)->where('is_self_pay_form', '1')->where('L', $language)->where('Term', $term)->first();

        $show_sched_selfpay = PlacementForm::where('INDEXID', $indexid)->where('is_self_pay_form', '1')->where('L', $language)->where('Term', $term)->get();

        $show_admin_comments = PlacementForm::where('INDEXID', $indexid)->where('is_self_pay_form', '1')->where('L', $language)->where('Term', $term)->first()->adminCommentPlacement;

        $backSideId = Identity2File::select('path')->orderBy('id', 'desc')->where('placement_id', $selfpay_student->id)->get();
        $contractFiles = ContractFile::select('path')->orderBy('id', 'desc')->where('placement_id', $selfpay_student->id)->get();
        $additionalFiles = AdditionalFile::select('path')->orderBy('id', 'desc')->where('placement_id', $selfpay_student->id)->get();

        return view('selfpayforms.edit-placement-selfpay', compact('selfpay_student', 'show_sched_selfpay', 'show_admin_comments', 'backSideId', 'contractFiles', 'additionalFiles'));
    }

    public function postPlacementSelfPay(Request $request, $id)
    {
        $this->validate($request, array(
            'Term' => 'required|',
            'INDEXID' => 'required|',
            'L' => 'required|',
            // 'admin_comment_show' => 'required|',
            'submit-approval' => 'required|',
        ));

        $forms = PlacementForm::orderBy('Term', 'desc')
            ->where('INDEXID', $request->INDEXID)
            ->where('Term', $request->Term)
            ->where('L', $request->L)
            ->where('eform_submit_count', $request->eform_submit_count)
            ->where('is_self_pay_form', '1')
            ->get();

        foreach ($forms as $form) {
            $enrolment_record = PlacementForm::where('id', $form->id)->first();
            // $enrolment_record->Comments = $request->admin_comment_show;
            $enrolment_record->selfpay_approval = $request['submit-approval'];
            $enrolment_record->overall_approval = $request['submit-approval'];
            $enrolment_record->save();
        }

        $update_element = $enrolment_record->UpdatedOn->toDateTimeString(); // convert Carbon to string
        $request->request->add(['UpdatedOn' => $update_element]); //add to request

        // save comments in the comments table and associate it to the enrolment form
        foreach ($forms as $form) {
            $admin_comment = new AdminCommentPlacement;
            $admin_comment->comments = $request->admin_comment_show;
            $admin_comment->placement_id = $form->id;
            $admin_comment->user_id = Auth::user()->id;
            $admin_comment->save();
        }

        // get term values and convert to strings
        $term = $request->Term;
        $term_en = Term::where('Term_Code', $term)->first()->Term_Name;
        $term_fr = Term::where('Term_Code', $term)->first()->Term_Name_Fr;

        $term_season_en = Term::where('Term_Code', $term)->first()->Comments;
        $term_season_fr = Term::where('Term_Code', $term)->first()->Comments_fr;

        $term_date_time = Term::where('Term_Code', $term)->first()->Term_Begin;
        $term_year = new Carbon($term_date_time);
        $term_year = $term_year->year;

        $staff_email = User::where('indexno', $request->INDEXID)->first();
        Mail::to($staff_email)
            ->send(new MailtoStudentSelfpayPlacement($request, $term_season_en, $term_year));
        // $request->session()->flash('success', 'Enrolment form status updated. Student has also been emailed about this.'); 
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $sess = $request->session()->get('_previous');
        if (is_null($sess)) {
            return redirect('home')->with('interdire-msg', 'Whoops! Looks like something went wrong... Please report the problem to clm_language@un.org');
        }
        $result = array();
        foreach ($sess as $val) {
            $result = $val;
        }
        // 'success' flash Session attribute comes from whatform() method @Homecontroller  
        // check if user did not directly access link   
        if ($request->session()->has('success') || $result == route('selfpayform.create')) {

            //make collection values available
            $courses = Course::all();
            //get values directly from 'languages' table
            $languages = DB::table('languages')->pluck("name", "code")->all();
            $days = Day::pluck("Week_Day_Name", "Week_Day_Name")->except('Sunday', 'Saturday')->all();
            //get current year and date
            $now_date = Carbon::now()->toDateString();
            $now_year = Carbon::now()->year;

            //query the current term based on year and Term_End column is greater than today's date
            //whereYear('Term_End', $now_year)  
            $terms = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
            // $terms = Term::orderBy('Term_Code', 'desc')
            //                 ->whereDate('Term_End', '>=', $now_date)
            //                 //->first();
            //                 ->get()->min();
            if (is_null($terms)) {
                $request->session()->flash('enrolment_closed', 'Enrolment Form error: Current Enrolment Model does not exist in the table. Please contact and report to the Language Secretariat.');
                return redirect()->route('whatorg');
            }
            //query the next term based Term_Begin column is greater than today's date and then get min
            $next_term = Term::orderBy('Term_Code', 'desc')
                ->where('Term_Code', '=', $terms->Term_Next)->get();
            // ->min();

            $prev_term = Term::orderBy('Term_Code', 'desc')
                ->where('Term_Code', $terms->Term_Prev)->get();

            //define user variable as User collection
            $user = Auth::user();
            //define user index number for query 
            $current_user = Auth::user()->indexno;
            //using DB method to query latest CodeIndexID of current_user
            $repos = Repo::orderBy('Term', 'desc')
                ->where('INDEXID', $current_user)->value('CodeIndexID');
            //not using DB method to get latest language course of current_user
            $student_last_term = Repo::orderBy('Term', 'desc')
                ->where('INDEXID', $current_user)->first(['Term']);
            if ($student_last_term == null) {
                $repos_lang = null;
                $org = Torgan::orderBy('Org name', 'asc')->get()->pluck('Org name', 'Org name');
                return view('form.myform3', compact('courses', 'languages', 'terms', 'next_term', 'prev_term', 'repos', 'repos_lang', 'user', 'org', 'days'));
            }

            $repos_lang = Repo::orderBy('Term', 'desc')->where('Term', $student_last_term->Term)
                ->where('INDEXID', $current_user)->get();
            $org = Torgan::orderBy('Org name', 'asc')->get()->pluck('Org name', 'Org name');

            return view('form.myform3', compact('courses', 'languages', 'terms', 'next_term', 'prev_term', 'repos', 'repos_lang', 'user', 'org', 'days'));
        } else {
            return redirect('home')->with('interdire-msg', 'You cannot go directly to that link. Click on "Register/Enrol Here" < ' . route('whatorg') . ' > from the Menu below and answer the mandatory question.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $index_id = $request->input('index_id');
        $language_id = $request->input('L');
        $course_id = $request->input('course_id');
        $term_id = $request->input('term_id');
        //$schedule_id is an array 
        $schedule_id = $request->input('schedule_id');
        $uniquecode = $request->input('CodeIndexID');
        $decision = $request->input('decision');
        $org = $request->input('org');
        $agreementBtn = $request->input('agreementBtn');
        $consentBtn = $request->input('consentBtn');
        $flexibleBtn = $request->input('flexibleBtn');
        $flexibleDay = $request->input('flexibleDay');
        $flexibleTime = $request->input('flexibleTime');
        $flexibleFormat = $request->input('flexibleFormat');
        $codex = [];
        //concatenate (implode) Code input before validation   
        if (!empty($schedule_id)) {
            //check if $code has no input
            if (empty($uniquecode)) {
                //loop based on $room_id count and store in $codex array
                for ($i = 0; $i < count($schedule_id); $i++) {
                    $codex[] = array($course_id, $schedule_id[$i], $term_id, $index_id);
                    //implode array elements and pass imploded string value to $codex array as element
                    $codex[$i] = implode('-', $codex[$i]);
                    //for each $codex array element stored, loop array merge method
                    //and output each array element to a string via $request->Code

                    foreach ($codex as $value) {
                        $request->merge(['CodeIndexID' => $value]);
                    }
                    //var_dump($request->CodeIndexID);
                    // the validation below fails when CodeIndexID is already taken AND 
                    // deleted_at column is NULL which means it has not been cancelled AND
                    // there is an existing self-pay form
                    $this->validate($request, array(
                        'CodeIndexID' => Rule::unique('tblLTP_Enrolment')->where(function ($query) use ($request) {
                            $uniqueCodex = $request->CodeIndexID;
                            $query->where('CodeIndexID', $uniqueCodex)
                                ->where('deleted_at', NULL)
                                ->where('is_self_pay_form', 1);
                        })
                    ));
                }
            }
        }
        // 1st part of validate other input fields 
        $this->validate($request, array(
            'payfile' => 'required|mimes:pdf,doc,docx|max:8000',
        ));

        if ($request->has('contractFile')) {
            // separated for optional validation in the future
            $this->validate($request, array(
                'identityfile' => 'required|mimes:pdf,doc,docx|max:8000',
                'identityfile2' => 'required|mimes:pdf,doc,docx|max:8000',
                'contractFile' => 'required|mimes:pdf,doc,docx|max:8000',
            ));
        }

        if ($request->placementDecisionB === '0') {
            $this->validate($request, array(
                'placementLang' => 'required|integer',
                'agreementBtn' => 'required|',
                'dayInput' => 'required|',
                'timeInput' => 'required|',
                'deliveryMode' => 'required|',
                'course_preference_comment' => 'required|',
            ));
        }
        // control the number of submitted enrolment forms
        $qryEformCount = Preenrolment::withTrashed()
            ->where('INDEXID', $index_id)
            ->where('Term', $term_id)
            ->orderBy('eform_submit_count', 'desc')->first();

        $eform_submit_count = 1;
        if (isset($qryEformCount->eform_submit_count)) {
            $eform_submit_count = $qryEformCount->eform_submit_count + 1;
        }

        // set default value of $form_counter to 1 and then add succeeding
        $lastValueCollection = Preenrolment::withTrashed()
            ->where('Te_Code', $course_id)
            ->where('INDEXID', $index_id)
            ->where('Term', $term_id)
            ->orderBy('form_counter', 'desc')->first();

        $form_counter = 1;
        if (isset($lastValueCollection->form_counter)) {
            $form_counter = $lastValueCollection->form_counter + 1;
        }

        //Store the attachments to storage path and save in db table
        if ($request->hasFile('identityfile')) {
            $request->file('identityfile');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_id_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('identityfile'), $time . '_id_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile->extension());
            //Create new record in db table
            $attachment_identity_file = new File([
                'user_id' => Auth::user()->id,
                'actor_id' => Auth::user()->id,
                'filename' => $filename,
                'size' => $request->identityfile->getClientSize(),
                'path' => $filestore,
            ]);
            $attachment_identity_file->save();
        } else {
            $attachment_identity_file = (object) ['id' => null];
        }

        if ($request->hasFile('payfile')) {
            $request->file('payfile');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_payment_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->payfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('payfile'), $time . '_payment_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->payfile->extension());
            //Create new record in db table
            $attachment_pay_file = new File([
                'user_id' => Auth::user()->id,
                'actor_id' => Auth::user()->id,
                'filename' => $filename,
                'size' => $request->payfile->getClientSize(),
                'path' => $filestore,
            ]);
            $attachment_pay_file->save();
        }

        // check if placement test form
        // if so, call method from PlacementFormController
        if ($request->placementDecisionB === '0') {
            app('App\Http\Controllers\PlacementFormController')->postSelfPayPlacementInfo($request, $attachment_pay_file, $attachment_identity_file);

            if ($request->is_self_pay_form == 1) {
                $request->session()->flash('success', 'Your Placement Test request has been submitted.');
                return redirect()->route('thankyouSelfPay');
            }

            $request->session()->flash('success', 'Your Placement Test request has been submitted.');
            return redirect()->route('thankyouPlacement');
        }

        // 2nd part of validate other input fields 
        $this->validate($request, array(
            'term_id' => 'required|',
            'schedule_id' => 'required|',
            'course_id' => 'required|',
            'L' => 'required|',
            'org' => 'required',
            'flexibleDay' => 'required',
            'flexibleTime' => 'required',
            'flexibleFormat' => 'required',
            'regular_enrol_comment' => 'required',
            'agreementBtn' => 'required|',
        ));

        if ($org === 'MSU') {
            $this->validate($request, array(
                'countryMission' => 'required|'
            ));
        }

        if ($org === 'NGO') {
            $this->validate($request, array(
                'ngoName' => 'required|'
            ));
        }

        //loop for storing Code value to database
        $ingredients = [];
        for ($i = 0; $i < count($schedule_id); $i++) {
            $ingredients[] = new  Preenrolment([
                'CodeIndexID' => $course_id . '-' . $schedule_id[$i] . '-' . $term_id . '-' . $index_id,
                'Code' => $course_id . '-' . $schedule_id[$i] . '-' . $term_id,
                'schedule_id' => $schedule_id[$i],
                'L' => $language_id,
                'profile' => $request->profile,
                'Te_Code' => $course_id,
                'Term' => $term_id,
                'INDEXID' => $index_id,
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" =>  \Carbon\Carbon::now(),
                'continue_bool' => $decision,
                'attachment_id' => $attachment_identity_file->id,
                'attachment_pay' => $attachment_pay_file->id,
                'is_self_pay_form' => 1,
                'eform_submit_count' => $eform_submit_count,
                'form_counter' => $form_counter,
                'DEPT' => $org,
                'country_mission' => $request->input('countryMission'),
                "ngo_name" => $request->input('ngoName'),
                'agreementBtn' => $agreementBtn,
                'consentBtn' => $consentBtn,
                'flexibleBtn' => $flexibleBtn,
                'flexibleDay' => $flexibleDay,
                'flexibleTime' => $flexibleTime,
                'flexibleFormat' => $flexibleFormat,
                'std_comments' => $request->regular_enrol_comment,
            ]);

            foreach ($ingredients as $data) {
                $data->save();
            }
        }

        foreach ($ingredients as $data_id) {
            // create contract and additional files id and save enrolment id(s)
            if ($request->hasFile('identityfile2')) {
                $request->file('identityfile2');
                $time = date("d-m-Y") . "-" . time();
                $filename = $time . '_back_id_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile2->extension();
                //Store attachment
                $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('identityfile2'), $time . '_back_id_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile2->extension());
                //Create new record in db table
                $attachment_identity_2_file = new Identity2File([
                    'user_id' => Auth::user()->id,
                    'actor_id' => Auth::user()->id,
                    'enrolment_id' => $data_id->id,
                    'filename' => $filename,
                    'size' => $request->identityfile2->getClientSize(),
                    'path' => $filestore,
                ]);
                $attachment_identity_2_file->save();
            }
            if ($request->hasFile('contractFile')) {
                $request->file('contractFile');
                $time = date("d-m-Y") . "-" . time();
                $filename = $time . '_contract_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->contractFile->extension();
                //Store attachment
                $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('contractFile'), $time . '_contract_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->contractFile->extension());
                //Create new record in db table
                $attachment_contract_file = new ContractFile([
                    'user_id' => Auth::user()->id,
                    'actor_id' => Auth::user()->id,
                    'enrolment_id' => $data_id->id,
                    'filename' => $filename,
                    'size' => $request->contractFile->getClientSize(),
                    'path' => $filestore,
                ]);
                $attachment_contract_file->save();
            }
            if ($request->hasFile('addFile0')) {
                $request->file('addFile0');
                $time = date("d-m-Y") . "-" . time();
                $filename = $time . '_additional_file_0_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->addFile0->extension();
                //Store attachment
                $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('addFile0'), $time . '_additional_file_0_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->addFile0->extension());
                //Create new record in db table
                $attachment_add_file_0 = new AdditionalFile([
                    'user_id' => Auth::user()->id,
                    'actor_id' => Auth::user()->id,
                    'enrolment_id' => $data_id->id,
                    'filename' => $filename,
                    'size' => $request->addFile0->getClientSize(),
                    'path' => $filestore,
                ]);
                $attachment_add_file_0->save();
            }
            if ($request->hasFile('addFile1')) {
                $request->file('addFile1');
                $time = date("d-m-Y") . "-" . time();
                $filename = $time . '_additional_file_1_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->addFile1->extension();
                //Store attachment
                $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('addFile1'), $time . '_additional_file_1_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->addFile1->extension());
                //Create new record in db table
                $attachment_add_file_1 = new AdditionalFile([
                    'user_id' => Auth::user()->id,
                    'actor_id' => Auth::user()->id,
                    'enrolment_id' => $data_id->id,
                    'filename' => $filename,
                    'size' => $request->addFile1->getClientSize(),
                    'path' => $filestore,
                ]);
                $attachment_add_file_1->save();
            }
            if ($request->hasFile('addFile2')) {
                $request->file('addFile2');
                $time = date("d-m-Y") . "-" . time();
                $filename = $time . '_additional_file_2_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->addFile2->extension();
                //Store attachment
                $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('addFile2'), $time . '_additional_file_2_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->addFile2->extension());
                //Create new record in db table
                $attachment_add_file_2 = new AdditionalFile([
                    'user_id' => Auth::user()->id,
                    'actor_id' => Auth::user()->id,
                    'enrolment_id' => $data_id->id,
                    'filename' => $filename,
                    'size' => $request->addFile2->getClientSize(),
                    'path' => $filestore,
                ]);
                $attachment_add_file_2->save();
            }
        }

        //execute Mail class before redirect         
        $mgr_email = $request->mgr_email;
        $staff = Auth::user();
        $current_user = Auth::user()->indexno;
        $now_date = Carbon::now()->toDateString();
        $terms = Term::orderBy('Term_Code', 'desc')
            ->whereDate('Term_End', '>=', $now_date)
            ->get()->min();
        $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');
        $course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')
            ->where('INDEXID', $current_user)
            ->value('Te_Code');
        //query from Preenrolment table the needed information data to include in email
        $input_course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')
            ->where('INDEXID', $current_user)
            ->first();
        $input_schedules = Preenrolment::orderBy('Term', 'desc')
            ->where('INDEXID', $current_user)
            ->where('Term', $next_term_code)
            ->where('Te_Code', $course)
            ->where('form_counter', $form_counter)
            ->get();

        // email confirmation message to student enrolment form has been received 
        // Mail::to($mgr_email)->send(new MailtoApprover($input_course, $input_schedules, $staff));

        $request->session()->flash('success', 'Thank you. The enrolment form has been submitted to the Language Secretariat for processing.');

        return redirect()->route('thankyouSelfPay');
    }

    public function waitlistedAndValidCancelledFormsView(Request $request)
    {
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        return view('selfpayforms.waitlisted-and-valid-cancelled-forms', compact('terms'));
    }
    public function waitlistedAndValidCancelledForms(Request $request)
    {
        $term = $request->term;
        $cancelDateLimit = Term::orderBy('Term_Code', 'desc')->where('Term_Code', $term)->first()->Cancel_Date_Limit;

        // valid cases of waitlisted selfpaying students from PASH table
        $pashRecordsWaitlistedQuery = Repo::orderBy('id', 'desc')->where('Term', $term)
            ->where('is_self_pay_form', 1)->whereHas('classrooms', function ($query) {
                $query->whereNull('Tch_ID')
                    ->orWhere('Tch_ID', '=', 'TBD');
            })->with('users')->with('languages')->with('courses')->with('classrooms.teachers');

        $pashRecordsWaitlisted = $pashRecordsWaitlistedQuery->get();

        // valid cases of valid cancelled selfpaying students from PASH table
        $pashRecordValidCancelledQuery = Repo::onlyTrashed()->orderBy('id', 'desc')->where('Term', $term)->where('is_self_pay_form', 1)->where('deleted_at', '<', $cancelDateLimit)->with('users')->with('languages')->with('courses')->with('classrooms.teachers');

        $pashRecordValidCancelled = $pashRecordValidCancelledQuery->get();

        $mergedIndex = $pashRecordsWaitlisted->merge($pashRecordValidCancelled);

        $mergedArrayIndex = [];
        foreach ($mergedIndex as $value) {
            $mergedArrayIndex[] = $value->INDEXID;
        }

        // valid cases of cancelled forms from selfpaying students from Enrolment table
        $regularRecords = Preenrolment::onlyTrashed()
            // ->whereNotIn('INDEXID', $mergedArrayIndex)
            ->where('Term', $term)->where('is_self_pay_form', 1)->where('deleted_at', '<', $cancelDateLimit)->with('users')->with('languages')->with('courses')->get();

        // valid cases of cancelled forms from selfpaying students from Placement table
        $placementRecords = PlacementForm::onlyTrashed()
            // ->whereNotIn('INDEXID', $mergedArrayIndex)
            ->where('Term', $term)->where('is_self_pay_form', 1)->where('deleted_at', '<', $cancelDateLimit)->with('users')->with('languages')->with('courses')->get();

        $data = [
            'pashRecordsWaitlisted' => $pashRecordsWaitlisted,
            'pashRecordValidCancelled' => $pashRecordValidCancelled,
            'regularRecords' => $regularRecords,
            'placementRecords' => $placementRecords,
        ];

        // $data = $mergedArrayIndex;
        return response()->json($data);
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
