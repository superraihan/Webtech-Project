<?php
include 'db_connect.php';
session_start();

$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        // In a real application, you would send an email or save to database here
        $success_message = 'Thank you for your message! We will get back to you soon.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Us - PetAdopt</title>
    <link rel="stylesheet" href="contact.css?v=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>

<section class="hero-contact">
    <div class="hero-content">
        <p class="tag">📬 GET IN TOUCH</p>
        <h1>We'd Love to <span>Hear</span> From You</h1>
        <p>Have questions about adoption? Want to partner with us? We're here to help!</p>
    </div>
</section>

<section class="contact-section">
    <div class="contact-container">
        <div class="contact-info">
            <h2>Contact Information</h2>
            <p>Reach out to us through any of these channels. We typically respond within 24 hours.</p>
            
            <div class="info-items">
                <div class="info-item">
                    <div class="info-icon">📍</div>
                    <div class="info-text">
                        <h4>Our Location</h4>
                        <p>123 Pet Street, Animal City<br>CA 90210, USA</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">📧</div>
                    <div class="info-text">
                        <h4>Email Us</h4>
                        <p>info@petadopt.com<br>support@petadopt.com</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">📞</div>
                    <div class="info-text">
                        <h4>Call Us</h4>
                        <p>+1 (555) 123-4567<br>Mon - Fri: 9AM - 6PM</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">💬</div>
                    <div class="info-text">
                        <h4>Social Media</h4>
                        <div class="social-links">
                            <a href="#">Facebook</a>
                            <a href="#">Instagram</a>
                            <a href="#">Twitter</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="contact-form-container">
            <h2>Send Us a Message</h2>
            
            <?php if ($success_message): ?>
                <div class="alert success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" class="contact-form">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" placeholder="John Doe" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="john@example.com" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <select id="subject" name="subject" required>
                        <option value="">Select a subject</option>
                        <option value="adoption">Pet Adoption Inquiry</option>
                        <option value="volunteer">Volunteer Opportunities</option>
                        <option value="partnership">Shelter Partnership</option>
                        <option value="donation">Donations</option>
                        <option value="feedback">Feedback</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" rows="5" placeholder="Tell us how we can help you..." required></textarea>
                </div>
                
                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>
    </div>
</section>

<section class="faq">
    <h2>Frequently Asked Questions</h2>
    <div class="faq-grid">
        <div class="faq-item">
            <h3>How do I adopt a pet?</h3>
            <p>Browse our available pets, click on one you're interested in, and fill out the adoption application. Our team will review your application and contact you within 48 hours.</p>
        </div>
        <div class="faq-item">
            <h3>What are the adoption fees?</h3>
            <p>Adoption fees vary depending on the pet and include vaccinations, microchipping, and spaying/neutering. Fees typically range from $50 to $300.</p>
        </div>
        <div class="faq-item">
            <h3>Can I return an adopted pet?</h3>
            <p>We have a 30-day adjustment period. If things don't work out, please contact us and we'll help find a solution or accept the pet back.</p>
        </div>
        <div class="faq-item">
            <h3>How can I become a volunteer?</h3>
            <p>We're always looking for passionate volunteers! Fill out the contact form above or email us at volunteer@petadopt.com to learn about opportunities.</p>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

</body>
</html>
