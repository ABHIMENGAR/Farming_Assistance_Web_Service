<?php
require_once "includes/config.php";
session_start();

if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

$success_msg = $error_msg = "";

// Fetch current user data
$sql = "SELECT username, email, mobile, address FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $mobile = trim($_POST["mobile"]);
    $address = trim($_POST["address"]);

    $sql = "UPDATE users SET username = ?, email = ?, mobile = ?, address = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $username, $email, $mobile, $address, $_SESSION["user_id"]);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION["username"] = $username;
        header("location: profile.php");
        exit;
    } else {
        $error_msg = "Error updating profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .edit-profile-wrapper {
            max-width: 600px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }

        .edit-card {
            padding: 40px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .input-group label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="edit-profile-wrapper animate-fade">
        <div class="glass-card edit-card">
            <header style="text-align: center; margin-bottom: 40px;">
                <h1 style="font-size: 2.5rem; margin-bottom: 10px;">Edit Profile</h1>
                <p style="color: var(--text-muted);">Personalize your AgroPlus experience.</p>
            </header>

            <?php if ($error_msg): ?>
                <div class="glass-panel"
                    style="padding: 15px; background: rgba(255, 87, 87, 0.1); border-color: rgba(255, 87, 87, 0.2); color: #ff5757; margin-bottom: 25px; border-radius: 8px; text-align: center; font-size: 0.9rem;">
                    <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="form-grid">
                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>"
                        required>
                </div>

                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="input-group">
                    <label>Mobile Number</label>
                    <input type="tel" name="mobile" value="<?php echo htmlspecialchars($user['mobile'] ?? ''); ?>">
                </div>

                <div class="input-group">
                    <label>Residential Address</label>
                    <textarea name="address" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                </div>

                <div style="margin-top: 30px; display: flex; gap: 15px;">
                    <button type="submit" class="btn-primary" style="flex: 2;">Save Profile</button>
                    <a href="profile.php" class="btn-primary"
                        style="flex: 1; background: var(--glass-bg); border: 1px solid var(--glass-border); text-align: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>

</html>