<?php

// config/file_upload.php

return [

    /*
    |--------------------------------------------------------------------------
    | File Upload Driver
    |--------------------------------------------------------------------------
    | Supported drivers: "local", "google_drive"
    |
    | Switch to "google_drive" when ready to migrate.
    | See App\Services\Uploaders\GoogleDriveFileUploader for setup steps.
    |
    */

    'driver' => env('FILESYSTEM_UPLOAD_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Local Driver Options
    |--------------------------------------------------------------------------
    */
    'local' => [
        'disk' => 'public',
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Drive Driver Options
    |--------------------------------------------------------------------------
    */
    'google_drive' => [
        'disk'      => 'google_drive',
        'folder_id' => env('GOOGLE_DRIVE_FOLDER_ID', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload Folders
    |--------------------------------------------------------------------------
    */
    'folders' => [
        'thumbnails'  => 'campaigns/thumbnails',
        'attachments' => 'campaigns/files',
    ],

    /*
    |--------------------------------------------------------------------------
    | Max Sizes (in KB)
    |--------------------------------------------------------------------------
    */
    'max_image_size' => 20480,     // 20 MB
    'max_file_size'  => 3145728,   // 3 GB

];
