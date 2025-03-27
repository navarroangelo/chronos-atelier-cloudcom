<?php
include 'database.php';  // Include the database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chronos Atelier</title>
    <link rel="stylesheet" href="src/assets/style/navbar-styles.css">
    <link rel="stylesheet" href="src/assets/style/footer-styles.css">
    <link rel="stylesheet" href="src/assets/style/catalog-styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>

    <?php include 'navbar.php'; ?>  <!-- Include the navbar -->

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero__overlay"></div> <!-- Overlay -->
        <img src="src/assets/images/hero-home.webp" alt="Hero Watch" class="hero__image">
        <div class="hero__content">
            <h1>Rolex Daytona 40 Full Rose Gold Sundust</h1>
            <p>Watch Highlight</p>
        </div>
    </section>


    <section class="brand-scroller">
        <h2 class="brand-scroller__title">Our Featured Brands</h2>
        <div class="brand-scroller__container">
            <div class="brand-scroller__track">
                <img src="src/assets/images/rolex-logo.webp" alt="Brand 1">
                <img src="src/assets/images/boss-logo.webp" alt="Brand 2">
                <img src="src/assets/images/casio-logo.webp" alt="Brand 3">
                <img src="src/assets/images/citizen-logo.webp" alt="Brand 4">
                <img src="src/assets/images/fossil-logo.webp" alt="Brand 5">
                <img src="src/assets/images/mk-logo.webp" alt="Brand 6">
                <img src="src/assets/images/orient-logo.webp" alt="Brand 7">
                <img src="src/assets/images/tissot-logo.webp" alt="Brand 8">
                <img src="src/assets/images/rolex-logo.webp" alt="Brand 1">
                <img src="src/assets/images/boss-logo.webp" alt="Brand 2">
                <img src="src/assets/images/casio-logo.webp" alt="Brand 3">
                <img src="src/assets/images/citizen-logo.webp" alt="Brand 4">
                <img src="src/assets/images/fossil-logo.webp" alt="Brand 5">
                <img src="src/assets/images/mk-logo.webp" alt="Brand 6">
                <img src="src/assets/images/orient-logo.webp" alt="Brand 7">
                <img src="src/assets/images/tissot-logo.webp" alt="Brand 8">
                <img src="src/assets/images/rolex-logo.webp" alt="Brand 1">
                <img src="src/assets/images/boss-logo.webp" alt="Brand 2">
                <img src="src/assets/images/casio-logo.webp" alt="Brand 3">
                <img src="src/assets/images/citizen-logo.webp" alt="Brand 4">
                <img src="src/assets/images/fossil-logo.webp" alt="Brand 5">
                <img src="src/assets/images/mk-logo.webp" alt="Brand 6">
                <img src="src/assets/images/orient-logo.webp" alt="Brand 7">
                <img src="src/assets/images/tissot-logo.webp" alt="Brand 8">
            </div>
        </div>
    </section>

    <section class="catalogue">
    <h2 class="catalogue__title">Our Watch Collection</h2>

    <!-- Search Input -->
    <div class="catalogue__search">
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search for a watch...">
        <button id="searchButton" onclick="filterWatchesByName()">
            <i class="fas fa-search"></i> <!-- Font Awesome search icon -->
        </button>
        <button id="clearButton" onclick="clearSearch()">
            <i class="fas fa-times"></i> <!-- Font Awesome clear icon -->
        </button>
    </div>
</div>
    
    <div class="catalogue__grid">
        <?php
        // Sample watch data array
        $watches = [
            ["image" => "src/assets/images/1.webp", "name" => "Rolex Daytona 40", "price" => "₱2,980,000.00"],
            ["image" => "src/assets/images/2.webp", "name" => "Santos De Cartier", "price" => "₱498,000.00"],
            ["image" => "src/assets/images/3.webp", "name" => "Santos Dumont Rose Gold", "price" => "₱438,000.00"],
            ["image" => "src/assets/images/4.webp", "name" => "Rolex Yachtmaster", "price" => "₱2,780,000.00"],
            ["image" => "src/assets/images/5.webp", "name" => "Rolex Submariner", "price" => "₱1,980,000.00"],
            ["image" => "src/assets/images/6.webp", "name" => "Rolex Datejust", "price" => "₱1,780,000.00"],
            ["image" => "src/assets/images/7.webp", "name" => "Rolex Oyster Perpetual", "price" => "₱1,380,000.00"],
            ["image" => "src/assets/images/8.webp", "name" => "Rolex Explorer", "price" => "₱1,980,000.00"],
            ["image" => "src/assets/images/9.webp", "name" => "Rolex GMT Master II", "price" => "₱2,380,000.00"],
            ["image" => "src/assets/images/10.webp", "name" => "Rolex Sky Dweller", "price" => "₱3,280,000.00"],
        ];

        // Loop through watches and display cards
        foreach ($watches as $watch) {
            echo '
            <div class="watch-card" data-name="'.$watch["name"].'">
                <img src="'.$watch["image"].'" alt="'.$watch["name"].'">
                <div class="watch-card__info">
                    <h3>'.$watch["name"].'</h3>
                    <p class="watch-card__price">'.$watch["price"].'</p>
                    <button class="watch-card__btn" onclick="openModal(\''.$watch["name"].'\', \''.$watch["image"].'\', \''.$watch["price"].'\')">View Details</button>
                </div>
            </div>';
        }        
        ?>
    </div>
</section>

<div id="watchModal" class="modal">
    <div class="modal__content">
        <span class="modal__close" onclick="closeModal()">&times;</span>
        <div class="modal__body">
            <!-- Image Section -->
            <div class="modal__image-container">
                <img id="modalImage" src="" alt="Watch Image">
            </div>
            <!-- Details Section -->
            <div class="modal__details">
                <h2 id="modalTitle">Watch Title</h2>
                <p id="modalPrice"></p>
                <p id="modalDescription">A luxury watch with timeless design and exceptional craftsmanship.</p>
            </div>
        </div>
    </div>
</div>

<section class="watch-of-the-year">
    <div class="watch-of-the-year__content">
        <img src="src/assets/images/hero-home.webp" alt="Watch of the Year" class="watch-of-the-year__image">
        <div class="watch-of-the-year__text">
            <h2 class="watch-of-the-year__title">Datejust</h2>
            <p class="watch-of-the-year__subtitle">A watch for the dates to remember</p>
            <p class="watch-of-the-year__subtitle">Watch of the Year</p>

        </div>
    </div>
</section>

    <script src="scripts/script.js"></script>
    <?php include 'footer.php'; ?>

</body>
</html>