<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Drive Integration Settings
    |--------------------------------------------------------------------------
    |
    | Mendukung 2 mode autentikasi:
    | 1. OAuth 2.0 Web Authorization (Disarankan untuk Drive Saya / Kuota 5 TB).
    | 2. Google Cloud Service Account (Untuk Shared Drive / Drive Bersama).
    |
    */

    'folder_id' => env('GOOGLE_DRIVE_FOLDER_ID', ''),

    // OAuth 2.0 Web Client (Untuk upload langsung ke Drive Saya berkuota 5 TB)
    'client_id' => env('GOOGLE_DRIVE_CLIENT_ID', ''),
    'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET', ''),
    'redirect_uri' => env('GOOGLE_DRIVE_REDIRECT_URI', ''),
    'oauth_path' => storage_path('app/google-drive-oauth.json'),

    // Service Account (Untuk Google Workspace Shared Drive)
    'credentials_path' => env('GOOGLE_DRIVE_CREDENTIALS_PATH', storage_path('app/google-drive-credentials.json')),
    'client_email' => env('GOOGLE_DRIVE_CLIENT_EMAIL', ''),
    'private_key' => env('GOOGLE_DRIVE_PRIVATE_KEY', ''),
];
