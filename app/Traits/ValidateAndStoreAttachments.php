<?php

namespace App\Traits;

use App\AdditionalFile;
use App\ContractFile;
use App\File;
use App\Identity2File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait ValidateAndStoreAttachments
{
    public function validateAttachments($request)
    {
        if ($request->has('contractFile')) {
            // separated for optional validation in the future
            $this->validate($request, array(
                'identityfile' => 'required|mimes:pdf,doc,docx|max:8000',
                'identityfile2' => 'required|mimes:pdf,doc,docx|max:8000',
                'contractFile' => 'required|mimes:pdf,doc,docx|max:8000',
            ));
        }
    }

    public function storeFrontIDattachment($request)
    {
        $index_id = $request->input('index_id');
        $language_id = $request->input('L');
        $course_id = $request->input('course_id');
        $term_id = $request->input('term_id');

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
                'size' => $request->identityfile->getSize(),
                'path' => $filestore,
            ]);
            $attachment_identity_file->save();
        } else {
            $attachment_identity_file = (object) ['id' => null];
        }
    }

    public function storeOtherAttachments($ingredients, $request)
    {
        $index_id = $request->input('index_id');
        $language_id = $request->input('L');
        $course_id = $request->input('course_id');
        $term_id = $request->input('term_id');

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
                    'size' => $request->identityfile2->getSize(),
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
                    'size' => $request->contractFile->getSize(),
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
                    'size' => $request->addFile0->getSize(),
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
                    'size' => $request->addFile1->getSize(),
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
                    'size' => $request->addFile2->getSize(),
                    'path' => $filestore,
                ]);
                $attachment_add_file_2->save();
            }
        }
    }
}
