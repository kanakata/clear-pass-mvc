<?php
return [
    'app_name'         => 'AgriHotel Connect',
    'debug'            => true,
    'timezone'         => 'Africa/Nairobi',
    'session_name'     => 'agrihotel_sess',
    'session_lifetime' => 7200,
    'cache_ttl'        => 300,
    'upload_max'       => 5242880,
    'allowed_img'      => ['image/jpeg', 'image/png', 'image/webp'],
    'csrf_token_length' => 64,
    'rate_limit'       => [
        'login'    => ['attempts' => 5, 'window' => 900],
        'register' => ['attempts' => 3, 'window' => 3600],
        'api'      => ['attempts' => 60, 'window' => 60],
    ],
    'roles' => ['admin', 'hotel', 'farmer'],
];
