<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect logged in users
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'customer') {
        header('Location: customer_dashboard.php');
        exit;
    }
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'farmer') {
        header('Location: categories.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroPlus - Advanced Farming Solutions</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0 20px;
            background:
                linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                url('assets/images/field.jpg') center/cover;
        }

        .hero-content {
            max-width: 800px;
        }

        .hero h1 {
            font-size: 4.5rem;
            margin-bottom: 20px;
            line-height: 1.1;
            background: linear-gradient(135deg, #fff 0%, var(--primary) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--text-muted);
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .features {
            padding: 100px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .features h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 60px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-item {
            padding: 40px;
            text-align: center;
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <section class="hero">
        <div class="hero-content animate-fade">
            <h1>Revolutionizing <br>Agriculture Direct</h1>
            <p>Empowering farmers and suppliers through cutting-edge technology and direct communication. Join the
                digital transformation of farming today.</p>
            <div style="display: flex; gap: 20px; justify-content: center;">
                <a href="register.php" class="hero-btn">Get Started</a>
                <a href="#features" class="hero-btn"
                    style="background: var(--glass-bg); border: 1px solid var(--glass-border);">Learn More</a>
            </div>
        </div>
    </section>

    <section id="features" class="features">
        <div class="animate-fade">
            <h2>Why Choose AgroPlus?</h2>
            <div class="features-grid">
                <div class="glass-card feature-item">
                    <span class="feature-icon">🌱</span>
                    <h3>Direct Trading</h3>
                    <p>Cut out the middleman and trade directly with farmers and suppliers for better prices.</p>
                </div>
                <div class="glass-card feature-item">
                    <span class="feature-icon">📊</span>
                    <h3>Advanced Insights</h3>
                    <p>Get real-time data on market trends and crop optimization techniques.</p>
                </div>
                <div class="glass-card feature-item">
                    <span class="feature-icon">🚀</span>
                    <h3>Fast Growth</h3>
                    <p>Accelerate your farming business with our digital tools and resources.</p>
                </div>
            </div>
        </div>
    </section>

    <script src="assets/js/main.js"></script>
</body>

</html>