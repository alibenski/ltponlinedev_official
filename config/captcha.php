<?php
// [\vendor\anhskohbo\no-captcha\src\config\captcha.php]
return [
    'secret' => env('NOCAPTCHA_SECRET'),
    'sitekey' => env('NOCAPTCHA_SITEKEY'),
    'options' => [
        'timeout' => 2.0,
    ],
];
