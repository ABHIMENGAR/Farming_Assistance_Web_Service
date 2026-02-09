<?php
require_once "includes/config.php";
session_start();

// Check if user is logged in and is a customer
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "customer") {
    header("location: login.php");
    exit;
}

// Fetch cart items and calculate total
$sql = "SELECT ci.*, p.name, p.price 
        FROM cart_items ci 
        JOIN products p ON ci.product_id = p.id 
        WHERE ci.user_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$cart_items = mysqli_stmt_get_result($stmt);

$total = 0;
$items = [];
while ($item = mysqli_fetch_assoc($cart_items)) {
    $total += $item['price'] * $item['quantity'];
    $items[] = $item;
}

if ($total <= 0) {
    header("Location: products.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .checkout-wrapper {
            max-width: 800px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }

        .checkout-card {
            padding: 40px;
        }

        .order-summary-list {
            background: rgba(0, 0, 0, 0.2);
            border-radius: var(--radius-md);
            padding: 20px;
            margin: 25px 0;
        }

        .order-item-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 0.95rem;
        }

        .order-item-row:last-child {
            border-bottom: none;
        }

        .payment-total {
            text-align: right;
            margin-top: 25px;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-light);
        }

        .payment-lock-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="checkout-wrapper animate-fade">
        <div class="glass-card checkout-card">
            <header style="text-align: center; margin-bottom: 40px;">
                <h1 style="font-size: 2.5rem; margin-bottom: 10px;">Secure Checkout</h1>
                <p style="color: var(--text-muted);">Almost there! Confirm your order details below.</p>
            </header>

            <div class="order-summary-section">
                <h2 style="font-size: 1.3rem;">Order Summary</h2>
                <div class="order-summary-list">
                    <?php foreach ($items as $item): ?>
                        <div class="order-item-row">
                            <span><?php echo htmlspecialchars($item['name']); ?> <span
                                    style="color: var(--text-muted); font-size: 0.8rem;">(x<?php echo $item['quantity']; ?>)</span></span>
                            <span
                                style="font-weight: 600;">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="payment-total">
                    <span style="font-size: 1rem; color: var(--text-muted); font-weight: 400; margin-right: 10px;">Grand
                        Total:</span>
                    ₹<?php echo number_format($total, 2); ?>
                </div>
            </div>

            <div style="margin-top: 50px;">
                <button id="rzpPayBtn" class="btn-primary"
                    style="width: 100%; padding: 20px; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; gap: 12px;">
                    💳 Complete Secure Payment
                </button>

                <div class="payment-lock-badge">
                    <span>🔒</span>
                    <span>Encrypted & Secured by Razorpay</span>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="cart.php"
                style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem; transition: var(--transition);"
                onmouseover="this.style.color='white'" onmouseout="this.style.color='var(--text-muted)'">
                ← Return to Cart
            </a>
        </div>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        const payBtn = document.getElementById('rzpPayBtn');
        payBtn.addEventListener('click', async function () {
            payBtn.disabled = true;
            payBtn.innerHTML = '⚙️ Initializing Secure Connection...';

            try {
                const createRes = await fetch('create_razorpay_order.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ amount: <?php echo json_encode((float) $total); ?> })
                });
                const orderData = await createRes.json();
                if (orderData.error) { throw new Error(orderData.error); }

                const options = {
                    key: orderData.key,
                    amount: Math.round(<?php echo json_encode((float) $total); ?> * 100),
                    currency: orderData.currency,
                    name: 'AgroPlus',
                    description: 'Direct Farming Marketplace Payment',
                    image: 'assets/images/logo.png', // Add logo if available
                    handler: async function (response) {
                        payBtn.innerHTML = '🔄 Verifying Payment...';
                        try {
                            const verifyRes = await fetch('verify_razorpay_payment.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({
                                    razorpay_payment_id: response.razorpay_payment_id,
                                    razorpay_signature: response.razorpay_signature || '',
                                    local_order_id: orderData.local_order_id
                                })
                            });
                            const verify = await verifyRes.json();
                            if (verify.success && verify.redirect) {
                                window.location.href = verify.redirect;
                            } else {
                                throw new Error(verify.message || 'Payment verification failed');
                            }
                        } catch (e) {
                            alert(e.message);
                            payBtn.innerHTML = '💳 Complete Secure Payment';
                            payBtn.disabled = false;
                        }
                    },
                    prefill: {
                        name: <?php echo json_encode($_SESSION['username'] ?? ''); ?>,
                        email: <?php echo json_encode($_SESSION['email'] ?? ''); ?>
                    },
                    theme: { color: '#4CAF50' }
                };
                const rzp = new Razorpay(options);
                rzp.open();
            } catch (e) {
                alert(e.message);
                payBtn.innerHTML = '💳 Complete Secure Payment';
                payBtn.disabled = false;
            }
        });
    </script>
</body>

</html>