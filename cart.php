<?php
require_once "includes/config.php";
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "customer") {
    header("location: login.php");
    exit;
}

// Handle quantity updates (Synchronous for stability, but we'll style it well)
if (isset($_POST['bulk_update']) && isset($_POST['items']) && is_array($_POST['items'])) {
    foreach ($_POST['items'] as $id => $qty) {
        $cart_item_id = intval($id);
        $quantity = intval($qty);
        if ($quantity <= 0) {
            $sql = "DELETE FROM cart_items WHERE id = ? AND user_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ii", $cart_item_id, $_SESSION['user_id']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        } else {
            $sql = "UPDATE cart_items SET quantity = ? WHERE id = ? AND user_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "iii", $quantity, $cart_item_id, $_SESSION['user_id']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }
    header("Location: cart.php");
    exit;
}

// Fetch cart items
$sql = "SELECT ci.*, p.name, p.price, p.image 
        FROM cart_items ci 
        JOIN products p ON ci.product_id = p.id 
        WHERE ci.user_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$cart_items_result = mysqli_stmt_get_result($stmt);
$items = [];
$total = 0;
while ($row = mysqli_fetch_assoc($cart_items_result)) {
    $items[] = $row;
    $total += $row['price'] * $row['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .cart-section {
            max-width: 1000px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }

        .cart-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .cart-grid {
                grid-template-columns: 1fr;
            }
        }

        .cart-item-card {
            display: grid;
            grid-template-columns: 100px 1fr auto;
            gap: 20px;
            padding: 20px;
            margin-bottom: 20px;
            align-items: center;
        }

        .cart-item-img {
            width: 100px;
            height: 100px;
            border-radius: var(--radius-sm);
            object-fit: cover;
        }

        .item-qty-input {
            width: 70px;
            padding: 8px;
            text-align: center;
            background: rgba(0, 0, 0, 0.2) !important;
        }

        .summary-card {
            padding: 30px;
            position: sticky;
            top: 100px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .summary-total {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--glass-border);
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-light);
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="cart-section">
        <h1 style="margin-bottom: 30px;" class="animate-fade">Shopping Cart</h1>

        <?php if (empty($items)): ?>
            <div class="glass-card animate-fade" style="padding: 60px; text-align: center;">
                <span style="font-size: 4rem; display: block; margin-bottom: 20px;">🛒</span>
                <h2>Your cart is empty</h2>
                <p style="color: var(--text-muted); margin-bottom: 30px;">Looks like you haven't added anything yet.</p>
                <a href="products.php" class="btn-primary">Start Shopping</a>
            </div>
        <?php else: ?>
            <form method="POST" class="cart-grid animate-fade">
                <input type="hidden" name="bulk_update" value="1">

                <div class="cart-items-list">
                    <?php foreach ($items as $item): ?>
                        <div class="glass-card cart-item-card">
                            <img src="<?php echo htmlspecialchars($item['image'] ?: 'assets/images/default-product.jpg'); ?>"
                                class="cart-item-img">
                            <div>
                                <h3 style="margin-bottom: 5px;"><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p style="color: var(--primary-light); font-weight: 600;">
                                    ₹<?php echo number_format($item['price'], 2); ?></p>
                            </div>
                            <div style="text-align: right;">
                                <label
                                    style="display: block; font-size: 0.7rem; color: var(--text-muted); margin-bottom: 5px;">Quantity</label>
                                <input type="number" name="items[<?php echo $item['id']; ?>]"
                                    value="<?php echo $item['quantity']; ?>" min="0" class="item-qty-input">
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div style="text-align: left; margin-top: 10px;">
                        <button type="submit" class="btn-primary"
                            style="background: var(--glass-bg); border: 1px solid var(--glass-border);">🔄 Update
                            Quantities</button>
                    </div>
                </div>

                <div class="glass-card summary-card">
                    <h2 style="margin-bottom: 25px;">Order Summary</h2>
                    <div class="summary-row">
                        <span style="color: var(--text-muted);">Subtotal</span>
                        <span>₹<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span style="color: var(--text-muted);">Shipping</span>
                        <span style="color: var(--primary);">FREE</span>
                    </div>
                    <div class="summary-total">
                        <span>Total</span>
                        <span>₹<?php echo number_format($total, 2); ?></span>
                    </div>

                    <a href="checkout.php" class="btn-primary"
                        style="width: 100%; margin-top: 30px; text-align: center; display: block; font-size: 1.1rem; padding: 15px;">Secure
                        Checkout</a>

                    <p style="text-align: center; font-size: 0.75rem; color: var(--text-muted); margin-top: 15px;">
                        🛡️ 100% Secure Payments
                    </p>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script src="assets/js/main.js"></script>
</body>

</html>