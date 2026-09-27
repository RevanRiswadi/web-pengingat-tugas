<?php

// Prepare writable storage structure in /tmp for Vercel Serverless
$storageDirs = [
    '/tmp/storage',
    '/tmp/storage/app',
    '/tmp/storage/app/public',
    '/tmp/storage/framework',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap',
    '/tmp/bootstrap/cache',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Touch empty log file in /tmp if missing
if (!file_exists('/tmp/storage/logs/laravel.log')) {
    @touch('/tmp/storage/logs/laravel.log');
}

// Set environment variables for serverless runtime
putenv('VERCEL=1');
putenv('LOG_CHANNEL=stderr');
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
$_ENV['VERCEL'] = '1';
$_ENV['LOG_CHANNEL'] = 'stderr';
$_SERVER['VERCEL'] = '1';
$_SERVER['LOG_CHANNEL'] = 'stderr';

// Forward the request to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
