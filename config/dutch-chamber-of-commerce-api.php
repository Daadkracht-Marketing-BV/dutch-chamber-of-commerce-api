<?php

return [
    'api_key' => env('DUTCH_CHAMBER_OF_COMMERCE_API_KEY'),
    'verify' => env('DUTCH_CHAMBER_OF_COMMERCE_API_VERIFY', dirname(__DIR__, 3).DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'cacert.pem')
];
