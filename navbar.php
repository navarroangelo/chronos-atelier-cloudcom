<?php
?>

<header class="navbar">
    <div class="navbar__container">
        <!-- Logo -->
        <a href="index.php" class="navbar__logo">
            <img id="navbar-logo" src="src/assets/images/LOGONOBG.webp" alt="Logo of Chronos Atelier">
        </a>

        <!-- Navigation Links -->
        <nav class="navbar__menu">
            <ul class="navbar__list">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="catalog.php">Catalog</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>

        <!-- Right Links -->
        <div class="navbar__right">
            <a href="account.php">Account</a>
        </div>
    </div>
</header>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const logo = document.getElementById("navbar-logo");
    const originalLogo = "src/assets/images/LOGONOBG.webp";
    const newLogo = "src/assets/images/CA-LETTER_LOGO.webp";

    window.addEventListener("scroll", function () {
        const scrollThreshold = 200; // Adjust this value to trigger the change earlier
        const bottomPosition = document.body.offsetHeight - window.innerHeight - scrollThreshold;

        if (window.scrollY >= bottomPosition) {
            logo.src = newLogo; // Change to new logo when near the bottom
        } else {
            logo.src = originalLogo; // Revert to original logo
        }
    });
});
</script>