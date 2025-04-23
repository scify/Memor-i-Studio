<?php

return [
    'site_key' => env('RECAPTCHA_V3_SITE_KEY', ''),
    'secret_key' => env('RECAPTCHA_V3_SECRET_KEY', ''),
    'min_score' => 0.5,
    'action_name' => 'contact',
]; 