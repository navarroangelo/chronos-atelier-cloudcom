<?php
// catalog.php
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

    <section class="catalogue">
    <h2 class="catalogue__title">Our Watch Collection</h2>
    
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
            <div class="watch-card">
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

    <!-- Modal for displaying watch details -->
    <div id="watchModal" class="modal">
        <div class="modal__content">
            <span class="modal__close" onclick="closeModal()">&times;</span>
            <div class="modal__body">
                <div class="modal__image-container">
                    <img id="modalImage" src="" alt="Watch Image">
                </div>
                <div class="modal__details">
                    <h2 id="modalTitle">Watch Title</h2>
                    <p id="modalPrice"></p>
                    <p id="modalDescription">A luxury watch with timeless design and exceptional craftsmanship.</p>
                    <button class="modal__btn">Buy Now</button>
                </div>
            </div>
        </div>
    </div>


    <script src="scripts/script.js"></script>
    <?php include 'footer.php'; ?>

</body>
</html>