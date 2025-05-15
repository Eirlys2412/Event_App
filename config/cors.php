<?php

return [
    'paths' => ['api/*', 'oauth/*', 'sanctum/csrf-cookie', 'storage/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => ['Content-Length', 'Content-Range'],
    'max_age' => 86400, // 24 giờ
    'supports_credentials' => true,
]; 