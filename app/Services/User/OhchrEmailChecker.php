<?php

namespace App\Services\User;

use Session;

class OhchrEmailChecker
{
    public function ohchrEmailChecker($email_add)
    {
        strtolower($email_add);
        $check = str_contains($email_add, '@ohchr.org');
        if ($check) {
            return $check;
        }
    }
}