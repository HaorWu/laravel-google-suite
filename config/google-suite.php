<?php

return [

     /*
     *  The id of the Google Calendar that will be used by default.
     */
    'hosted_domain' => env('GOOGLE_HOSTED_DOMAIN'),

    /*
     *  The id of the Google Calendar that will be used by default.
     */
    'service_account' => env('GOOGLE_SERVICE_ACCOUNT'),

    /*
     * Path to the json file containing the credentials.
     */
    'credentials' => env('GOOGLE_SERVICE_ACCOUNT_CREDENTIALS')
];
