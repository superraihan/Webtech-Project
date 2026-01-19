<!DOCTYPE html>
<html>

<head>
    <title>PetAdopt</title>
    <!-- Update path to CSS -->
    <link rel="stylesheet" href="views/assets/css/home.css?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
</head>

<body>

    <?php include 'views/layout/header.php'; ?>

    <section class="hero">
        <div class="text">
            <p class="tag">💫FIND YOUR PERFECT COMPANION</p>
            <h1>Every Pet Deserves a <span>Loving Home</span></h1>
            <p>Join thousands of families who have found their forever friends.🍀</p>
        </div>

        <div class="image">
            <img
                src="https://images.unsplash.com/photo-1450778869180-41d0601e046e?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8Y2F0JTIwYW5kJTIwZG9nfGVufDB8fDB8fHww">
        </div>
    </section>

    <section class="stats">
        <div>
            <h2>5,000+</h2>
            <p>PETS ADOPTED</p>
        </div>
        <div>
            <h2>1,200+</h2>
            <p>HAPPY FAMILIES</p>
        </div>
        <div>
            <h2>50+</h2>
            <p>PARTNER SHELTERS</p>
        </div>
    </section>

    <section class="featured">
        <h2>Featured Pets</h2>

        <div class="featured-grid">
            <?php
            if (!empty($pets)) {
                foreach ($pets as $row) {
                    $imagePath = !empty($row['image']) ? "uploads/" . $row['image'] : "views/assets/images/paw.png";
                    ?>
                    <div class="pet-card">
                        <img src="<?php echo $imagePath; ?>" class="pet-img">
                        <h3>
                            <?php echo $row['name']; ?>
                        </h3>
                        <p class="pet-type">
                            <?php echo $row['type']; ?>
                        </p>
                        <p class="pet-age">Age:
                            <?php echo $row['age']; ?> years
                        </p>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='no-pets'>No pets available for adoption right now.</p>";
            }
            ?>
        </div>
    </section>

    <?php include 'views/layout/footer.php'; ?>

    <!-- Update path to JS -->
    <script src="views/assets/js/home.js"></script>
</body>

</html>