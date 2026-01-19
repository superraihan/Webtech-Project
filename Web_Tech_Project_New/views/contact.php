<!DOCTYPE html>
<html>

<head>
    <title>Contact Us - PetAdopt</title>
    <!-- Update CSS path -->
    <link rel="stylesheet" href="views/assets/css/contact.css?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
</head>

<body>

    <?php include 'views/layout/header.php'; ?>

    <section class="hero-contact">
        <div class="hero-content">
            <p class="tag">📬 GET IN TOUCH</p>
            <h1>We'd Love to <span>Hear</span> From You</h1>
            <p>Have questions about adoption? Want to partner with us? We're here to help!</p>
        </div>
    </section>

    <!-- Display messages if any -->
    <!-- The original code didn't seem to display specific success/error messages in the HTML body except setting variables? 
     Ah, I see in original file: 
     It sets $success_message/$error_message but where are they echoed?
     Checking original file content...
     I don't see echo $success_message in the view part!
     It seems the original code was incomplete or I missed it.
     I will add display logic for them.
-->
    <?php if ($success_message): ?>
        <div class="success-msg">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="error-msg">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>


    <section class="contact-section">
        <div class="contact-container">

            <!-- Added form tag requirement if it was missing or assuming it handles post -->
            <!-- Original file had HTML form? 
             Wait, looking at Step 87...
             It DOES NOT HAVE A FORM TAG wrapping inputs? 
             It has <div class="contact-info"> ... </div>
             But NO FORM visible in the snippet provided in Step 87!
             Wait, Step 87 shows lines 1-96. The snippet ends at </html>.
             It displays "Contact Information" but NO INPUT FIELDS!
             The PHP logic at top checks for POST ['name'], ['email'] etc. 
             But the HTML doesn't have a form?
             Maybe I missed it or it was scrolled out?
             "Showing lines 1 to 96". 
             It seems the User's `contact.php` only displays info and has PHP logic for a form that IS MISSING from the view?
             Or maybe the form was supposed to be there.
             
             I will preserve the current HTML. If the form is missing, I won't invent it unless asked, or I might have missed it if the file was larger than 96 lines?
             "Total Lines: 96". So that's the whole file.
             It seems `contact.php` is incomplete (has logic but no form UI).
             I will just copy what's there.
        -->

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
        </div>
    </section>

    <?php include 'views/layout/footer.php'; ?>

</body>

</html>