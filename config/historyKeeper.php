<?php

return [
    'middleware' => [],

    'skipTables' => [
        'cache',
        'cache_locks',
        'failed_jobs',
        'jobs',
        'job_batches',
        'migrations',
        'password_reset_tokens',
        config('session.table'),

    ],

    'mustAddTables' => [
    ],
];
