<?php
/**
 * Vercel PHP Router
 * This script routes all incoming requests to the appropriate PHP file in the root directory.
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = ltrim($uri, '/');

// Default to index.php for the root
if ($file === '' || $file === '/') {
    $file = 'index.php';
}

// Security: Prevent directory traversal
if (strpos($file, '..') !== false) {
    http_response_code(403);
    echo "Forbidden";
    exit;
}

// Ensure it has .php extension for routing logic if it points to a root file
if (!str_ends_with($file, '.php')) {
    if (file_exists(__DIR__ . '/../' . $file . '.php')) {
        $file .= '.php';
    }
}

$target = __DIR__ . '/../' . $file;

if (file_exists($target) && !is_dir($target)) {
    // Crucial: Change working directory to the root so that includes work relative to root
    chdir(__DIR__ . '/../');

    // Include the requested file
    require $target;
} else {
    // If it's a directory that exists, look for index.php inside it
    if (is_dir($target)) {
        $indexInside = rtrim($target, '/') . '/index.php';
        if (file_exists($indexInside)) {
            chdir(dirname($indexInside));
            require $indexInside;
            exit;
        }
    }

    // Fallback to 404
    http_response_code(404);
    echo "404 Not Found: " . htmlspecialchars($uri);
}
