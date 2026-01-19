<?php
include 'db_connect.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>About Us - PetAdopt</title>
    <link rel="stylesheet" href="about.css?v=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>

<section class="hero-about">
    <div class="hero-content">
        <p class="tag">🐾 WHO WE ARE</p>
        <h1>Connecting <span>Hearts</span> With <span>Paws</span></h1>
        <p>We believe every pet deserves love, and every family deserves the joy of a furry companion.</p>
    </div>
</section>

<section class="mission">
    <div class="mission-content">
        <div class="mission-text">
            <h2>Our Mission</h2>
            <p>At PetAdopt, we're dedicated to creating meaningful connections between pets in need and loving families. Our platform bridges the gap between shelters and adopters, making the adoption process simple, transparent, and joyful.</p>
            <p>We work tirelessly to ensure that no pet is left behind. Through our network of partner shelters and dedicated volunteers, we've helped thousands of animals find their forever homes.</p>
        </div>
        <div class="mission-image">
            <img src="https://images.unsplash.com/photo-1587300003388-59208cc962cb?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0" alt="Happy dog">
        </div>
    </div>
</section>

<section class="values">
    <h2>Our Core Values</h2>
    <div class="values-grid">
        <div class="value-card">
            <div class="value-icon">❤️</div>
            <h3>Compassion</h3>
            <p>Every animal deserves kindness and care. We treat all pets with the love they deserve.</p>
        </div>
        <div class="value-card">
            <div class="value-icon">🤝</div>
            <h3>Trust</h3>
            <p>We build lasting relationships through transparency and honest communication.</p>
        </div>
        <div class="value-card">
            <div class="value-icon">🏠</div>
            <h3>Commitment</h3>
            <p>We're dedicated to finding the perfect match between pets and their new families.</p>
        </div>
        <div class="value-card">
            <div class="value-icon">🌟</div>
            <h3>Excellence</h3>
            <p>We strive to provide the best adoption experience for both pets and families.</p>
        </div>
    </div>
</section>

<section class="story">
    <div class="story-content">
        <div class="story-image">
            <img src="https://images.unsplash.com/photo-1415369629372-26f2fe60c467?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0" alt="Cat and owner">
        </div>
        <div class="story-text">
            <h2>Our Story</h2>
            <p>PetAdopt was founded in 2020 by a group of animal lovers who saw the need for a better way to connect pets with families. What started as a small local initiative has grown into a nationwide platform.</p>
            <p>Today, we partner with over 50 shelters across the country, helping thousands of pets find loving homes each year. Our community of adopters, volunteers, and supporters continues to grow every day.</p>
            <div class="story-stats">
                <div class="stat-item">
                    <span class="stat-number">5,000+</span>
                    <span class="stat-label">Pets Adopted</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">50+</span>
                    <span class="stat-label">Partner Shelters</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">100+</span>
                    <span class="stat-label">Volunteers</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta">
    <div class="cta-content">
        <h2>Ready to Find Your New Best Friend?</h2>
        <p>Browse our available pets and start your adoption journey today!</p>
        <a href="pets.php" class="cta-btn">View Available Pets</a>
    </div>
</section>

<?php include 'footer.php'; ?>

</body>
</html>
