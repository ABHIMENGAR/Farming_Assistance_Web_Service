<?php
require_once "includes/config.php";
session_start();

if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

$success_msg = $error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = trim($_POST["current_password"]);
    $new_password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (password_verify($current_password, $user["password"])) {
        if ($new_password == $confirm_password) {
            if (strlen($new_password) >= 6) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE users SET password = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $update_sql);
                mysqli_stmt_bind_param($stmt, "si", $hashed_password, $_SESSION["user_id"]);
                if (mysqli_stmt_execute($stmt))
                    $success_msg = "Password updated successfully!";
                else
                    $error_msg = "Failed to update password. Try again.";
            } else {
                $error_msg = "Password must be at least 6 characters.";
            }
        } else {
            $error_msg = "New passwords do not match.";
        }
    } else {
        $error_msg = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Settings - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .password-wrapper {
            max-width: 500px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }

        .password-card {
            padding: 40px;
        }

        .input-group {
            margin-bottom: 25px;
        }

        .input-group label {
            display: block;
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="password-wrapper animate-fade">
        <div class="glass-card password-card">
            <header style="text-align: center; margin-bottom: 40px;">
                <h1 style="font-size: 2.2rem; margin-bottom: 10px;">Change Password</h1>
                <p style="color: var(--text-muted);">Ensure your account stays secure.</p>
            </header>

            <?php if ($success_msg): ?>
                <div class="glass-panel"
                    style="padding: 12px; background: rgba(129, 199, 132, 0.1); border-color: rgba(129, 199, 132, 0.2); color: #81c784; margin-bottom: 25px; border-radius: 8px; text-align: center; font-size: 0.9rem;">
                    ✓ <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>

            <?php if ($error_msg): ?>
                <div class="glass-panel"
                    style="padding: 12px; background: rgba(255, 152, 0, 0.1); border-color: rgba(255, 152, 0, 0.2); color: #ffb74d; margin-bottom: 25px; border-radius: 8px; text-align: center; font-size: 0.9rem;">
                    ⚠ <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="input-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required placeholder="Enter current password">
                </div>

                <div class="input-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" required placeholder="6+ characters">
                </div>

                <div class="input-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" required placeholder="Repeat new password">
                </div>

                <div style="margin-top: 35px; display: grid; grid-template-columns: 2fr 1fr; gap: 15px;">
                    <button type="submit" class="btn-primary">Update Security</button>
                    <a href="profile.php" class="btn-primary"
                        style="background: var(--glass-bg); border: 1px solid var(--glass-border); text-align: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>

</html>