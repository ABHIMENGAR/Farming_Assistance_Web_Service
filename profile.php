<?php
require_once "includes/config.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

// Fetch latest user data from database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }

        .profile-card {
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--glass-border);
        }

        .profile-avatar-large {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .profile-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .info-group {
            margin-bottom: 10px;
        }

        .info-label {
            display: block;
            color: var(--text-muted);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 1.1rem;
            color: white;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: block;
        }

        .profile-actions {
            margin-top: 40px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="profile-container animate-fade">
        <div class="glass-card profile-card">
            <div class="profile-header">
                <div class="profile-avatar-large">
                    👤
                </div>
                <div>
                    <h1 style="font-size: 2.5rem; line-height: 1;"><?php echo htmlspecialchars($user['username']); ?>
                    </h1>
                    <span
                        style="color: var(--primary-light); font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 2px;">
                        <?php echo htmlspecialchars($user['user_type']); ?> Member
                    </span>
                </div>
            </div>

            <div class="profile-info-grid">
                <div class="info-group">
                    <span class="info-label">Email Address</span>
                    <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="info-group">
                    <span class="info-label">Mobile Number</span>
                    <span class="info-value"><?php echo htmlspecialchars($user['mobile'] ?? 'Not provided'); ?></span>
                </div>
                <div class="info-group" style="grid-column: 1 / -1;">
                    <span class="info-label">Primary Address</span>
                    <span
                        class="info-value"><?php echo nl2br(htmlspecialchars($user['address'] ?? 'No address on file')); ?></span>
                </div>
            </div>

            <div class="profile-actions">
                <a href="edit_profile.php" class="btn-primary">✏️ Edit Profile</a>
                <a href="change_password.php" class="btn-primary"
                    style="background: var(--glass-bg); border: 1px solid var(--glass-border);">🔑 Change Password</a>
                <a href="logout.php" class="btn-primary"
                    style="background: rgba(244, 67, 54, 0.1); border: 1px solid rgba(244, 67, 54, 0.3); color: #f44336;">🚪
                    Logout</a>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>

</html>