<?php
session_start();
$success = false;
$error = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $subject = htmlspecialchars($_POST['subject'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');
    
    if (!empty($name) && !empty($email) && !empty($message)) {
        // In production, you would send an email here
        $success = true;
    } else {
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Cendelina Trading Ltd | Get in Touch with Our Team</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    
    <style>
        :root {
            --primary: #1E3A8A;
            --primary-light: #2563EB;
            --primary-dark: #0F1F4A;
            --secondary: #EA580C;
            --secondary-light: #F97316;
            --secondary-dark: #C2410C;
            --light: #F9FAFB;
            --dark: #111827;
            --white: #ffffff;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
            
            --gradient-primary: linear-gradient(135deg, var(--primary), var(--primary-dark));
            --gradient-secondary: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
            --shadow-xl: 0 20px 25px rgba(0,0,0,0.1);
            --shadow-2xl: 0 25px 50px rgba(0,0,0,0.25);
            
            --border-radius-lg: 1rem;
            --border-radius-xl: 1.5rem;
            --border-radius-full: 9999px;
            
            --transition-base: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-bounce: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Space Grotesk', sans-serif; overflow-x: hidden; background: var(--light); }

        /* Navbar */
        .navbar-modern {
            background: transparent;
            padding: 1rem 0;
            transition: var(--transition-base);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 9999;
        }
        .navbar-modern.scrolled {
            background: rgba(30,58,138,0.95);
            backdrop-filter: blur(20px);
            padding: 0.5rem 0;
            box-shadow: var(--shadow-lg);
        }
        .navbar-brand-modern {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .brand-logo {
            width: 50px;
            height: 50px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--primary);
            font-weight: bold;
        }
        .brand-text h2 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--white);
            margin: 0;
        }
        .brand-text p {
            color: rgba(255,255,255,0.8);
            font-size: 0.7rem;
            margin: 0;
            letter-spacing: 1px;
        }
        .nav-link-modern {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: var(--transition-base);
            position: relative;
        }
        .nav-link-modern::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--secondary);
            transition: var(--transition-base);
            transform: translateX(-50%);
        }
        .nav-link-modern:hover::after, .nav-link-modern.active::after {
            width: 80%;
        }
        .quote-btn {
            background: var(--gradient-secondary);
            color: var(--white) !important;
            border-radius: var(--border-radius-full);
            padding: 0.5rem 1.5rem !important;
            transition: var(--transition-bounce);
        }
        .quote-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(234,88,12,0.4);
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 180px 0 100px;
            color: var(--white);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1), transparent 70%);
            border-radius: 50%;
            animation: rotate 30s linear infinite;
        }
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .page-header h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
        }
        .page-header p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Contact Info Cards */
        .contact-info-section {
            padding: 60px 0 0;
        }
        .contact-card {
            background: var(--white);
            padding: 40px 30px;
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-lg);
            text-align: center;
            transition: var(--transition-bounce);
            height: 100%;
            border: 1px solid var(--gray-200);
        }
        .contact-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-2xl);
            border-color: var(--secondary);
        }
        .contact-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }
        .contact-icon i {
            font-size: 2.5rem;
            color: var(--white);
        }
        .contact-card h3 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: var(--primary);
        }
        .contact-card p {
            color: var(--gray-600);
            margin-bottom: 5px;
        }
        .contact-card a {
            color: var(--gray-600);
            text-decoration: none;
            transition: var(--transition-base);
        }
        .contact-card a:hover {
            color: var(--secondary);
        }

        /* Contact Form Section */
        .contact-form-section {
            padding: 60px 0 80px;
        }
        .form-card {
            background: var(--white);
            padding: 50px;
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-xl);
        }
        .form-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }
        .form-subtitle {
            color: var(--gray-600);
            margin-bottom: 30px;
        }
        .form-control-custom {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius-lg);
            font-size: 1rem;
            transition: var(--transition-base);
        }
        .form-control-custom:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(234,88,12,0.1);
        }
        textarea.form-control-custom {
            resize: vertical;
            min-height: 120px;
        }
        .submit-btn {
            background: var(--gradient-secondary);
            color: var(--white);
            padding: 14px 40px;
            border: none;
            border-radius: var(--border-radius-full);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition-bounce);
            width: 100%;
        }
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(234,88,12,0.4);
        }
        .alert-custom {
            padding: 15px 20px;
            border-radius: var(--border-radius-lg);
            margin-bottom: 25px;
            display: none;
        }
        .alert-custom.show {
            display: block;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .alert-success-custom {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        .alert-error-custom {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #dc2626;
        }

        /* Map Section */
        .map-section {
            padding: 0 0 80px;
        }
        .map-container {
            border-radius: var(--border-radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-xl);
        }
        .map-placeholder {
            background: var(--gray-200);
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: var(--gray-600);
        }
        .map-placeholder i {
            font-size: 4rem;
            color: var(--secondary);
            margin-bottom: 20px;
        }

        /* Business Hours */
        .hours-section {
            padding: 0 0 80px;
        }
        .hours-card {
            background: var(--white);
            padding: 40px;
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-lg);
            text-align: center;
        }
        .hours-card h3 {
            color: var(--primary);
            margin-bottom: 20px;
        }
        .hours-table {
            width: 100%;
            margin: 0 auto;
        }
        .hours-table tr td {
            padding: 10px;
            border-bottom: 1px solid var(--gray-200);
        }
        .hours-table tr:last-child td {
            border-bottom: none;
        }
        .hours-day {
            font-weight: 600;
            color: var(--gray-800);
        }
        .hours-time {
            color: var(--gray-600);
        }
        .closed {
            color: var(--secondary);
            font-weight: 500;
        }

        /* Footer */
        .footer-modern {
            background: var(--gray-900);
            color: var(--gray-400);
            padding: 60px 0 20px;
        }
        .footer-widget h4 {
            color: var(--white);
            font-size: 1.1rem;
            margin-bottom: 20px;
        }
        .footer-links {
            list-style: none;
            padding: 0;
        }
        .footer-links li {
            margin-bottom: 10px;
        }
        .footer-links a {
            color: var(--gray-400);
            text-decoration: none;
            transition: var(--transition-base);
        }
        .footer-links a:hover {
            color: var(--secondary);
            padding-left: 5px;
        }
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        .social-link {
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            transition: var(--transition-bounce);
        }
        .social-link:hover {
            background: var(--secondary);
            transform: translateY(-3px);
        }
        .footer-bottom {
            text-align: center;
            padding-top: 40px;
            margin-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        /* Back to Top */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--gradient-primary);
            color: var(--white);
            border: none;
            border-radius: var(--border-radius-full);
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            z-index: 9999;
            box-shadow: var(--shadow-xl);
            transition: var(--transition-bounce);
        }
        .back-to-top:hover {
            transform: translateY(-5px) scale(1.1);
            background: var(--gradient-secondary);
        }
        .back-to-top.visible { display: flex; }

        @media (max-width: 992px) {
            .page-header h1 { font-size: 2.5rem; }
            .form-card { padding: 30px; }
        }
        @media (max-width: 768px) {
            .page-header h1 { font-size: 2rem; }
            .contact-card { margin-bottom: 20px; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-modern" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <div class="navbar-brand-modern">
                    <div class="brand-logo"><i class="fas fa-ship"></i></div>
                    <div class="brand-text">
                        <h2>Cendelina Trading Ltd</h2>
                        <p>LOGISTICS & IMPORT SOLUTIONS</p>
                    </div>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" style="background: white;">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link nav-link-modern" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-modern" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-modern" href="products.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-modern active" href="contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-modern" href="admin-login.php">Admin Login</a></li>
                    <li class="nav-item"><a class="nav-link quote-btn" href="#">Get Quote <i class="fas fa-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 data-aos="fade-up">Contact Us</h1>
            <p data-aos="fade-up" data-aos-delay="100">We're here to help. Reach out to our team for inquiries, quotes, or support.</p>
        </div>
    </section>

    <!-- Contact Info Cards -->
    <section class="contact-info-section">
        <div class="container">
            <div class="row g-4">
                <!-- Phone Card 1 -->
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <h3>Phone Number 1</h3>
                        <p><a href="tel:+211919427114">+211 919 427 114</a></p>
                        <p class="small text-muted">Available 24/7 for emergencies</p>
                    </div>
                </div>
                
                <!-- Phone Card 2 -->
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>Phone Number 2</h3>
                        <p><a href="tel:+211921674814">+211 921 674 814</a></p>
                        <p class="small text-muted">WhatsApp Available</p>
                    </div>
                </div>
                
                <!-- Email Card -->
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>Email Address</h3>
                        <p><a href="mailto:goidavid2000@gmail.com">goidavid2000@gmail.com</a></p>
                        <p class="small text-muted">We reply within 24 hours</p>
                    </div>
                </div>
                
                <!-- Location Card -->
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>Our Location</h3>
                        <p>Jebel, Juba, South Sudan</p>
                        <p class="small text-muted">Opposite Jebel Market</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-form-section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="form-card">
                        <h2 class="form-title">Send Us a Message</h2>
                        <p class="form-subtitle">Fill out the form below and we'll get back to you as soon as possible.</p>
                        
                        <?php if ($success): ?>
                        <div class="alert-custom alert-success-custom show" id="successAlert">
                            <i class="fas fa-check-circle me-2"></i> Thank you for your message! We'll get back to you shortly.
                        </div>
                        <script>
                            setTimeout(() => {
                                document.getElementById('successAlert')?.classList.remove('show');
                            }, 5000);
                        </script>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                        <div class="alert-custom alert-error-custom show" id="errorAlert">
                            <i class="fas fa-exclamation-triangle me-2"></i> Please fill in all required fields.
                        </div>
                        <script>
                            setTimeout(() => {
                                document.getElementById('errorAlert')?.classList.remove('show');
                            }, 5000);
                        </script>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control-custom" name="name" placeholder="Your Full Name *" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" class="form-control-custom" name="email" placeholder="Email Address *" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="tel" class="form-control-custom" name="phone" placeholder="Phone Number">
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control-custom" name="subject">
                                        <option value="">Select Subject *</option>
                                        <option value="general">General Inquiry</option>
                                        <option value="quote">Request a Quote</option>
                                        <option value="products">Product Information</option>
                                        <option value="support">Customer Support</option>
                                        <option value="partnership">Partnership Opportunity</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <textarea class="form-control-custom" name="message" placeholder="Your Message *" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="submit-btn">
                                        <i class="fas fa-paper-plane me-2"></i> Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="col-lg-5" data-aos="fade-left">
                    <div class="hours-card">
                        <i class="fas fa-clock fa-3x" style="color: var(--secondary); margin-bottom: 20px;"></i>
                        <h3>Business Hours</h3>
                        <table class="hours-table">
                            <tr>
                                <td class="hours-day">Monday - Friday</td>
                                <td class="hours-time">8:00 AM - 5:00 PM</td>
                            </tr>
                            <tr>
                                <td class="hours-day">Saturday</td>
                                <td class="hours-time">9:00 AM - 1:00 PM</td>
                            </tr>
                            <tr>
                                <td class="hours-day">Sunday</td>
                                <td class="hours-time closed">Closed</td>
                            </tr>
                            <tr>
                                <td class="hours-day">Public Holidays</td>
                                <td class="hours-time closed">Closed</td>
                            </tr>
                        </table>
                        <hr class="my-4">
                        <h4 style="color: var(--primary); font-size: 1.1rem;">Emergency Support</h4>
                        <p class="text-muted">For urgent matters outside business hours, please call:</p>
                        <p><strong><a href="tel:+211919427114" style="color: var(--secondary);">+211 919 427 114</a></strong></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <div class="map-container" data-aos="zoom-in">
                <div class="map-placeholder">
                    <i class="fas fa-map-marked-alt"></i>
                    <h4>Our Location - Jebel, Juba</h4>
                    <p>Jebel Road, Opposite Jebel Market, Juba, South Sudan</p>
                    <small class="text-muted">(Interactive map will be added here)</small>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Response Note -->
    <section class="contact-form-section" style="padding-top: 0;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center" data-aos="fade-up">
                    <div class="contact-card" style="background: var(--gradient-primary); color: var(--white);">
                        <i class="fas fa-rocket fa-3x mb-3"></i>
                        <h3 style="color: var(--white);">Fast Response Guaranteed</h3>
                        <p>We strive to respond to all inquiries within 24 hours. For urgent matters, please call us directly.</p>
                        <div class="mt-3">
                            <a href="tel:+211919427114" class="btn" style="background: var(--white); color: var(--primary); margin: 5px;">
                                <i class="fas fa-phone"></i> Call Now
                            </a>
                            <a href="https://wa.me/211921674814" class="btn" style="background: #25D366; color: white; margin: 5px;">
                                <i class="fab fa-whatsapp"></i> WhatsApp Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-modern">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up">
                    <div class="footer-widget">
                        <h4>Cendelina Trading Ltd</h4>
                        <p>Logistics & Import Solutions<br>Your trusted partner for global sourcing.</p>
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="footer-widget">
                        <h4>Quick Links</h4>
                        <ul class="footer-links">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="about.php">About Us</a></li>
                            <li><a href="products.php">Products</a></li>
                            <li><a href="contact.php">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="footer-widget">
                        <h4>Categories</h4>
                        <ul class="footer-links">
                            <li><a href="products.php?cat=technology">Technology</a></li>
                            <li><a href="products.php?cat=building">Building Materials</a></li>
                            <li><a href="products.php?cat=cars">Cars & Spares</a></li>
                            <li><a href="products.php?cat=office">Office Essentials</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="footer-widget">
                        <h4>Contact</h4>
                        <ul class="footer-links">
                            <li><i class="fas fa-phone me-2"></i> <a href="tel:+211919427114">+211 919 427 114</a></li>
                            <li><i class="fas fa-phone me-2"></i> <a href="tel:+211921674814">+211 921 674 814</a></li>
                            <li><i class="fas fa-envelope me-2"></i> <a href="mailto:goidavid2000@gmail.com">goidavid2000@gmail.com</a></li>
                            <li><i class="fas fa-map-marker-alt me-2"></i> Jebel, Juba, South Sudan</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Cendelina Trading Ltd. All rights reserved. | Established 2017 | System admin portal (restricted access)</p>
            </div>
        </div>
    </footer>

    <button class="back-to-top" id="backToTop"><i class="fas fa-arrow-up"></i></button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, once: true, offset: 100 });
        
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 50) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
            
            const backToTop = document.getElementById('backToTop');
            if (window.pageYOffset > 300) backToTop.classList.add('visible');
            else backToTop.classList.remove('visible');
        });
        
        document.getElementById('backToTop').addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    </script>
</body>
</html>