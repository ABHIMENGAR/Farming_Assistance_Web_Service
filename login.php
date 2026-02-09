<?php
require_once "includes/config.php";
require_once "includes/session_config.php";

// Check if already logged in
if (isset($_SESSION["user_id"])) {
    if ($_SESSION["user_type"] === "farmer") {
        header("location: farmer_dashboard.php");
    } else if ($_SESSION["user_type"] === "customer") {
        header("location: customer_dashboard.php");
    } else {
        header("location: index.php");
    }
    exit;
}

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT id, username, password, user_type FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row["password"])) {
            // Regenerate session ID for security
            session_regenerate_id(true);

            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["user_type"] = $row["user_type"];
            $_SESSION["last_activity"] = time();

            if ($row["user_type"] == "farmer") {
                header("Location: farmer_dashboard.php", true, 303);
            } else if ($row["user_type"] == "customer") {
                header("Location: customer_dashboard.php", true, 303);
            } else {
                header("Location: index.php", true, 303);
            }
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .auth-wrapper {
            min-height: calc(100vh - 70px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin-top: 70px;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
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
        <div class="login-container animate-fade">
            <div class="auth-header">
                <h1>Welcome Back</h1>
                <p style="color: var(--text-muted);">Sign in to your AgroPlus account</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.9rem;">Email Address</label>
                    <input type="email" name="email" required placeholder="Enter your email">
                </div>
                <div style="margin-bottom: 30px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.9rem;">Password</label>
                    <input type="password" name="password" required placeholder="Enter your password">
                </div>
                <button type="submit" class="submit-btn" style="width: 100%;">Sign In</button>

                <div style="margin-top: 25px; text-align: center; color: var(--text-muted); font-size: 0.9rem;">
                    Don't have an account? <a href="register.php"
                        style="color: var(--primary); text-decoration: none; font-weight: 600;">Create one</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>