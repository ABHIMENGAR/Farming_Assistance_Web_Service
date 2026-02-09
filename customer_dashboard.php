<?php
require_once "includes/config.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "customer") {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 100px auto;
            padding: 0 20px;
        }

        .dashboard-header {
            margin-bottom: 50px;
            text-align: center;
        }

        .dashboard-header h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }

        .dash-card {
            padding: 30px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .dash-card-icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .dash-card h2 {
            margin-bottom: 15px;
            color: var(--primary-light);
        }

        .dash-card p {
            color: var(--text-muted);
            margin-bottom: 25px;
            font-size: 0.95rem;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="dashboard-container">
        <header class="dashboard-header animate-fade">
            <h1>Welcome back, <?php echo explode(' ', $_SESSION["username"])[0]; ?>!</h1>
            <p>Your agricultural gateway is ready. What would you like to do today?</p>
        </header>

        <div class="dashboard-grid animate-fade">
            <div class="glass-card dash-card">
                <div>
                    <div class="dash-card-icon">🛒</div>
                    <h2>Browse Products</h2>
                    <p>Discover fresh produce and organic products directly from verified farmers across the region.</p>
                </div>
                <a href="products.php" class="btn-primary">Shop Now</a>
            </div>

            <div class="glass-card dash-card">
                <div>
                    <div class="dash-card-icon">📦</div>
                    <h2>My Orders</h2>
                    <p>Track your active orders, view past purchase history, and manage your delivery preferences.</p>
                </div>
                <a href="order_history.php" class="btn-primary">Track Orders</a>
            </div>

            <div class="glass-card dash-card">
                <div>
                    <div class="dash-card-icon">🌱</div>
                    <h2>Organic Methods</h2>
                    <p>Learn about sustainable farming practices and verified organic methods used by our partners.</p>
                </div>
                <a href="customer_organic_methods.php" class="btn-primary">Learn More</a>
            </div>

            <div class="glass-card dash-card">
                <div>
                    <div class="dash-card-icon">🏛️</div>
                    <h2>Govt Schemes</h2>
                    <p>Explore latest government agricultural schemes and support initiatives available in your area.
                    </p>
                </div>
                <a href="government_schemes.php" class="btn-primary">View Schemes</a>
            </div>

            <div class="glass-card dash-card">
                <div>
                    <div class="dash-card-icon">♻️</div>
                    <h2>Waste Management</h2>
                    <p>Efficient ways to handle agricultural waste and discover eco-friendly disposal solutions.</p>
                </div>
                <a href="waste_management.php" class="btn-primary">Resources</a>
            </div>

            <div class="glass-card dash-card" style="border-color: rgba(255,152,0,0.3);">
                <div>
                    <div class="dash-card-icon">👤</div>
                    <h2>Profile Settings</h2>
                    <p>Update your personal information, address, and mobile number for a smoother shopping experience.
                    </p>
                </div>
                <a href="profile.php" class="btn-primary"
                    style="background: linear-gradient(135deg, var(--accent) 0%, #E65100 100%);">Edit Profile</a>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>

</html>