<?php

namespace App\Http\Controllers;

use App\NewUser;
use App\Preenrolment;
use App\Preview;
use App\Repo;
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


class AdminController extends Controller
{
    public function moveToPash()
    {
        $results = \DB::select( "INSERT into LTP_PASHQTcur (INDEXID,CodeIndexIDClass,CodeClass,CodeIndexID,Code,schedule_id,Te_Code,L,flexibleBtn,convocation_email_sent,form_counter,Term,DEPT,PS,created_at,UpdatedOn,deleted_at,EMAIL,Comments) SELECT INDEXID,CodeIndexIDClass,CodeClass,CodeIndexID,Code,schedule_id,Te_Code,L,flexibleBtn,convocation_email_sent,form_counter,Term,DEPT,PS,created_at,UpdatedOn,deleted_at,EMAIL,Comments FROM tblLTP_preview" );
    }

    public function setSessionTerm(Request $request)
    {
        $new_user_count = NewUser::where('approved_account', 0)->count();
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        
        $request->session()->put('Term', $request->Term);

        // return view('admin.index',compact('terms'))->withNew_user_count($new_user_count);   
        return redirect()->back();   
    }

    public function adminIndex()
    {
        $new_user_count = NewUser::where('approved_account', 0)->count();
        $terms = Term::orderBy('Term_Code', 'desc')->get();       
        $cancelled_convocations = Repo::onlyTrashed()->where('Term', Session::get('Term'))->count();

        return view('admin.index',compact('terms','cancelled_convocations','new_user_count'));   
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
