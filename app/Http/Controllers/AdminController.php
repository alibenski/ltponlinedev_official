<?php

namespace App\Http\Controllers;

use App\NewUser;
use App\Services\User\ExistingUserImport;
use App\Services\User\UserImport;
use App\Term;
use App\Torgan;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Preenrolment;


class AdminController extends Controller
{
    public function setSessionTerm(Request $request)
    {
        $new_user_count = NewUser::where('approved_account', 0)->count();
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        
        $request->session()->put('Term', $request->Term);

        // return view('admin.index',compact('terms'))->withNew_user_count($new_user_count);   
        return redirect()->back();   
    }
    
    public function previewCourse(Request $request)
    {
        $languages = DB::table('languages')->pluck("name", "code")->all();
        $org = Torgan::orderBy('Org Name', 'asc')->get(['Org Name', 'Org Full Name']);
        $terms = Term::orderBy('Term_Code', 'desc')->get();



        if (!Session::has('Term')) {
            $enrolment_forms = null;
            return view('preview-course')->withEnrolment_forms($enrolment_forms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
        }

        $enrolment_forms = new Preenrolment;
        // $currentQueries = \Request::query();
        $queries = [];

        $columns = [
            'L', 'DEPT', 'Te_Code'
        ];


        foreach ($columns as $column) {
            if (\Request::has($column)) {
                $enrolment_forms = $enrolment_forms->where($column, \Request::input($column));
                $queries[$column] = \Request::input($column);
            }

        }
        if (Session::has('Term')) {
            $enrolment_forms = $enrolment_forms->where('Term', Session::get('Term'));
            $queries['Term'] = Session::get('Term');
        }

        if (\Request::has('search')) {
            $name = \Request::input('search');
            $enrolment_forms = $enrolment_forms->with('users')
                ->whereHas('users', function ($q) use ($name) {
                    return $q->where('name', 'LIKE', '%' . $name . '%')->orWhere('email', 'LIKE', '%' . $name . '%');
                });
            $queries['search'] = \Request::input('search');
        }

        if (\Request::has('sort')) {
            $enrolment_forms = $enrolment_forms->orderBy('created_at', \Request::input('sort'));
            $queries['sort'] = \Request::input('sort');
        }

        // $allQueries = array_merge($queries, $currentQueries);
        $enrolment_forms = $enrolment_forms->orderBy('schedule_id', 'asc')->paginate(10)->appends($queries);
        return view('preview-course')->withEnrolment_forms($enrolment_forms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
    }

    public function adminIndex()
    {
        $new_user_count = NewUser::where('approved_account', 0)->count();
        $terms = Term::orderBy('Term_Code', 'desc')->get();       

        return view('admin.index',compact('terms'))->withNew_user_count($new_user_count);   
    }


    public function importUser() 
    {
        return view('admin.import-user');
    }

    public function importExistingUser()
    {
        return view('admin.import-existing-user');
    }

    public function handleImportUser(Request $request, UserImport $userImport) 
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }
                
        if ($request->hasFile('file')) {
             # code...
            $file = $request->file('file');
            $csvData = file_get_contents($file);
            
            // check utf8 BOM and remove BOM characters
            $bom = pack("CCC", 0xef, 0xbb, 0xbf);
                if (0 === strncmp($csvData, $bom, 3)) {
                    echo "BOM detected - file is UTF-8\n";
                    $csvData = substr($csvData, 3);
                }

            $rows = array_map("str_getcsv", explode("\n", $csvData));
            $header = array_shift($rows);
            // dd($rows);
            if (!$userImport->checkImportData($rows, $header)) {
                $request->session()->flash('error_rows', $userImport->getErrorRows());
                // $request->session()->flash('error_row_id', $userImport->getErrorRowId());
                // $request->session()->flash('valid_row_id', $userImport->getValidRowId());
                Session::flash('interdire-msg','Error in data. Correct and re-upload');
                return redirect()->back();
            }   

            $userImport->createUsers($header, $rows);
            
            session()->flash('success','Users imported');
            return redirect()->back(); 
        } else
        return 'no file';
    }

    public function handleImportExistingUser(Request $request, ExistingUserImport $userImport) 
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }
                
        if ($request->hasFile('file')) {
             # code...
            $file = $request->file('file');
            $csvData = file_get_contents($file);
            
            // check utf8 BOM and remove BOM characters
            $bom = pack("CCC", 0xef, 0xbb, 0xbf);
                if (0 === strncmp($csvData, $bom, 3)) {
                    echo "BOM detected - file is UTF-8\n";
                    $csvData = substr($csvData, 3);
                }

            $rows = array_map("str_getcsv", explode("\n", $csvData));
            $header = array_shift($rows);
            // dd($userImport->checkImportData($rows, $header));
            if (!$userImport->checkImportData($rows, $header)) {
                $request->session()->flash('error_rows', $userImport->getErrorRows());
                // $request->session()->flash('error_row_id', $userImport->getErrorRowId());
                // $request->session()->flash('valid_row_id', $userImport->getValidRowId());
                Session::flash('interdire-msg','Error in data. Correct and re-upload');
                return redirect()->back();
            }   

            $userImport->createUsers($header, $rows);
            
            session()->flash('success','Existing Users imported');
            return redirect()->back(); 
        } else
        return 'no file';
    }
}
