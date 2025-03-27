<?php
// contact.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Chronos Atelier</title>
    <link rel="stylesheet" href="src/assets/style/contact-styles.css?v=">
    <link rel="stylesheet" href="src/assets/style/navbar-styles.css">
    <link rel="stylesheet" href="src/assets/style/footer-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>
<body>
    <?php include 'navbar.php'; ?> <!-- Include the navbar -->

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero__overlay"></div> <!-- Overlay -->
        <img src="src/assets/images/CONTACT-US-HERO.webp" alt="Hero Watch" class="hero__image">
        <div class="hero__content">
            <h1>Contact Us</h1>
            <p class="header-subtext">Time is valuable, so is your experience.</p>
        </div>
    </section>

   <!-- Contact Section -->
<section class="contact-container">
    <div class="contact-form">
        <h2>Start your Timless Journey</h2>
        <form action="https://formspree.io/f/xeoaljkl" method="POST" id="contactForm">
    <div class="input-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required>
    </div>

    <div class="input-group">
        <label for="contact">Contact Number</label>
        <input type="tel" id="contact" name="contact" required>
    </div>

    <div class="input-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
    </div>

    <div class="input-group">
        <label for="concern">Concern</label>
        <select id="concern" name="concern" required>
            <option value="" disabled selected>Select a concern</option>
            <option value="Product Inquiry">Product Inquiry</option>
            <option value="Order Status">Order Status</option>
            <option value="Customization Request">Customization Request</option>
            <option value="Warranty & Repairs">Warranty & Repairs</option>
            <option value="Other">Other</option>
        </select>
    </div>

    <div class="input-group">
        <label for="message">Message</label>
        <textarea id="message" name="message" rows="5" required></textarea>
    </div>

    <button type="submit" class="submit-btn">Send Message</button>
</form>

        <!--AJAX SUBMISSION-->
        <script>
    document.querySelector("#contactForm").addEventListener("submit", async function(event) {
        event.preventDefault(); // Prevent page reload

        const form = event.target;
        const formData = new FormData(form);

        const response = await fetch(form.action, {
            method: form.method,
            body: formData,
            headers: { 'Accept': 'application/json' }
        });

        if (response.ok) {
            alert("Your message has been sent successfully!");
            form.reset(); // Clear form fields
        } else {
            alert("Oops! Something went wrong.");
        }
    });
</script>


    </div>

    <!-- Google Maps -->
    <div class="contact-map">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509349!2d144.95592831531682!3d-37.81720997975171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad65d5dfb1d0f1d%3A0x5045675218cce6e2!2sLuxury%20Watch%20Store!5e0!3m2!1sen!2sus!4v1617325251269!5m2!1sen!2sus"
            width="100%"
            height="550"
            style="border:0;"
            allowfullscreen=""
            loading="lazy">
        </iframe>
    </div>
</section>

    <section class="faq-section">
        <div class="faq-container">
            <h2>Frequently Asked Questions</h2>

            <div class="faq-item">
                <button class="faq-question">
                    <span>How can I contact Chronos Atelier for assistance?</span>
                    <i class="fas fa-clock faq-toggle-icon"></i>
                </button>
                <div class="faq-answer">
                    <p>You can reach us via email at <a href="mailto:support@chronosatelier.com">support@chronosatelier.com</a>, call us at +1 (234) 567-8901, or fill out our contact form.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>What are your customer support hours?</span>
                    <i class="fas fa-clock faq-toggle-icon"></i>
                </button>
                <div class="faq-answer">
                    <p>Our team is available from Monday to Friday (9 AM - 7 PM) and Saturday (10 AM - 5 PM). We are closed on Sundays.</p>
                </div>
            </div>

            <h3>Product & Services</h3>

            <div class="faq-item">
                <button class="faq-question">
                    <span></i> Are all your watches authentic?</span>
                    <i class="fas fa-clock faq-toggle-icon"></i>
                </button>
                <div class="faq-answer">
                    <p>Yes, we guarantee 100% authenticity. Every watch comes with an official certificate and warranty.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span> Do you offer watch customization?</span>
                    <i class="fas fa-clock faq-toggle-icon"></i>
                </button>
                <div class="faq-answer">
                    <p>Yes, we provide customization options for select luxury timepieces. Contact us for details.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>Do your watches come with a warranty?</span>
                    <i class="fas fa-clock faq-toggle-icon"></i>
                </button>
                <div class="faq-answer">
                    <p>Yes, all watches come with a manufacturer's warranty or our store warranty for added protection.</p>
                </div>
            </div>

        </div>
    </section>
<script>
    document.querySelectorAll(".faq-question").forEach((question) => {
    question.addEventListener("click", function () {
        let answer = this.nextElementSibling;
        let icon = this.querySelector("i");

        // Close other answers
        document.querySelectorAll(".faq-answer").forEach((el) => {
            if (el !== answer) {
                el.style.maxHeight = null;
                el.style.opacity = "0";
                el.previousElementSibling.classList.remove("active");
            }
        });

        // Toggle active class
        this.classList.toggle("active");

        if (this.classList.contains("active")) {
            answer.style.maxHeight = answer.scrollHeight + "px";
            answer.style.opacity = "1";
        } else {
            answer.style.maxHeight = null;
            answer.style.opacity = "0";
        }
    });
});
</script>



    <?php include 'footer.php'; ?> <!-- Include Footer -->
</body>
</html>