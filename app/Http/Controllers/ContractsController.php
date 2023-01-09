<?php

namespace App\Http\Controllers;

use App\ContractFile;
use App\PlacementForm;
use App\Preenrolment;
use Illuminate\Http\Request;

class ContractsController extends Controller
{
    public function getContractFile(Request $request)
    {
        if ($request->formType == 1) {
            $formId = Preenrolment::orderBy('id', 'desc')->where('INDEXID', $request->indexId)->where('Te_Code', $request->teCode)->where('Term', $request->term)->first();
            $contractFile = ContractFile::where('user_id', $request->userId)->where('enrolment_id', $formId->id)->first();

            if (is_null($contractFile)) {
                $data = 'none';
            } else {
                $data = [
                    'userId' => $request->userId,
                    'teCode' => $request->teCode,
                    'term' => $request->term,
                    'path' => $contractFile->path
                ];
                if (!$contractFile->path) {
                    $data = 'none';
                }
            }

            return $data;
        }

        if ($request->formType == 0) {
            $formId = PlacementForm::orderBy('id', 'desc')->where('INDEXID', $request->indexId)->where('L', $request->L)->where('Term', $request->term)->first();
            $contractFile = ContractFile::where('user_id', $request->userId)->where('placement_id', $formId->id)->first();

            if (is_null($contractFile)) {
                $data = 'none';
            } else {
                $data = [
                    'userId' => $request->userId,
                    'L' => $request->L,
                    'term' => $request->term,
                    'path' => $contractFile->path
                ];
                if (!$contractFile->path) {
                    $data = 'none';
                }
            }

            return $data;
        }

        return "none";
    }
}
