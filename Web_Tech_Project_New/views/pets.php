<!DOCTYPE html>
<html>

<head>
    <title>Available Pets - PetAdopt</title>
    <link rel="stylesheet" href="views/assets/css/home.css">
    <link rel="stylesheet" href="views/assets/css/pets.css?v=3">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
</head>

<body>

    <?php include 'views/layout/header.php'; ?>

    <section class="pets-header">
        <h1>Find Your New <span>Best Friend</span></h1>
        <p>Browse through our available pets by category.</p>

        <?php if ($message): ?>
            <div class="alert <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="filter-section">
        <div class="categories">
            <a href="index.php?page=pets" class="<?php echo ($filter_type == '') ? 'active' : ''; ?>">All</a>
            <a href="index.php?page=pets&type=Cat"
                class="<?php echo ($filter_type == 'Cat') ? 'active' : ''; ?>">Cats</a>
            <a href="index.php?page=pets&type=Dog"
                class="<?php echo ($filter_type == 'Dog') ? 'active' : ''; ?>">Dogs</a>
            <a href="index.php?page=pets&type=Rabbit"
                class="<?php echo ($filter_type == 'Rabbit') ? 'active' : ''; ?>">Rabbits</a>
        </div>
    </section>

    <section class="pets-display">
        <div class="pets-grid">
            <?php
            if (!empty($pets)) {
                foreach ($pets as $row) {
                    $imagePath = !empty($row['image']) ? "uploads/" . $row['image'] : "views/assets/images/paw.png";
                    ?>
                    <div class="pet-card">
                        <img src="<?php echo $imagePath; ?>" class="pet-img">
                        <div class="pet-info">
                            <h3>
                                <?php echo $row['name']; ?>
                            </h3>
                            <p class="type">
                                <?php echo $row['type']; ?>
                            </p>
                            <p class="age">Age:
                                <?php echo $row['age']; ?> years
                            </p>
                            <p class="desc">
                                <?php echo $row['description']; ?>
                            </p>

                            <?php if (isset($_SESSION['user_id']) && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')): ?>
                                <form method="POST" class="adopt-form">
                                                <input type="hidden" name="pet_id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" name="request_adoption" class="adopt-btn">Request Adoption</button>
                                            </form>
                                    <?php elseif (!isset($_SESSION['email'])): ?>
                                            <a href="index.php?page=login"><button class="adopt-btn">Login to Adopt</button></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php
                }
            } else {
                echo "<p class='no-pets'>No pets found in this category.</p>";
            }
            ?>
        </div>
    </section>

    <?php include 'views/layout/footer.php'; ?>
    <script src="views/assets/js/pets.js"></script>

</body>

</html>