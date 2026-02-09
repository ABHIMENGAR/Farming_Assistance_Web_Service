<?php
require_once "includes/config.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "farmer") {
    header("location: login.php");
    exit;
}

$farmer_id = $_SESSION["user_id"];

// Fetch stats
$product_count_query = "SELECT COUNT(*) as total FROM products WHERE farmer_id = $farmer_id";
$product_count_result = mysqli_query($conn, $product_count_query);
$product_count = 0;
if ($product_count_result) {
    $product_count = mysqli_fetch_assoc($product_count_result)['total'];
}

$active_listings = $product_count; // Placeholder logic
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 100px auto;
            padding: 0 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            padding: 30px;
            text-align: center;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            display: block;
        }

        .stat-label {
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .action-card {
            padding: 40px;
            text-align: center;
        }

        .action-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            display: block;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="dashboard-container">
        <header class="animate-fade" style="margin-bottom: 50px; text-align: center;">
            <h1 style="font-size: 3.5rem; margin-bottom: 10px;">Farmer Hub</h1>
            <p style="color: var(--text-muted); font-size: 1.2rem;">Manage your harvest and connect with buyers
                instantly.</p>
        </header>

        <div class="stats-grid animate-fade">
            <div class="glass-card stat-card">
                <span class="stat-value"><?php echo $product_count; ?></span>
                <span class="stat-label">Total Products</span>
            </div>
            <div class="glass-card stat-card">
                <span class="stat-value"><?php echo $active_listings; ?></span>
                <span class="stat-label">Active Listings</span>
            </div>
            <div class="glass-card stat-card">
                <span class="stat-value">0</span>
                <span class="stat-label">Pending Orders</span>
            </div>
        </div>

        <div class="actions-grid animate-fade">
            <div class="glass-card action-card">
                <span class="action-icon">📤</span>
                <h2>Upload Product</h2>
                <p>Add new items to your shop. Include high-quality photos and detailed descriptions for better sales.
                </p>
                <a href="upload_product.php" class="btn-primary" style="margin-top: 20px;">Add New Product</a>
            </div>

            <div class="glass-card action-card">
                <span class="action-icon">📋</span>
                <h2>Manage Listings</h2>
                <p>Update stock levels, edit pricing, or remove products from your public catalog.</p>
                <a href="view_products.php" class="btn-primary" style="margin-top: 20px;">View Inventory</a>
            </div>

            <div class="glass-card action-card">
                <span class="action-icon">📈</span>
                <h2>Sales Insights</h2>
                <p>Coming Soon: Track your revenue and see which products are performing best.</p>
                <button class="btn-primary" disabled style="opacity: 0.5; margin-top: 20px;">Locked</button>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>

</html>