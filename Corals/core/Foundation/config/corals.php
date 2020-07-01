<?php

return [
    'api_version' => env('CORALS_API_VERSION', 'v1'),
    'cache_ttl' => env('DEFAULT_CACHE_TTL', '1440'),
    'slack' => [
        'exception_channels' => array_filter(explode(',', env('SLACK_EXCEPTION_CHANNELS'))),
    ],
];


