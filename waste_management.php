<?php
require_once "includes/config.php";
session_start();

// Fetch all waste management content
$sql = "SELECT * FROM waste_management_content ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sustainable Waste Management - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .waste-section {
            max-width: 1200px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }

        .waste-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .waste-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }

        .waste-card {
            overflow: hidden;
            cursor: pointer;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .waste-thumb {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: var(--transition);
        }

        .waste-card:hover .waste-thumb {
            transform: scale(1.05);
        }

        .waste-info {
            padding: 25px;
            flex: 1;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-window {
            max-width: 800px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 2rem;
            color: white;
            cursor: pointer;
            z-index: 10;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="waste-section">
        <header class="waste-header animate-fade">
            <h1 style="font-size: 3.5rem; margin-bottom: 10px;">Sustainable Future</h1>
            <p style="color: var(--text-muted); font-size: 1.2rem;">Converting agricultural waste into gold through
                innovative recycling techniques.</p>
        </header>

        <div class="waste-grid">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="glass-card waste-card animate-fade"
                    onclick="openWasteModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                    <?php if ($row['image']): ?>
                        <img src="uploads/waste_management/<?php echo htmlspecialchars($row['image']); ?>" class="waste-thumb">
                    <?php endif; ?>
                    <div class="waste-info">
                        <span
                            style="color: var(--primary-light); font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Tutorial
                            & Guide</span>
                        <h2 style="margin: 10px 0; font-size: 1.4rem;"><?php echo htmlspecialchars($row['title']); ?></h2>
                        <p
                            style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 15px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            <?php echo htmlspecialchars($row['description']); ?>
                        </p>
                        <span class="btn-primary" style="padding: 8px 18px; font-size: 0.8rem;">Read More</span>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="wasteModal" class="modal-overlay" onclick="closeWasteModal()">
        <div class="glass-card modal-window animate-fade" onclick="event.stopPropagation()" style="padding: 40px;">
            <span class="modal-close" onclick="closeWasteModal()">&times;</span>
            <img id="modalImg" src="" style="width: 100%; border-radius: var(--radius-md); margin-bottom: 30px;">
            <h2 id="modalTitle" style="font-size: 2rem; margin-bottom: 20px;"></h2>
            <p id="modalDesc" style="color: var(--text-muted); line-height: 1.8; font-size: 1.1rem;"></p>
        </div>
    </div>

    <script>
        function openWasteModal(content) {
            document.getElementById('modalImg').src = 'uploads/waste_management/' + content.image;
            document.getElementById('modalTitle').textContent = content.title;
            document.getElementById('modalDesc').textContent = content.description;
            document.getElementById('wasteModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeWasteModal() {
            document.getElementById('wasteModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeWasteModal();
        });
    </script>
    <script src="assets/js/main.js"></script>
</body>

</html>