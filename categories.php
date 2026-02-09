<?php
require_once "includes/config.php";
if (session_status() === PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != "farmer") {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Services - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .services-section {
            max-width: 1200px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .service-card {
            padding: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            text-align: center;
        }

        .service-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid var(--glass-border);
        }

        .service-body {
            padding: 30px;
            flex: 1;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="services-section">
        <header style="text-align: center; margin-bottom: 60px;" class="animate-fade">
            <h1 style="font-size: 3.5rem; margin-bottom: 10px;">Farmer Services</h1>
            <p style="color: var(--text-muted);">Comprehensive tools designed for the modern agricultural leader.</p>
        </header>

        <div class="services-grid">
            <article class="glass-card service-card animate-fade">
                <img src="assets/images/download (4).png" class="service-img">
                <div class="service-body">
                    <h2 style="margin-bottom: 15px;">Produce Market</h2>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 25px;">List your harvest and
                        reach thousands of customers directly.</p>
                    <a href="farmer_products.php" class="btn-primary" style="width: 100%;">Marketplace Hub</a>
                </div>
            </article>

            <article class="glass-card service-card animate-fade">
                <img src="assets/images/download (2).png" class="service-img">
                <div class="service-body">
                    <h2 style="margin-bottom: 15px;">Waste Recovery</h2>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 25px;">Sustainable techniques
                        for agricultural waste recycling.</p>
                    <a href="waste_management.php" class="btn-primary" style="width: 100%;">Recycle Guide</a>
                </div>
            </article>

            <article class="glass-card service-card animate-fade">
                <img src="assets/images/download (3).png" class="service-img">
                <div class="service-body">
                    <h2 style="margin-bottom: 15px;">Organic Growth</h2>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 25px;">Explore natural
                        pesticides and eco-friendly practices.</p>
                    <a href="organic_methods.php" class="btn-primary" style="width: 100%;">Organic Techniques</a>
                </div>
            </article>

            <article class="glass-card service-card animate-fade">
                <img src="assets/images/download (1).png" class="service-img">
                <div class="service-body">
                    <h2 style="margin-bottom: 15px;">Govt. Support</h2>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 25px;">Access subsidies and
                        initiatives dedicated to farmers.</p>
                    <a href="government_schemes.php" class="btn-primary" style="width: 100%;">View Schemes</a>
                </div>
            </article>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>

</html>