<?php
require_once "includes/config.php";
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "customer") {
    header("location: login.php");
    exit;
}

// Fetch user's orders
$sql = "SELECT o.*, 
        COUNT(oi.id) as total_items 
        FROM orders o 
        LEFT JOIN order_items oi ON o.id = oi.order_id 
        WHERE o.customer_id = ? 
        GROUP BY o.id 
        ORDER BY o.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
mysqli_stmt_execute($stmt);
$orders_result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .history-section {
            max-width: 900px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }

        .order-card-fancy {
            margin-bottom: 30px;
            padding: 0;
            overflow: hidden;
        }

        .order-head {
            background: rgba(255, 255, 255, 0.03);
            padding: 20px 30px;
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-body {
            padding: 30px;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-completed {
            background: rgba(76, 175, 80, 0.15);
            color: #81c784;
        }

        .status-pending {
            background: rgba(255, 152, 0, 0.15);
            color: #ffb74d;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th {
            text-align: left;
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            padding-bottom: 15px;
        }

        .items-table td {
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 0.95rem;
        }

        .order-foot {
            padding: 20px 30px;
            text-align: right;
            border-top: 1px solid var(--glass-border);
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="history-section">
        <header style="margin-bottom: 40px;" class="animate-fade">
            <h1 style="font-size: 3rem; margin-bottom: 10px;">Order History</h1>
            <p style="color: var(--text-muted);">A comprehensive record of your sustainable purchases.</p>
        </header>

        <?php if (mysqli_num_rows($orders_result) > 0): ?>
            <?php while ($order = mysqli_fetch_assoc($orders_result)): ?>
                <div class="glass-card order-card-fancy animate-fade">
                    <div class="order-head">
                        <div>
                            <span style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-bottom: 5px;">Order
                                Identifier</span>
                            <span
                                style="font-weight: 700;">#AGP-<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></span>
                        </div>
                        <div>
                            <span
                                style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-bottom: 5px; text-align: right;">Status</span>
                            <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </div>
                    </div>

                    <div class="order-body">
                        <?php
                        $items_sql = "SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?";
                        $stmt = mysqli_prepare($conn, $items_sql);
                        mysqli_stmt_bind_param($stmt, "i", $order['id']);
                        mysqli_stmt_execute($stmt);
                        $items = mysqli_stmt_get_result($stmt);
                        ?>
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th style="text-align: right;">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($item = mysqli_fetch_assoc($items)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td style="text-align: right;">₹<?php echo number_format($item['price'], 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="order-foot">
                        <span style="color: var(--text-muted); margin-right: 15px;">Amount Paid:</span>
                        <span
                            style="font-size: 1.4rem; font-weight: 700; color: var(--primary-light);">₹<?php echo number_format($order['total_amount'], 2); ?></span>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="glass-card animate-fade" style="padding: 60px; text-align: center;">
                <span style="font-size: 4rem; display: block; margin-bottom: 20px;">📦</span>
                <h2>No orders found</h2>
                <p style="color: var(--text-muted); margin-bottom: 30px;">Your order history is currently empty.</p>
                <a href="products.php" class="btn-primary">Explore Products</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="assets/js/main.js"></script>
</body>

</html>