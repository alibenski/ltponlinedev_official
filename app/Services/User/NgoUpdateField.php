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

    public function checkNgoValueNewUser($newUser, $request)
    {
        if ($request->org === 'NGO') {
                $newUser->ngo_name = $request->ngoName;
            } else {
                $newUser->ngo_name = NULL;
            }
    }
}