<?php
require_once "includes/config.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - AgroPlus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .contact-section {
            max-width: 1000px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: start;
        }

        @media (max-width: 800px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
        }

        .contact-info-card {
            padding: 40px;
        }

        .info-slot {
            display: flex;
            gap: 20px;
            margin-bottom: 35px;
        }

        .info-icon {
            width: 50px;
            height: 50px;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-light);
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .map-panel {
            height: 450px;
            overflow: hidden;
            padding: 0;
        }

        .map-panel iframe {
            width: 100%;
            height: 100%;
            border: 0;
            filter: grayscale(1) invert(1) opacity(0.6);
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="contact-section">
        <header style="text-align: center; margin-bottom: 60px;" class="animate-fade">
            <h1 style="font-size: 3.5rem; margin-bottom: 10px;">Get In Touch</h1>
            <p style="color: var(--text-muted);">We are here to support your journey towards sustainable farming.</p>
        </header>

        <div class="contact-grid animate-fade">
            <div class="glass-card contact-info-card">
                <h2 style="margin-bottom: 30px;">Contact Information</h2>

                <div class="info-slot">
                    <div class="info-icon"><i class="fas fa-location-dot"></i></div>
                    <div>
                        <h4 style="color: var(--primary-light);">Our Headquarters</h4>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 5px;">123 Agro Street,
                            Farming District, State - 123456</p>
                    </div>
                </div>

                <div class="info-slot">
                    <div class="info-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <h4 style="color: var(--primary-light);">Call Support</h4>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 5px;">+91 123 456 7890<br>+91
                            987 654 3210</p>
                    </div>
                </div>

                <div class="info-slot">
                    <div class="info-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <h4 style="color: var(--primary-light);">Digital Correspondence</h4>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 5px;">
                            contact@agroplus.com<br>support@agroplus.com</p>
                    </div>
                </div>

                <div style="margin-top: 20px; text-align: center;">
                    <p style="font-size: 0.8rem; color: var(--text-muted);">Available Mon - Sat | 9:00 AM - 6:00 PM</p>
                </div>
            </div>

            <div class="glass-card map-panel">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d118147.68401229884!2d73.12351229883556!3d22.32200405969978!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395fc8ab91a3ddab%3A0xac39d3bfe1473fb8!2sVadodara%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1709799611099!5m2!1sen!2sin"
                    allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>

</html>