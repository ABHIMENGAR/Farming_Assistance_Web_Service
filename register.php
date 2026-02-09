<?php
require_once "includes/config.php";
session_start();

// Check if already logged in
if (isset($_SESSION["user_id"])) {
    header("location: index.php");
    exit;
}

// Process registration form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $user_type = trim($_POST["user_type"]);

    // Validate input
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email exists
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "This email is already registered.";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashed_password, $user_type);

            if (mysqli_stmt_execute($stmt)) {
                header("location: login.php");
                exit;
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .auth-wrapper {
            min-height: calc(100vh - 70px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            margin-top: 70px;
        }

        .register-container {
            width: 100%;
            max-width: 500px;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .error-msg {
            background: rgba(244, 67, 54, 0.1);
            border: 1px solid #f44336;
            color: #f44336;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="auth-wrapper">
        <div class="register-container animate-fade">
            <div class="auth-header">
                <h1>Create Account</h1>
                <p style="color: var(--text-muted);">Join the future of agricultural trading</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.9rem;">Full Name</label>
                    <input type="text" name="username" required placeholder="Enter your full name">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.9rem;">Email Address</label>
                    <input type="email" name="email" required placeholder="Enter your email">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-size: 0.9rem;">Password</label>
                        <input type="password" name="password" required placeholder="Create password">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-size: 0.9rem;">Confirm</label>
                        <input type="password" name="confirm_password" required placeholder="Confirm password">
                    </div>
                </div>

                <div style="margin-bottom: 30px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.9rem;">I want to join as a</label>
                    <select name="user_type" required>
                        <option value="customer">Customer (Buyer)</option>
                        <option value="farmer">Farmer (Seller)</option>
                    </select>
                </div>

                <button type="submit" class="submit-btn" style="width: 100%;">Create Account</button>

                <div style="margin-top: 25px; text-align: center; color: var(--text-muted); font-size: 0.9rem;">
                    Already have an account? <a href="login.php"
                        style="color: var(--primary); text-decoration: none; font-weight: 600;">Sign In</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>