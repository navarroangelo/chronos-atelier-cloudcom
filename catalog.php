<?php
include 'database.php'; 


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
    <link rel="stylesheet" href="src/assets/style/catalog-styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <section class="hero">
    <div class="hero__overlay"></div> <!-- Overlay -->
    <video autoplay muted loop class="hero__video">
        <source src="src/assets/images/catalog-vid.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="hero__content">
        <h1>Explore our Collection</h1>
        <p>Catalog</p>
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
        <div class="catalogue__search">
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search by name or brand...">
            <i class="fas fa-search search-icon" onclick="filterWatches()"></i> <!-- Search icon -->
            <i class="fas fa-times clear-icon" onclick="clearSearch()"></i> <!-- Clear icon -->
        </div>
    </div>
        <div class="catalogue__grid">
        <?php
        // Fetch data from the database
        $sql = "SELECT watch_name, watch_brand, watch_price, watch_year, watch_description, watch_image FROM watches";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Loop through the results and display each watch
            while ($row = $result->fetch_assoc()) {
                // Check if the image is a file path or binary data
                $imageSrc = '';
                if (strpos($row["watch_image"], 'src/') === 0) {
                    // Local image path
                    $imageSrc = $row["watch_image"];
                } else {
                    // Binary image data
                    $imageSrc = 'data:image/jpeg;base64,' . base64_encode($row["watch_image"]);
                }

                echo '
                <div class="watch-card" data-name="' . htmlspecialchars($row["watch_name"]) . '">
                    <img src="' . $imageSrc . '" alt="' . htmlspecialchars($row["watch_name"]) . '">
                    <div class="watch-card__info">
                        <h3>' . htmlspecialchars($row["watch_name"]) . '</h3>
                        <p class="watch-card__brand">Brand: ' . htmlspecialchars($row["watch_brand"]) . '</p>
                        <p class="watch-card__price">₱' . number_format($row["watch_price"], 2) . '</p>
                        <p class="watch-card__year">Year: ' . htmlspecialchars($row["watch_year"]) . '</p>
                        <button class="watch-card__btn" onclick="openModal(
                            \'' . addslashes($row["watch_name"]) . '\',
                            \'' . addslashes($imageSrc) . '\',
                            \'' . addslashes($row["watch_price"]) . '\',
                            \'' . addslashes($row["watch_description"]) . '\',
                            \'' . addslashes($row["watch_brand"]) . '\',
                            \'' . addslashes($row["watch_year"]) . '\'
                        )">View Details</button>
                    </div>
                </div>';
            }
        } else {
            echo '<div class="no-results">No watches found in the database.</div>';
        }

        // Close the database connection
        $conn->close();
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
                <p id="modalBrand"></p>
                <p id="modalYear"></p>
                <p id="modalPrice"></p>
                <p id="modalDescription"></p>
            </div>
        </div>
    </div>
</div>

<section class="watch-of-the-year">
    <div class="watch-of-the-year__content">
        <img src="src/assets/images/watch-of-the-year.webp" alt="Watch of the Year" class="watch-of-the-year__image">
        <div class="watch-of-the-year__text">
            <h2 class="watch-of-the-year__title">Submariner</h2>
            <p class="watch-of-the-year__subtitle">A watch for the dates to remember</p>
            <p class="watch-of-the-year__subtitle">Watch of the Year</p>

        </div>
    </div>
</section>

    <script src="scripts/script.js"></script>
    <?php include 'footer.php'; ?>

</body>
</html>