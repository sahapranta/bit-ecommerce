<?php

return [
    'api_key' => env('BLOCKIO_API_KEY'),
    'pin' => env('BLOCKIO_PIN'),
    'version' => env('BLOCKIO_VERSION', 2),
    'confirmation' => env('BLOCKIO_MIN_CONFIRMATION', 3),
];
