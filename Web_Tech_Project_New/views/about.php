<!DOCTYPE html>
<html>

<head>
    <title>About Us - PetAdopt</title>
    <link rel="stylesheet" href="views/assets/css/home.css">
    <link rel="stylesheet" href="views/assets/css/about.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
</head>

<body>

    <?php include 'views/layout/header.php'; ?>
    <section class="hero-about">
        <div class="hero-content">
            <p class="tag">🐶 WHO WE ARE</p>
            <h1>About <span>PetAdopt</span></h1>
            <p>Connecting loving families with pets in need of a forever home. We are dedicated to making the world a
                better place, one paw at a time.</p>
        </div>
    </section>

    <section class="mission">
        <div class="mission-content">
            <div class="mission-text">
                <h2>Our Mission</h2>
                <p>At PetAdopt, we believe every pet deserves a loving home. We work tirelessly with local shelters,
                    foster homes, and rescue organizations to streamline the adoption process.</p>
                <p>Our goal is to create a seamless, transparent, and joyful experience for potential adopters while
                    ensuring every animal finds the perfect match.</p>
            </div>
            <div class="mission-image">
                <img src="https://images.unsplash.com/photo-1541599540903-216a46ca1dc0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                    alt="Happy Dog">
            </div>
        </div>
    </section>

    <section class="values">
        <h2>Our Core Values</h2>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">❤️</div>
                <h3>Compassion</h3>
                <p>We treat every animal with love, respect, and dignity, ensuring their well-being is our top priority.
                </p>
            </div>
            <div class="value-card">
                <div class="value-icon">🤝</div>
                <h3>Integrity</h3>
                <p>We believe in honest, transparent adoption processes to build trust with our community.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🏡</div>
                <h3>Commitment</h3>
                <p>We are committed to finding permanent, safe, and loving homes for all our rescued pets.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🌟</div>
                <h3>Excellence</h3>
                <p>We strive for excellence in animal care, customer service, and community education.</p>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="cta-content">
            <h2>Ready to Adopt?</h2>
            <p>Your new best friend is waiting for you. Browse our available pets and start your adoption journey today!
            </p>
            <a href="index.php?page=pets" class="cta-btn">Find a Pet</a>
        </div>
    </section>

    <?php include 'views/layout/footer.php'; ?>

</body>

</html>