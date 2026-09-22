<?php

// Vercel serverless environment is read-only.
// We must redirect view compilation to /tmp
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

if (!is_dir($_ENV['VIEW_COMPILED_PATH'])) {
    mkdir($_ENV['VIEW_COMPILED_PATH'], 0777, true);
}

require __DIR__ . '/../public/index.php';
