<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Session;
use Carbon\Carbon;
use DB;
use App\Services\User\UserImport;


class AdminController extends Controller
{
    public function importUser() 
    {
        return view('admin.import-user');
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
}
