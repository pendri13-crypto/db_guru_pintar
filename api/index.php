<?php

// Vercel serverless environment is read-only.
// We must redirect view compilation and bootstrap cache to /tmp
$tmpDir = '/tmp/storage';

$_ENV['VIEW_COMPILED_PATH'] = $tmpDir . '/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = $tmpDir . '/framework/views';

$_ENV['APP_SERVICES_CACHE'] = $tmpDir . '/bootstrap/cache/services.php';
$_SERVER['APP_SERVICES_CACHE'] = $tmpDir . '/bootstrap/cache/services.php';

$_ENV['APP_PACKAGES_CACHE'] = $tmpDir . '/bootstrap/cache/packages.php';
$_SERVER['APP_PACKAGES_CACHE'] = $tmpDir . '/bootstrap/cache/packages.php';

$_ENV['APP_CONFIG_CACHE'] = $tmpDir . '/bootstrap/cache/config.php';
$_SERVER['APP_CONFIG_CACHE'] = $tmpDir . '/bootstrap/cache/config.php';

$_ENV['APP_ROUTES_CACHE'] = $tmpDir . '/bootstrap/cache/routes.php';
$_SERVER['APP_ROUTES_CACHE'] = $tmpDir . '/bootstrap/cache/routes.php';

$_ENV['APP_EVENTS_CACHE'] = $tmpDir . '/bootstrap/cache/events.php';
$_SERVER['APP_EVENTS_CACHE'] = $tmpDir . '/bootstrap/cache/events.php';

if (!is_dir($_ENV['VIEW_COMPILED_PATH'])) {
    mkdir($_ENV['VIEW_COMPILED_PATH'], 0777, true);
}
if (!is_dir($tmpDir . '/bootstrap/cache')) {
    mkdir($tmpDir . '/bootstrap/cache', 0777, true);
}

require __DIR__ . '/../public/index.php';
