<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Update last activity
if (isset($_SESSION['user_id'])) {
    $_SESSION['last_activity'] = time();
}

$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>

<nav class="navbar">
    <div class="nav-container">
        <div class="navbar-left">
            <a href="index.php" class="agroplus-logo">
                <svg class="logo-icon" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="25" cy="40" r="8" fill="#4CAF50" opacity="0.6"/>
                    <rect x="22" y="32" width="6" height="12" fill="#8BC34A" rx="3"/>
                    <ellipse cx="18" cy="28" rx="6" ry="4" fill="#66BB6A" transform="rotate(-30 18 28)"/>
                    <ellipse cx="32" cy="28" rx="6" ry="4" fill="#66BB6A" transform="rotate(30 32 28)"/>
                </svg>
                <span>AgroPlus</span>
            </a>
        </div>
        
        <div class="nav-items">
            <a href="index.php" class="<?php echo ($current_page == 'index') ? 'active' : ''; ?>">Home</a>
            
            <?php if(isset($_SESSION["user_id"])): ?>
                <?php if($_SESSION["user_type"] == 'customer'): ?>
                    <a href="products.php" class="<?php echo ($current_page == 'products') ? 'active' : ''; ?>">Products</a>
                    <a href="cart.php" class="<?php echo ($current_page == 'cart') ? 'active' : ''; ?>">Cart</a>
                <?php endif; ?>

                <div class="user-dropdown">
                    <a href="#" class="username">
                        👤 <?php echo htmlspecialchars($_SESSION["username"]); ?>
                    </a>
                    <div class="dropdown-content">
                        <a href="profile.php">My Profile</a>
                        <?php if($_SESSION["user_type"] == "admin"): ?>
                            <a href="admin_dashboard.php">Admin Panel</a>
                        <?php elseif($_SESSION["user_type"] == "farmer"): ?>
                            <a href="farmer_dashboard.php">Farmer Panel</a>
                        <?php elseif($_SESSION["user_type"] == "customer"): ?>
                            <a href="customer_dashboard.php">Dashboard</a>
                        <?php endif; ?>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php" class="nav-cta">Join Now</a>
                <a href="admin_login.php" style="opacity: 0.6; font-size: 0.8em;">Admin</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<style>
    .navbar {
        height: 70px;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        display: flex;
        align-items: center;
    }

    .nav-container {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
    }

    .nav-items {
        display: flex;
        align-items: center;
        gap: 25px;
    }

    .nav-items a {
        text-decoration: none;
        color: var(--text-muted);
        font-weight: 500;
        font-size: 0.95rem;
        transition: var(--transition);
        position: relative;
    }

    .nav-items a:hover, .nav-items a.active {
        color: var(--text-main);
    }

    .nav-items a.active::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--primary);
        border-radius: 2px;
    }

    .nav-cta {
        background: var(--primary);
        color: white !important;
        padding: 8px 20px !important;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
    }

    .nav-cta:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
    }

    .user-dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background: var(--card-bg);
        backdrop-filter: blur(20px);
        min-width: 180px;
        box-shadow: var(--shadow-premium);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        padding: 8px 0;
        z-index: 1;
        margin-top: 10px;
    }

    .dropdown-content a {
        color: var(--text-muted);
        padding: 12px 20px;
        display: block;
        font-size: 0.9rem;
    }

    .dropdown-content a:hover {
        background: rgba(255, 255, 255, 0.05);
        color: var(--primary);
    }

    .user-dropdown:hover .dropdown-content {
        display: block;
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
 