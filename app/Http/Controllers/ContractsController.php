<?php

namespace App\Http\Controllers;

use App\ContractFile;
use App\Preenrolment;
use Illuminate\Http\Request;

class ContractsController extends Controller
{
    public function getContractFile(Request $request)
    {
        if ($request->formType == 1) {
            $formId = Preenrolment::orderBy('id', 'desc')->where('INDEXID', $request->indexId)->where('Te_Code', $request->teCode)->where('Term', $request->term)->first();
            $contractFile = ContractFile::where('user_id', $request->userId)->where('enrolment_id', $formId->id)->first();

            $data = [
                'userId' => $request->userId,
                'teCode' => $request->teCode,
                'term' => $request->term,
                'path' => $contractFile->path
            ];
            if (!$contractFile->path) {
                $data = 'none';
            }

            return $data;
        }
    }
}
