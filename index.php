<?php
session_start(); 

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chronos Atelier</title>
    <link rel="stylesheet" href="src/assets/style/navbar-styles.css">
    <link rel="stylesheet" href="src/assets/style/footer-styles.css?v=">
    <link rel="stylesheet" href="src/assets/style/index-styles.css">
</head>
<body>
        <!-- Navbar Include-->
    <?php include 'navbar.php'; ?> 

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero__overlay"></div> 
        <img src="src/assets/images/hero-home.webp" alt="Hero Watch" class="hero__image">
        <div class="hero__content">
            <h1>Rolex Daytona 40 Full Rose Gold Sundust</h1>
            <p>Watch Highlight</p>
        </div>
    </section>

    <!-- Who We Are Section -->
    <section class="who-we-are">
            <div class="who-we-are__container">
                <div class="who-we-are__text">
                    <h2>Who We Are</h2>
                    <p>
                        Welcome to Chronos Atelier, where timeless craftsmanship meets modern innovation.
                        We specialize in offering premium luxury watches for collectors, enthusiasts,
                        and professionals who appreciate fine horology.
                    </p>
                    <p>
                        With a commitment to authenticity and excellence, our curated collection
                        features some of the most prestigious brands in the world.
                        Whether you're seeking elegance, performance, or investment value, Chronos Atelier is your trusted partner in the art of timekeeping.
                    </p>
                </div>
                <div class="who-we-are__image">
                    <img src="src/assets/images/hero-watch.webp" alt="Luxury watches in showcase">
                </div>
            </div>
        </section>

        <!-- Watches Highlight Section -->
    <section class="watches-highlight">
        <div class="watches-highlight__container">
            <h2>Watches Highlight</h2>
            <div class="watches-highlight__grid">
                <!-- Watch 1 -->
                <div class="watch-card">
                    <img src="src/assets/images/submariner-date.webp" alt="Watch 1">
                    <h3>Rolex Submariner Date</h3>
                    <p>A true classic in the world of diving watches, featuring a robust and elegant design.</p>
                </div>

                <!-- Watch 2 -->
                <div class="watch-card">
                    <img src="src/assets/images/omega-speedmaster.webp" alt="Watch 2">
                    <h3>Omega Speedmaster</h3>
                    <p>The legendary chronograph that went to the moon, a symbol of precision and history.</p>
                </div>

                <!-- Watch 3 -->
                <div class="watch-card">
                    <img src="src/assets/images/cartier-santos.webp" alt="Watch 3">
                    <h3>Cartier Santos De Cartier</h3>
                    <p>An iconic timepiece that combines timeless elegance with modern sophistication, perfect for any occasion.</p>
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
                <h2>Get in Touch with Us</h2>
                <p>Need assistance or have inquiries about our collections? Our team is here to help you with expert advice and exclusive services.</p>
                <a href="contact.php" class="cta-button">Contact Us</a>
            </div>
        </div>
    </section>
    <!-- Footer Include -->
    <?php include 'footer.php'; ?>


</body>
</html>
