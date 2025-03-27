<?php
// catalog.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | Chronos Atelier</title>
    <link rel="stylesheet" href="src/assets/style/about-styles.css">
    <link rel="stylesheet" href="src/assets/style/navbar-styles.css">
    <link rel="stylesheet" href="src/assets/style/footer-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:wght@300;400;600&family=Figtree:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?> <!-- Include the navbar -->

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero__overlay"></div> <!-- Overlay -->
        <img src="src/assets/images/ABOUT-HEROIMAGE.webp" alt="Hero Watch" class="hero__image">
        <div class="hero__content">
            <h1>About Us</h1>
            <p>The Story Behind the Atelier</p>
        </div>
    </section>

    <!-- Chrono Story Section -->
    <section class="chrono-story">
        <div class="chrono-container">
            <div class="chrono-text">
                <h2>A Legacy in Every Tick</h2>
                <p>
                    Founded with a passion for luxury timepieces, Chronos Atelier has curated an exclusive collection of watches that redefine elegance and precision. Our journey began with a simple yet powerful idea: to provide watch enthusiasts with the finest craftsmanship from around the world.
                </p>
                <p>
                    Each piece we showcase is a testament to timeless artistry, blending tradition with modern innovation. Whether you're a collector or a first-time buyer, Chronos Atelier is your gateway to luxury horology.
                </p>
            </div>
            <div class="chrono-image">
                <img src="src/assets/images/STORY-IMAGE.webp" alt="Chrono Story Image">
            </div>
        </div>
    </section>

    <!-- Mission and Vision Section -->
    <section class="mission-vision">
        <div class="mission-container">
            <h2>Our Blueprint for Timeless Excellence</h2>
            <div class="mission-vision-content">
                <div class="mission">
                    <i class="fas fa-bullseye"></i> <!-- Mission Icon -->
                    <h3>Our Mission</h3>
                    <p>
                        At Chronos Atelier, our mission is to bring the finest selection of luxury watches to collectors and enthusiasts worldwide. We are dedicated to providing authenticity, quality craftsmanship, and unparalleled customer service, ensuring that every timepiece tells a story of excellence.
                    </p>
                </div>
                <div class="vision">
                    <i class="fas fa-eye"></i> <!-- Vision Icon -->
                    <h3>Our Vision</h3>
                    <p>
                        We envision a world where luxury watches are more than just accessories—they are symbols of passion, heritage, and prestige. Chronos Atelier aims to be the premier destination for watch lovers, connecting people with timepieces that resonate with their unique style and values.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Owners & Leadership Section -->
    <section class="leadership">
        <div class="leadership-container">
            <h2>Meet the Timekeepers</h2>
            <div class="leaders">
                <div class="leader">
                    <img src="src/assets/images/OWNER-IMAGE.webp" alt="CEO">
                    <h3>Marcel Angelo Navarro</h3>
                    <p>Founder & CEO</p>
                </div>
                <div class="leader">
                    <img src="src/assets/images/CO-OWNER-IMAGE.webp" alt="COO">
                    <h3>Brent Axel Francisco</h3>
                    <p>COO</p>
                </div>
            </div>
        </div>
    </section>

    
     <!-- Contact CTA Section -->
     <section class="contact-cta">
        <div class="contact-cta__container">
            <div class="contact-cta__image">
                <img src="src/assets/images/LOGONOBG.webp" alt="Contact Chronos Atelier">
            </div>
            <div class="contact-cta__content">
                <h2>Your Time, Our Expertise</h2>
                <p>From choosing the perfect timepiece to appreciating its intricate details, we ensure every second of your experience is exceptional..</p>
                <a href="contact.php" class="cta-button">Contact Us</a>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>