<?php
// clear-pail-cache.php
$files = [
    'bootstrap/cache/packages.php',
    'bootstrap/cache/services.php',
    'bootstrap/cache/config.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "Removed: $file\n";
    }
}

echo "Cache cleared successfully!\n";
