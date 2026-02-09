<?php
require_once "config.php";

header('Content-Type: application/json');

$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$term = isset($_GET['term']) ? mysqli_real_escape_string($conn, $_GET['term']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

try {
    $sql = "SELECT p.*, u.username as farmer_name 
            FROM products p 
            LEFT JOIN users u ON p.farmer_id = u.id 
            WHERE p.stock > 0";

    if ($category !== '') {
        $sql .= " AND p.category = '$category'";
    }

    if ($term !== '') {
        $sql .= " AND (p.name LIKE '%$term%' OR p.description LIKE '%$term%')";
    }

    switch ($sort) {
        case 'price_asc':
            $sql .= " ORDER BY p.price ASC";
            break;
        case 'price_desc':
            $sql .= " ORDER BY p.price DESC";
            break;
        case 'newest':
        default:
            $sql .= " ORDER BY p.created_at DESC";
            break;
    }

    $result = mysqli_query($conn, $sql);

    $products = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }

    echo json_encode($products);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch products']);
}