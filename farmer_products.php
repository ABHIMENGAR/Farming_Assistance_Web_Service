<?php
require_once "includes/config.php";
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "farmer") {
    header("location: login.php");
    exit;
}

$success_message = $error_message = "";

// Handle product upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = floatval($_POST["price"]);
    $category = trim($_POST["category"]);
    $stock = intval($_POST["stock"]);
    $is_organic = isset($_POST["is_organic"]) ? 1 : 0;

    $image_path = "";
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . "." . $ext;
        $image_path = "assets/images/products/" . $new_filename;
        if (!file_exists("assets/images/products"))
            mkdir("assets/images/products", 0777, true);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
    }

    $sql = "INSERT INTO products (farmer_id, name, description, price, category, is_organic, stock, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "issdsiis", $_SESSION["user_id"], $name, $description, $price, $category, $is_organic, $stock, $image_path);
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Product listing published successfully!";
        } else {
            $error_message = "Failed to list product. Please check your inputs.";
        }
    }
}

// Get farmer's products
$products = [];
$sql = "SELECT * FROM products WHERE farmer_id = ? ORDER BY created_at DESC";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result))
        $products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produce Management - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .farmer-products-wrapper {
            max-width: 1200px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }

        .management-grid {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 40px;
            align-items: start;
        }

        @media (max-width: 1000px) {
            .management-grid {
                grid-template-columns: 1fr;
            }
        }

        .upload-card {
            padding: 40px;
            position: sticky;
            top: 100px;
        }

        .product-list-panel {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .farmer-prod-card {
            display: grid;
            grid-template-columns: 120px 1fr auto;
            gap: 25px;
            padding: 20px;
            align-items: center;
        }

        .farmer-prod-img {
            width: 120px;
            height: 120px;
            border-radius: var(--radius-sm);
            object-fit: cover;
        }

        .badge-organic {
            background: rgba(76, 175, 80, 0.1);
            color: var(--primary-light);
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .stock-indicator {
            font-size: 0.85rem;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="farmer-products-wrapper">
        <header style="margin-bottom: 40px;" class="animate-fade">
            <h1 style="font-size: 3rem;">Produce Management</h1>
            <p style="color: var(--text-muted);">List new products and monitor your current inventory.</p>
        </header>

        <div class="management-grid">
            <aside class="glass-card upload-card animate-fade">
                <h2 style="margin-bottom: 30px;">Add New Harvest</h2>

                <?php if ($success_message): ?>
                    <p style="color:#81c784; margin-bottom:15px; font-size:0.9rem;">✓ <?php echo $success_message; ?></p>
                <?php endif; ?>
                <?php if ($error_message): ?>
                    <p style="color:#ffb74d; margin-bottom:15px; font-size:0.9rem;">⚠ <?php echo $error_message; ?></p>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data">
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-size:0.8rem; margin-bottom:8px;">Product Name</label>
                        <input type="text" name="name" required placeholder="Ex: Organic Tomatoes">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-size:0.8rem; margin-bottom:8px;">Price (₹)</label>
                        <input type="number" name="price" step="0.01" required placeholder="0.00">
                    </div>
                    <div style="margin-bottom: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label style="display:block; font-size:0.8rem; margin-bottom:8px;">Stock Qty</label>
                            <input type="number" name="stock" required placeholder="100">
                        </div>
                        <div>
                            <label style="display:block; font-size:0.8rem; margin-bottom:8px;">Category</label>
                            <select name="category" required>
                                <option value="Vegetables">Vegetables</option>
                                <option value="Fruits">Fruits</option>
                                <option value="Grains">Grains</option>
                                <option value="Dairy">Dairy</option>
                            </select>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-size:0.8rem; margin-bottom:8px;">Description</label>
                        <textarea name="description" required rows="3"
                            placeholder="Describe your produce..."></textarea>
                    </div>
                    <div style="margin-bottom: 25px;">
                        <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                            <input type="checkbox" name="is_organic" style="width: auto;">
                            <span style="font-size:0.9rem;">Certified organic produce</span>
                        </label>
                    </div>
                    <div style="margin-bottom: 30px;">
                        <label style="display:block; font-size:0.8rem; margin-bottom:8px;">Product Image</label>
                        <input type="file" name="image" accept="image/*" required style="font-size:0.8rem;">
                    </div>

                    <button type="submit" class="btn-primary" style="width: 100%;">Publish Listing</button>
                </form>
            </aside>

            <main class="product-list-panel">
                <h2 style="margin-bottom: 25px;">Active Listings (<?php echo count($products); ?>)</h2>

                <?php if (empty($products)): ?>
                    <div class="glass-card animate-fade" style="padding: 60px; text-align: center;">
                        <p style="color: var(--text-muted);">You haven't listed any products yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="glass-card farmer-prod-card animate-fade">
                            <img src="<?php echo htmlspecialchars($product['image'] ?: 'assets/images/default-product.jpg'); ?>"
                                class="farmer-prod-img">
                            <div>
                                <h3 style="margin-bottom: 8px;"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 12px;">
                                    <?php echo htmlspecialchars($product['category']); ?></p>
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <span
                                        style="font-size: 1.1rem; font-weight: 700; color: var(--primary-light);">₹<?php echo number_format($product['price'], 2); ?></span>
                                    <?php if ($product['is_organic']): ?><span
                                            class="badge-organic">ORGANIC</span><?php endif; ?>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <label
                                    style="display:block; font-size:0.7rem; color:var(--text-muted); margin-bottom:5px;">STOCK</label>
                                <span class="stock-indicator <?php echo ($product['stock'] < 10) ? 'text-warning' : ''; ?>"
                                    style="color: <?php echo ($product['stock'] < 10) ? '#ff9800' : 'white'; ?>;">
                                    <?php echo $product['stock']; ?> items
                                </span>
                                <div style="margin-top: 15px;">
                                    <button class="btn-primary"
                                        style="padding: 6px 14px; font-size: 0.75rem; background: var(--glass-bg); border: 1px solid var(--glass-border);">Edit</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>

</html>