<?php
require_once "includes/config.php";
session_start();

if (!isset($_SESSION["user_id"]) || !isset($_SESSION['order_id'])) {
    header("location: index.php");
    exit;
}

$order_id = $_SESSION['order_id'];
$total = $_SESSION['order_total'];
$items = $_SESSION['order_items'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Receipt - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .invoice-wrapper {
            max-width: 700px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }

        .receipt-card {
            padding: 50px;
            border: 2px solid var(--primary);
            position: relative;
        }

        .receipt-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 10px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .success-tick {
            width: 80px;
            height: 80px;
            background: rgba(76, 175, 80, 0.1);
            border: 2px solid var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 20px;
            color: var(--primary);
        }

        .receipt-meta {
            display: flex;
            justify-content: space-between;
            border-top: 1px dashed var(--glass-border);
            border-bottom: 1px dashed var(--glass-border);
            padding: 20px 0;
            margin-bottom: 30px;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .receipt-table {
            width: 100%;
            margin-bottom: 40px;
        }

        .receipt-table th {
            text-align: left;
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding-bottom: 15px;
        }

        .receipt-table td {
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .total-section {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
        }

        .action-row {
            margin-top: 50px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        @media print {

            .no-print,
            .navbar {
                display: none !important;
            }

            .invoice-wrapper {
                margin: 0;
                padding: 0;
                width: 100%;
                max-width: none;
            }

            .receipt-card {
                border: none;
                box-shadow: none;
                background: white;
                color: black;
            }

            .receipt-card * {
                color: black !important;
                border-color: #ddd !important;
            }

            body {
                background: white;
            }
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="invoice-wrapper animate-fade">
        <div class="glass-card receipt-card">
            <div class="receipt-header">
                <div class="success-tick">✓</div>
                <h1 style="color: var(--primary-light);">Payment Successful</h1>
                <p style="color: var(--text-muted);">Thank you for supporting direct farming!</p>
            </div>

            <div class="receipt-meta">
                <div>
                    <strong>Order ID:</strong> #AGP-<?php echo str_pad($order_id, 6, "0", STR_PAD_LEFT); ?>
                </div>
                <div>
                    <strong>Date:</strong> <?php echo date('d M, Y'); ?>
                </div>
            </div>

            <table class="receipt-table">
                <thead>
                    <tr>
                        <th style="width: 60%;">Item</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td style="text-align: center;"><?php echo $item['quantity']; ?></td>
                            <td style="text-align: right;">
                                ₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-section">
                <div style="color: var(--text-muted);">Grand Total</div>
                <div style="font-size: 2.2rem; font-weight: 700; color: var(--primary-light);">
                    ₹<?php echo number_format($total, 2); ?></div>
            </div>

            <div
                style="margin-top: 40px; border-top: 1px solid var(--glass-border); padding-top: 20px; text-align: center;">
                <p style="font-size: 0.8rem; color: var(--text-muted);">
                    A copy of this receipt has been saved to your order history.
                </p>
            </div>
        </div>

        <div class="action-row no-print">
            <button onclick="window.print()" class="btn-primary"
                style="background: var(--glass-bg); border: 1px solid var(--glass-border);">🖨️ Print Receipt</button>
            <a href="customer_dashboard.php" class="btn-primary">🏠 Back to Dashboard</a>
        </div>
    </div>

    <?php
    // We'll keep session variables for a bit longer if needed, or clear them now
    // Actually, clearing them is better to prevent refresh-duplicates
    // unset($_SESSION['order_id']); 
    // unset($_SESSION['order_total']);
    // unset($_SESSION['order_items']);
    ?>
</body>

</html>