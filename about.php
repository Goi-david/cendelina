<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Cendelina Trading Ltd | Premier Logistics & Import Solutions in South Sudan</title>
    
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

        /* Navbar (same as index) */
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

        /* About Content */
        .about-content {
            padding: 80px 0;
        }
        .about-card {
            background: var(--white);
            padding: 40px;
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-lg);
            height: 100%;
            transition: var(--transition-bounce);
        }
        .about-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-2xl);
        }
        .about-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
        }
        .about-icon i {
            font-size: 2.5rem;
            color: var(--white);
        }
        .about-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--primary);
        }
        .about-card p {
            color: var(--gray-600);
            line-height: 1.6;
        }

        /* Mission Vision */
        .mission-vision {
            background: var(--gradient-primary);
            color: var(--white);
            padding: 80px 0;
        }
        .mv-card {
            text-align: center;
            padding: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: var(--border-radius-xl);
            backdrop-filter: blur(10px);
            transition: var(--transition-bounce);
        }
        .mv-card:hover {
            transform: translateY(-10px);
            background: rgba(255,255,255,0.15);
        }
        .mv-card i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: var(--secondary);
        }
        .mv-card h3 {
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        /* Stats Section */
        .stats-about {
            padding: 80px 0;
            background: var(--white);
        }
        .stat-box {
            text-align: center;
            padding: 30px;
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius-xl);
            transition: var(--transition-bounce);
        }
        .stat-box:hover {
            border-color: var(--secondary);
            transform: translateY(-5px);
        }
        .stat-box .number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary);
        }
        .stat-box .label {
            color: var(--gray-600);
            margin-top: 10px;
        }

        /* Team Section */
        .team-section {
            padding: 80px 0;
            background: var(--light);
        }
        .team-card {
            background: var(--white);
            border-radius: var(--border-radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            transition: var(--transition-bounce);
            text-align: center;
        }
        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-2xl);
        }
        .team-image {
            height: 300px;
            overflow: hidden;
        }
        .team-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .team-card:hover .team-image img {
            transform: scale(1.05);
        }
        .team-info {
            padding: 25px;
        }
        .team-info h4 {
            font-size: 1.2rem;
            margin-bottom: 5px;
            color: var(--primary);
        }
        .team-info p {
            color: var(--secondary);
            font-weight: 500;
            margin-bottom: 15px;
        }
        .team-social {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .team-social a {
            width: 35px;
            height: 35px;
            background: var(--gray-100);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            transition: var(--transition-bounce);
        }
        .team-social a:hover {
            background: var(--secondary);
            color: var(--white);
            transform: translateY(-3px);
        }

        /* Location Section */
        .location-section {
            padding: 80px 0;
            background: var(--white);
        }
        .location-card {
            background: var(--gray-100);
            padding: 40px;
            border-radius: var(--border-radius-xl);
            height: 100%;
            transition: var(--transition-bounce);
        }
        .location-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        .location-card i {
            font-size: 2.5rem;
            color: var(--secondary);
            margin-bottom: 20px;
        }
        .location-card h4 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: var(--primary);
        }
        .map-placeholder {
            background: var(--gray-200);
            height: 300px;
            border-radius: var(--border-radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-600);
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
        }
        @media (max-width: 768px) {
            .page-header h1 { font-size: 2rem; }
            .about-card { margin-bottom: 20px; }
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
                    <li class="nav-item"><a class="nav-link nav-link-modern active" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-modern" href="products.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-modern" href="contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-modern" href="admin-login.php">Admin Login</a></li>
                    <li class="nav-item"><a class="nav-link quote-btn" href="#">Get Quote <i class="fas fa-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 data-aos="fade-up">About Cendelina Trading Ltd</h1>
            <p data-aos="fade-up" data-aos-delay="100">Your trusted partner in logistics and import solutions since 2017</p>
        </div>
    </section>

    <!-- Who We Are -->
    <section class="about-content">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600" alt="Warehouse" class="img-fluid rounded-4 shadow-lg">
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="about-card">
                        <div class="about-icon"><i class="fas fa-building"></i></div>
                        <h3>Who We Are</h3>
                        <p>Cendelina Trading Ltd is a premier logistics and import company headquartered in Juba, South Sudan. Since our establishment in 2017, we have grown to become one of the most trusted names in the industry, serving businesses across East Africa and beyond.</p>
                        <p class="mt-3">Our strategic location in Jebel, Juba, allows us to efficiently manage the entire supply chain — from global sourcing to local delivery. We pride ourselves on our transparency, reliability, and commitment to excellence.</p>
                        <div class="mt-4">
                            <span class="badge bg-primary me-2 p-2">✓ ISO Certified</span>
                            <span class="badge bg-primary me-2 p-2">✓ Licensed Importer</span>
                            <span class="badge bg-primary p-2">✓ Member of Juba Chamber of Commerce</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="mission-vision">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="mv-card">
                        <i class="fas fa-bullseye"></i>
                        <h3>Our Mission</h3>
                        <p>To provide seamless, transparent, and efficient logistics and import solutions that empower businesses in South Sudan and the region to thrive in a global marketplace.</p>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="mv-card">
                        <i class="fas fa-eye"></i>
                        <h3>Our Vision</h3>
                        <p>To become the leading logistics and trading partner in East Africa, recognized for our integrity, innovation, and unwavering commitment to customer success.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values -->
    <section class="about-content">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 style="color: var(--primary); font-size: 2.5rem; font-weight: 700;">Our Core Values</h2>
                <p class="text-muted">The principles that guide everything we do</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4" data-aos="flip-left" data-aos-delay="100">
                    <div class="about-card text-center">
                        <div class="about-icon mx-auto"><i class="fas fa-handshake"></i></div>
                        <h3>Integrity</h3>
                        <p>We operate with complete transparency, honesty, and ethical practices in all our dealings.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="flip-left" data-aos-delay="200">
                    <div class="about-card text-center">
                        <div class="about-icon mx-auto"><i class="fas fa-chart-line"></i></div>
                        <h3>Excellence</h3>
                        <p>We continuously improve our processes to deliver the highest quality service.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="flip-left" data-aos-delay="300">
                    <div class="about-card text-center">
                        <div class="about-icon mx-auto"><i class="fas fa-users"></i></div>
                        <h3>Partnership</h3>
                        <p>We build lasting relationships with our clients, suppliers, and community.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats-about">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-6" data-aos="zoom-in" data-aos-delay="100">
                    <div class="stat-box">
                        <div class="number">2017</div>
                        <div class="label">Year Established</div>
                    </div>
                </div>
                <div class="col-md-3 col-6" data-aos="zoom-in" data-aos-delay="200">
                    <div class="stat-box">
                        <div class="number">500+</div>
                        <div class="label">Projects Completed</div>
                    </div>
                </div>
                <div class="col-md-3 col-6" data-aos="zoom-in" data-aos-delay="300">
                    <div class="stat-box">
                        <div class="number">100%</div>
                        <div class="label">Client Satisfaction</div>
                    </div>
                </div>
                <div class="col-md-3 col-6" data-aos="zoom-in" data-aos-delay="400">
                    <div class="stat-box">
                        <div class="number">15+</div>
                        <div class="label">Global Partners</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 style="color: var(--primary); font-size: 2.5rem; font-weight: 700;">Meet Our Leadership</h2>
                <p class="text-muted">Dedicated professionals ensuring your success</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="flip-left" data-aos-delay="100">
                    <div class="team-card">
                        <div class="team-image"><img src="https://randomuser.me/api/portraits/men/32.jpg" alt="CEO"></div>
                        <div class="team-info">
                            <h4>John Deng</h4>
                            <p>CEO & Founder</p>
                            <div class="team-social">
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="flip-left" data-aos-delay="200">
                    <div class="team-card">
                        <div class="team-image"><img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Operations"></div>
                        <div class="team-info">
                            <h4>Sarah Abuk</h4>
                            <p>Operations Director</p>
                            <div class="team-social">
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="flip-left" data-aos-delay="300">
                    <div class="team-card">
                        <div class="team-image"><img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Logistics"></div>
                        <div class="team-info">
                            <h4>Michael Chan</h4>
                            <p>Logistics Manager</p>
                            <div class="team-social">
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="flip-left" data-aos-delay="400">
                    <div class="team-card">
                        <div class="team-image"><img src="https://randomuser.me/api/portraits/women/89.jpg" alt="Admin"></div>
                        <div class="team-info">
                            <h4>Grace Nyibol</h4>
                            <p>System Administrator</p>
                            <div class="team-social">
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Location Section -->
    <section class="location-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 style="color: var(--primary); font-size: 2.5rem; font-weight: 700;">Our Location</h2>
                <p class="text-muted">Visit our headquarters in Juba, South Sudan</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="location-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4>Head Office - Jebel, Juba</h4>
                        <p><strong>Address:</strong> Jebel Road, Opposite Jebel Market, Juba, South Sudan</p>
                        <p><strong>Hours:</strong> Monday - Friday: 8:00 AM - 5:00 PM<br>Saturday: 9:00 AM - 1:00 PM</p>
                        <p><strong>Phone:</strong> +211 912 345 678</p>
                        <p><strong>Email:</strong> info@cendelina.com</p>
                        <hr>
                        <h5>Why Jebel?</h5>
                        <p>Jebel is a strategic commercial hub in Juba, providing easy access to major transportation routes, customs clearance points, and our warehouse facilities.</p>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="map-placeholder">
                        <div class="text-center">
                            <i class="fas fa-map fa-3x mb-3" style="color: var(--secondary);"></i>
                            <p>Interactive Map<br>Jebel, Juba, South Sudan</p>
                            <small class="text-muted">(Google Maps integration will be added)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Badges -->
    <section class="about-content" style="padding-top: 0;">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="about-card text-center">
                        <i class="fas fa-shield-alt fa-3x" style="color: var(--secondary); margin-bottom: 20px;"></i>
                        <h3>Fully Licensed</h3>
                        <p>Registered and licensed by the Government of South Sudan for import/export operations.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="about-card text-center">
                        <i class="fas fa-file-invoice fa-3x" style="color: var(--secondary); margin-bottom: 20px;"></i>
                        <h3>Customs Bonded</h3>
                        <p>Authorized customs clearing agent with direct access to the Juba port.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="about-card text-center">
                        <i class="fas fa-truck fa-3x" style="color: var(--secondary); margin-bottom: 20px;"></i>
                        <h3>Own Fleet</h3>
                        <p>Dedicated fleet of trucks for local delivery and distribution.</p>
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
                            <li><i class="fas fa-envelope me-2"></i> info@cendelina.com</li>
                            <li><i class="fas fa-phone me-2"></i> +211 912 345 678</li>
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