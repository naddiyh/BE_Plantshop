<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Options
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for Cross-Origin Resource Sharing
    | (CORS). This will determine which origins, methods, and headers are
    | allowed for your API routes.
    |
    */
    
    'paths' => ['api/*'],
    
    'allowed_methods' => ['*'], // Mengizinkan semua metode (GET, POST, dll)
    
    'allowed_origins' => ['*'], // Mengizinkan semua asal (origins)
    
    'allowed_origins_patterns' => [],
    
    'allowed_headers' => ['*'], // Mengizinkan semua header
    
    'exposed_headers' => [],
    
    'max_age' => 0,
    
    'supports_credentials' => true,
];
