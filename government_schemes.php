<?php
require_once "includes/config.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

// Fetch government schemes
$schemes = mysqli_query($conn, "SELECT * FROM government_schemes");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Government Schemes - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .schemes-section {
            max-width: 1200px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }

        .schemes-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .schemes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }

        .scheme-card {
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .scheme-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            background: rgba(76, 175, 80, 0.1);
            color: var(--primary-light);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            align-self: flex-start;
        }

        .scheme-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: white;
        }

        .scheme-desc {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 25px;
            flex: 1;
        }

        .scheme-footer {
            margin-top: auto;
            border-top: 1px solid var(--glass-border);
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="schemes-section">
        <header class="schemes-header animate-fade">
            <h1 style="font-size: 3.5rem; margin-bottom: 10px;">Support & Subsidies</h1>
            <p style="color: var(--text-muted); font-size: 1.2rem;">Empowering farmers with the latest government
                initiatives and financial aids.</p>
        </header>

        <div class="schemes-grid">
            <?php while ($scheme = mysqli_fetch_assoc($schemes)): ?>
                <article class="glass-card scheme-card animate-fade">
                    <div class="scheme-badge">Government Registry</div>
                    <h2 class="scheme-title"><?php echo htmlspecialchars($scheme['name']); ?></h2>
                    <p class="scheme-desc"><?php echo htmlspecialchars($scheme['description']); ?></p>

                    <div class="scheme-footer">
                        <a href="#" class="btn-primary" style="width: 100%; text-align: center;">View Details</a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>

</html>