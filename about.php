<?php
require_once "includes/config.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Mission - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .about-section {
            max-width: 1000px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }

        .about-hero {
            text-align: center;
            margin-bottom: 80px;
        }

        .about-hero h1 {
            font-size: 4rem;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #fff 0%, var(--primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .vision-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 80px;
        }

        .vision-card {
            padding: 40px 20px;
            text-align: center;
        }

        .vision-card i {
            font-size: 2.5rem;
            color: var(--primary-light);
            margin-bottom: 20px;
        }

        .mission-panel {
            padding: 60px;
            margin-bottom: 80px;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }

        .member-card {
            padding: 30px;
            text-align: center;
        }

        .member-card h3 {
            color: var(--primary-light);
            margin-bottom: 5px;
        }

        .member-card p {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="about-section">
        <div class="about-hero animate-fade">
            <h1>Revolutionizing Agriculture</h1>
            <p style="color: var(--text-muted); font-size: 1.2rem; max-width: 700px; margin: 0 auto;">
                AgroPlus is more than a marketplace. We are a digital bridge connecting the resilient farmers of today
                with the conscious consumers of tomorrow.
            </p>
        </div>

        <div class="vision-grid">
            <div class="glass-card vision-card animate-fade">
                <i class="fas fa-seedling"></i>
                <h3>Our Vision</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 15px;">Sustainable ecosystems for a
                    healthier planet.</p>
            </div>
            <div class="glass-card vision-card animate-fade">
                <i class="fas fa-users"></i>
                <h3>Community</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 15px;">Empowering local farming
                    networks.</p>
            </div>
            <div class="glass-card vision-card animate-fade">
                <i class="fas fa-leaf"></i>
                <h3>Sustainability</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 15px;">100% Organic & Eco-friendly
                    focus.</p>
            </div>
            <div class="glass-card vision-card animate-fade">
                <i class="fas fa-microchip"></i>
                <h3>Innovation</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 15px;">Smart farming tech solutions.
                </p>
            </div>
        </div>

        <div class="glass-card mission-panel animate-fade">
            <h2 style="font-size: 2.2rem; margin-bottom: 30px; text-align: center;">Our Mission</h2>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center;">
                <div>
                    <p style="line-height: 1.8; color: var(--text-muted);">
                        At AgroPlus, we are dedicated to revolutionizing the agricultural sector by providing a direct
                        platform for farmers to connect with consumers. Our mission is to promote sustainable farming
                        practices while ensuring fair prices for farmers and quality products for consumers.
                    </p>
                </div>
                <ul style="list-style: none; color: var(--primary-light); font-weight: 500;">
                    <li style="margin-bottom: 12px;">✅ Supporting local communities</li>
                    <li style="margin-bottom: 12px;">✅ Promoting sustainable methods</li>
                    <li style="margin-bottom: 12px;">✅ Ensuring fair market prices</li>
                    <li style="margin-bottom: 12px;">✅ Building transparent supply chains</li>
                </ul>
            </div>
        </div>

        <div class="team-section animate-fade" style="text-align: center;">
            <h2 style="font-size: 2.5rem; margin-bottom: 40px;">The Core Team</h2>
            <div class="team-grid">
                <div class="glass-card member-card" style="grid-column: 1 / -1; background: rgba(76, 175, 80, 0.1);">
                    <h3>Abhi Mengar</h3>
                    <p>Strategic Team Leader</p>
                </div>
                <div class="glass-card member-card">
                    <h3>Aniket Kharwar</h3>
                    <p>Core Contributor</p>
                </div>
                <div class="glass-card member-card">
                    <h3>Manav Rathva</h3>
                    <p>Core Contributor</p>
                </div>
                <div class="glass-card member-card">
                    <h3>Nistha Parmar</h3>
                    <p>Core Contributor</p>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>

</html>