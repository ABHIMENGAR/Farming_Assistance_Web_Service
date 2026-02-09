<?php
require_once "includes/config.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

// Fetch all organic methods content
$sql = "SELECT * FROM organic_methods_content ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

// Group content by method type
$methods = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $methods[$row['method_type']][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organic Methods - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .organic-section {
            max-width: 1200px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }

        .organic-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .method-group {
            margin-bottom: 80px;
        }

        .type-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            color: white;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }

        .method-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }

        .method-card {
            overflow: hidden;
            display: flex;
            flex-direction: column;
            cursor: pointer;
            transition: var(--transition);
        }

        .method-thumb {
            width: 100%;
            height: 240px;
            object-fit: cover;
            transition: var(--transition);
        }

        .method-card:hover .method-thumb {
            transform: scale(1.04);
        }

        .method-body {
            padding: 30px;
            flex: 1;
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(15px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-window {
            max-width: 900px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="organic-section">
        <header class="organic-header animate-fade">
            <h1 style="font-size: 3.5rem; margin-bottom: 10px;">The Organic Way</h1>
            <p style="color: var(--text-muted); font-size: 1.2rem;">Harnessing nature's wisdom for a healthier, more
                sustainable harvest.</p>
        </header>

        <?php foreach ($methods as $type => $contents): ?>
            <section class="method-group">
                <div class="type-badge animate-fade">
                    <?php echo str_replace('_', ' ', htmlspecialchars($type)); ?>
                </div>

                <div class="method-grid">
                    <?php foreach ($contents as $content): ?>
                        <div class="glass-card method-card animate-fade"
                            onclick="openOrganicModal(<?php echo htmlspecialchars(json_encode($content)); ?>)">
                            <?php if ($content['image_path']): ?>
                                <img src="<?php echo htmlspecialchars($content['image_path']); ?>" class="method-thumb">
                            <?php endif; ?>
                            <div class="method-body">
                                <h2 style="font-size: 1.5rem; margin-bottom: 15px;">
                                    <?php echo htmlspecialchars($content['title']); ?></h2>
                                <p
                                    style="color: var(--text-muted); line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 20px;">
                                    <?php echo htmlspecialchars($content['description']); ?>
                                </p>
                                <span class="btn-primary" style="padding: 10px 20px; font-size: 0.85rem;">Learn Technique</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>

    <!-- Modal -->
    <div id="organicModal" class="modal-overlay" onclick="closeOrganicModal()">
        <div class="glass-card modal-window animate-fade" onclick="event.stopPropagation()" style="padding: 50px;">
            <span style="position: absolute; top: 25px; right: 25px; font-size: 2rem; color: white; cursor: pointer;"
                onclick="closeOrganicModal()">&times;</span>
            <img id="mImg" src=""
                style="width: 100%; border-radius: var(--radius-md); margin-bottom: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            <h2 id="mTitle" style="font-size: 2.5rem; margin-bottom: 20px;"></h2>
            <p id="mDesc" style="color: var(--text-muted); line-height: 1.8; font-size: 1.2rem;"></p>
        </div>
    </div>

    <script>
        function openOrganicModal(content) {
            document.getElementById('mImg').src = content.image_path;
            document.getElementById('mTitle').textContent = content.title;
            document.getElementById('mDesc').textContent = content.description;
            document.getElementById('organicModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeOrganicModal() {
            document.getElementById('organicModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeOrganicModal();
        });
    </script>
    <script src="assets/js/main.js"></script>
</body>

</html>