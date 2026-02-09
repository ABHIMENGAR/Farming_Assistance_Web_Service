<?php
/**
 * Vercel PHP Router
 * Routes all requests to files in the root directory.
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = ltrim($uri, '/');

// Default to index.php
if ($file === '' || $file === '/') {
    $file = 'index.php';
}

// Security: Prevent directory traversal
if (strpos($file, '..') !== false) {
    http_response_code(403);
    die("Forbidden");
}

// Redirect if .php is omitted but file exists in root
if (!str_ends_with($file, '.php') && !str_contains($file, '.')) {
    if (file_exists(__DIR__ . '/../' . $file . '.php')) {
        $file .= '.php';
    }
}

$target = __DIR__ . '/../' . $file;

if (file_exists($target) && !is_dir($target)) {
    // Assets and uploads should be handled by vercel.json routes, 
    // but just in case they reach here:
    if (str_starts_with($file, 'assets/') || str_starts_with($file, 'uploads/')) {
        return false; // Let Vercel serve it as static
    }

    chdir(__DIR__ . '/../');
    require $target;
} else {
    // 404
    http_response_code(404);
    echo "404 Not Found: " . htmlspecialchars($uri);
}
