<?php
require_once "includes/config.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

// Get all categories for filter
$categories = [];
$cat_sql = "SELECT DISTINCT category FROM products WHERE stock > 0";
$cat_result = mysqli_query($conn, $cat_sql);
if ($cat_result) {
    while ($row = mysqli_fetch_assoc($cat_result)) {
        $categories[] = $row['category'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .marketplace-header {
            margin-top: 100px;
            padding: 40px 20px;
            text-align: center;
        }

        .filter-section {
            max-width: 1200px;
            margin: 0 auto 40px;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            align-items: center;
        }

        .search-wrapper {
            flex: 1;
            min-width: 300px;
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .search-wrapper input {
            padding-left: 45px;
        }

        .category-filter {
            width: 200px;
        }

        .products-grid-container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 20px 100px;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <header class="marketplace-header animate-fade">
        <h1 style="font-size: 3rem; margin-bottom: 10px;">Marketplace</h1>
        <p style="color: var(--text-muted);">Fresh produce directly from the source.</p>
    </header>

    <section class="filter-section glass-panel animate-fade">
        <div class="search-wrapper">
            <span style="position: absolute; left: 15px; top: 12px; font-size: 1.2rem;">🔍</span>
            <input type="text" id="search-input" placeholder="Search for crops, fruits, or tools...">
        </div>

        <div class="category-filter">
            <select id="category-select" onchange="loadProducts(`category=${this.value}`)">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="sort-filter">
            <select id="sort-select" onchange="loadProducts(`sort=${this.value}`)">
                <option value="newest">Newest First</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
            </select>
        </div>
    </section>

    <main class="products-grid-container">
        <div class="products-grid">
            <!-- Products will be loaded here via AJAX -->
            <div style="grid-column: 1/-1; text-align: center; padding: 100px;">
                <p style="color: var(--text-muted);">Loading fresh products...</p>
            </div>
        </div>
    </main>

    <script src="assets/js/main.js"></script>
</body>

</html>