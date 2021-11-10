<?php

namespace App\Services\User;

class NgoUpdateField
{
    public function checkNgoValue($student, $request)
    {
        if ($request->organization === 'NGO') {
                $student->sddextr->ngo_name = $request->input('ngoName');
            } else {
                $student->sddextr->ngo_name = NULL;
            }
    }
}