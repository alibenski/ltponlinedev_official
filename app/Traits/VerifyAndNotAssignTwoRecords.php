<?php

namespace App\Traits;
use Illuminate\Support\Facades\Auth;

trait VerifyAndNotAssignTwoRecords
{
    public function verifyAndNotAssignRecords($assign_modal, $enrolment_to_be_copied, $comment)
    {
        if ($assign_modal == 1) {
            $inputFor2 = ['admin_eform_comment' => $comment, 'updated_by_admin' => 0, 'modified_by' => Auth::user()->id];
            $inputFor2 = array_filter($inputFor2, 'strlen');
    
            foreach ($enrolment_to_be_copied as $enrolment_info) {
                $enrolment_info->fill($inputFor2)->save();
            }
        }

        if ($assign_modal == 0) {
            $inputFor2 = ['teacher_comments' => $comment, 'updated_by_admin' => 0, 'modified_by' => Auth::user()->id];
            $inputFor2 = array_filter($inputFor2, 'strlen');
    
            foreach ($enrolment_to_be_copied as $enrolment_info) {
                $enrolment_info->fill($inputFor2)->save();
            }
        }

        return $enrolment_to_be_copied;
    }
}