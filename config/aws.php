<?php

use Aws\Laravel\AwsServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | AWS SDK Configuration
    |--------------------------------------------------------------------------
    |
    | The configuration options set in this file will be passed directly to the
    | `Aws\Sdk` object, from which all client objects are created. The minimum
    | required options are declared here, but the full set of possible options
    | are documented at:
    | http://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/configuration.html
    |
    */


    'region' => 'ap-southeast-1',
    'version' => 'latest',
    'credentials' => [
        'key'    => 'AKIAJJ43NHX454K74AXQ',
        'secret' => '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',
    ],
    'http'    => [
        'verify' => false
    ],
    'ua_append' => [
        'L5MOD/' . AwsServiceProvider::VERSION,
    ],
];
