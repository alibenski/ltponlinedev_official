<?php

namespace App\Services\User;

class MsuUpdateField
{
    public function checkMsuValue($student, $request)
    {
        if ($request->organization === 'MSU') {
                $student->sddextr->msu_country_id = $request->input('countryMission');
            } else {
                $student->sddextr->msu_country_id = NULL;
            }
    }
}