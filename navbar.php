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
            <div class="dropdown">
                <button class="dropdown__button">Account ▼</button>
                <div class="dropdown__menu">
                    <h3 class="dropdown__title">Chronos Atelier</h2>
                    <?php if (isset($_SESSION['username'])): ?>
                        <p class="dropdown__greeting">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                 <a href="admin-dashboard.php">Admin Dashboard</a>
                         <?php endif; ?>
                    <?php endif; ?>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
    </div>
</header>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const logo = document.getElementById("navbar-logo");
    const originalLogo = "src/assets/images/LOGONOBG.webp";
    const newLogo = "src/assets/images/CA-LETTER_LOGO.webp";

    window.addEventListener("scroll", function () {
        const scrollThreshold = 200; 
        const bottomPosition = document.body.offsetHeight - window.innerHeight - scrollThreshold;

        if (window.scrollY >= bottomPosition) {
            logo.src = newLogo; 
        } else {
            logo.src = originalLogo; 
        }
    });
});
</script>