<?php
require_once "includes/config.php";
session_start();

if (!isset($_SESSION["user_id"]) || !isset($_GET['order_id'])) {
    header("location: index.php");
    exit;
}

$order_id = intval($_GET['order_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Secured - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .success-wrapper {
            max-width: 500px;
            margin: 15vh auto;
            padding: 0 20px;
        }

        .success-card {
            padding: 50px;
            text-align: center;
        }

        .check-orbit {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .check-orbit::after {
            content: '';
            position: absolute;
            inset: -10px;
            border: 2px dashed rgba(76, 175, 80, 0.3);
            border-radius: 50%;
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .order-token {
            background: rgba(255, 255, 255, 0.05);
            padding: 8px 16px;
            border-radius: 20px;
            font-family: monospace;
            font-size: 0.9rem;
            color: var(--primary-light);
            display: inline-block;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="success-wrapper animate-fade">
        <div class="glass-card success-card">
            <div class="check-orbit">
                <span style="font-size: 3rem;">✓</span>
            </div>

            <h1 style="font-size: 2.2rem; margin-bottom: 15px;">Payment Secured</h1>
            <p style="color: var(--text-muted); line-height: 1.6;">Your investment in sustainable farming has been
                successfully processed.</p>

            <div class="order-token">
                ORDER #<?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?>
            </div>

            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 35px;">A digital receipt has been
                dispatched to your registered email address.</p>

            <div style="display: flex; gap: 15px;">
                <a href="order_history.php" class="btn-primary"
                    style="flex: 1; background: var(--glass-bg); border: 1px solid var(--glass-border);">Track Order</a>
                <a href="products.php" class="btn-primary" style="flex: 1;">Keep Shopping</a>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>

</html>