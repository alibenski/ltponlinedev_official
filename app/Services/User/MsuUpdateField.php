<?php

namespace App\Services\User;

class MsuUpdateField
{
    public function checkMsuValue($student, $request)
    {
        if ($request->organization === 'MSU') {
                $student->sddextr->country_mission = $request->input('countryMission');
            } else {
                $student->sddextr->country_mission = NULL;
            }
    }
}