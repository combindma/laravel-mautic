<?php

return [
    /*
     * Base URL of the Mautic instance
     */
    'baseUrl' => env('MAUTIC_BASE_URL'),

    /*
     * Client/Consumer key from Mautic
     */
    'clientKey' => env('MAUTIC_PUBLIC_KEY'),

    /*
     * Client/Consumer secret key from Mautic
     */
    'clientSecret' => env('MAUTIC_SECRET_KEY'),

    /*
     * Redirect URI/Callback URI for this script
     */
    'callback' => env('MAUTIC_CALLBACK'),

    /*
    * Enable or disable Mautic. Useful for local development when running tests.
    */
    'apiEnabled' => env('MAUTIC_ENABLED', false),

    /*
    * Filename to use when storing mautic access token. Must be a json File
    */
    'fileName' => 'mautic.json',
];
