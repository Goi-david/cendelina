<?php
session_start();
require_once 'config/database.php';

$error = '';
$success = '';
$full_name = $email = $phone = $address = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'customer';
    
    // Validation
    if (empty($full_name) || empty($email) || empty($phone) || empty($password)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Check if email already exists
        $check_sql = "SELECT id FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = 'Email already registered. Please login or use a different email.';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user
            $insert_sql = "INSERT INTO users (full_name, email, phone, address, password_hash, role, status, created_at) 
                           VALUES (?, ?, ?, ?, ?, ?, 'active', NOW())";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ssssss", $full_name, $email, $phone, $address, $hashed_password, $role);
            
            if ($insert_stmt->execute()) {
                $success = 'Registration successful! You can now login.';
                // Clear form
                $full_name = $email = $phone = $address = '';
                
                // Redirect after 2 seconds
                echo '<meta http-equiv="refresh" content="2;url=login.php">';
            } else {
                $error = 'Registration failed. Please try again.';
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Cendelina Trading Ltd | Create Your Account</title>
    
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
        body { 
            font-family: 'Space Grotesk', sans-serif; 
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        .bg-animation .shape {
            position: absolute;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            animation: float 20s infinite ease-in-out;
        }
        .shape1 { width: 300px; height: 300px; top: -100px; left: -100px; animation-delay: 0s; }
        .shape2 { width: 500px; height: 500px; bottom: -200px; right: -200px; animation-delay: 5s; }
        .shape3 { width: 200px; height: 200px; top: 50%; left: 50%; animation-delay: 10s; }
        .shape4 { width: 150px; height: 150px; bottom: 20%; left: 10%; animation-delay: 3s; }
        .shape5 { width: 250px; height: 250px; top: 20%; right: 10%; animation-delay: 7s; }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(50px, -50px) rotate(90deg); }
            50% { transform: translate(100px, 0) rotate(180deg); }
            75% { transform: translate(50px, 50px) rotate(270deg); }
        }

        /* Particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        .particle {
            position: absolute;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            pointer-events: none;
            animation: particle-float 10s infinite;
        }
        @keyframes particle-float {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
        }

        /* Register Container */
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 10;
            padding: 100px 20px 60px;
        }
        .register-card {
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-2xl);
            overflow: hidden;
            max-width: 550px;
            width: 100%;
            transition: var(--transition-bounce);
        }
        .register-card:hover {
            transform: translateY(-10px);
        }
        .register-header {
            background: var(--gradient-primary);
            padding: 35px 30px;
            text-align: center;
            color: var(--white);
        }
        .register-logo {
            width: 70px;
            height: 70px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }
        .register-logo i {
            font-size: 2rem;
            color: var(--primary);
        }
        .register-header h2 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .register-header p {
            opacity: 0.8;
            font-size: 0.85rem;
        }
        .register-body {
            padding: 35px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--gray-700);
            font-size: 0.9rem;
        }
        .required {
            color: var(--secondary);
        }
        .input-group-custom {
            position: relative;
        }
        .input-group-custom i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-500);
            font-size: 0.9rem;
        }
        .form-control-custom {
            width: 100%;
            padding: 12px 15px 12px 42px;
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius-lg);
            font-size: 0.95rem;
            transition: var(--transition-base);
        }
        .form-control-custom:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(234,88,12,0.1);
        }
        textarea.form-control-custom {
            padding-left: 42px;
            resize: vertical;
            min-height: 80px;
        }
        .password-strength {
            margin-top: 8px;
            font-size: 0.75rem;
        }
        .strength-bar {
            height: 4px;
            background: var(--gray-200);
            border-radius: 2px;
            margin-top: 5px;
            width: 100%;
        }
        .strength-bar-fill {
            height: 100%;
            border-radius: 2px;
            width: 0%;
            transition: width 0.3s ease;
        }
        .btn-register {
            width: 100%;
            padding: 14px;
            background: var(--gradient-secondary);
            color: var(--white);
            border: none;
            border-radius: var(--border-radius-lg);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition-bounce);
        }
        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(234,88,12,0.4);
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--gray-200);
        }
        .login-link a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 500;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .alert-custom {
            padding: 12px 18px;
            border-radius: var(--border-radius-lg);
            margin-bottom: 20px;
            display: none;
            font-size: 0.9rem;
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
        .terms {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
            font-size: 0.85rem;
        }
        .terms input {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        .terms label {
            margin: 0;
            cursor: pointer;
        }
        .terms a {
            color: var(--secondary);
            text-decoration: none;
        }
        .back-to-site {
            text-align: center;
            margin-top: 20px;
        }
        .back-to-site a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: var(--transition-base);
        }
        .back-to-site a:hover {
            color: var(--secondary);
        }
        @media (max-width: 576px) {
            .register-body { padding: 25px 20px; }
            .register-header { padding: 25px 20px; }
        }
    </style>
</head>
<body>

    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="shape shape1"></div>
        <div class="shape shape2"></div>
        <div class="shape shape3"></div>
        <div class="shape shape4"></div>
        <div class="shape shape5"></div>
    </div>

    <!-- Particles -->
    <div class="particles" id="particles"></div>

    <!-- Register Container -->
    <div class="register-container">
        <div class="register-card" data-aos="zoom-in" data-aos-duration="800">
            <div class="register-header">
                <div class="register-logo">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2>Create an Account</h2>
                <p>Join Cendelina Trading Ltd and start ordering with ease</p>
            </div>
            <div class="register-body">
                <?php if ($error): ?>
                <div class="alert-custom alert-error-custom show" id="errorAlert">
                    <i class="fas fa-exclamation-triangle me-2"></i> <?php echo $error; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                <div class="alert-custom alert-success-custom show" id="successAlert">
                    <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="" id="registerForm">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <div class="input-group-custom">
                            <i class="fas fa-user"></i>
                            <input type="text" class="form-control-custom" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Email Address <span class="required">*</span></label>
                        <div class="input-group-custom">
                            <i class="fas fa-envelope"></i>
                            <input type="email" class="form-control-custom" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Phone Number <span class="required">*</span></label>
                        <div class="input-group-custom">
                            <i class="fas fa-phone"></i>
                            <input type="tel" class="form-control-custom" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Address</label>
                        <div class="input-group-custom">
                            <i class="fas fa-map-marker-alt"></i>
                            <textarea class="form-control-custom" name="address" rows="2"><?php echo htmlspecialchars($address); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Password <span class="required">*</span></label>
                        <div class="input-group-custom">
                            <i class="fas fa-lock"></i>
                            <input type="password" class="form-control-custom" name="password" id="password" required>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-bar-fill" id="strengthFill"></div>
                            </div>
                            <span id="strengthText" style="color: var(--gray-600);"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Confirm Password <span class="required">*</span></label>
                        <div class="input-group-custom">
                            <i class="fas fa-check-circle"></i>
                            <input type="password" class="form-control-custom" name="confirm_password" id="confirmPassword" required>
                        </div>
                        <div id="passwordMatch" style="font-size: 0.75rem; margin-top: 5px;"></div>
                    </div>
                    
                    <div class="form-group">
                        <label>Account Type</label>
                        <div class="input-group-custom">
                            <i class="fas fa-user-tag"></i>
                            <select class="form-control-custom" name="role" style="padding-left: 42px;">
                                <option value="customer">Customer</option>
                                <option value="wholesaler">Wholesaler/Business</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="terms">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
                    </div>
                    
                    <button type="submit" class="btn-register">
                        <i class="fas fa-user-plus me-2"></i> Create Account
                    </button>
                </form>
                
                <div class="login-link">
                    Already have an account? <a href="login.php">Login here</a>
                </div>
            </div>
        </div>
        <div class="back-to-site">
            <a href="index.php"><i class="fas fa-arrow-left me-2"></i> Back to Cendelina Trading Ltd</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, once: true });

        // Create particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                const size = Math.random() * 5 + 2;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 10 + 's';
                particle.style.animationDuration = Math.random() * 10 + 8 + 's';
                particlesContainer.appendChild(particle);
            }
        }
        createParticles();

        // Password strength checker
        const passwordInput = document.getElementById('password');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            const percentage = (strength / 5) * 100;
            strengthFill.style.width = percentage + '%';
            
            if (percentage < 20) {
                strengthFill.style.background = '#dc2626';
                strengthText.textContent = 'Weak';
                strengthText.style.color = '#dc2626';
            } else if (percentage < 40) {
                strengthFill.style.background = '#f59e0b';
                strengthText.textContent = 'Fair';
                strengthText.style.color = '#f59e0b';
            } else if (percentage < 60) {
                strengthFill.style.background = '#fbbf24';
                strengthText.textContent = 'Good';
                strengthText.style.color = '#fbbf24';
            } else if (percentage < 80) {
                strengthFill.style.background = '#10b981';
                strengthText.textContent = 'Strong';
                strengthText.style.color = '#10b981';
            } else {
                strengthFill.style.background = '#059669';
                strengthText.textContent = 'Very Strong';
                strengthText.style.color = '#059669';
            }
        });
        
        // Password match checker
        const confirmPassword = document.getElementById('confirmPassword');
        const passwordMatch = document.getElementById('passwordMatch');
        
        function checkPasswordMatch() {
            if (confirmPassword.value.length > 0) {
                if (passwordInput.value === confirmPassword.value) {
                    passwordMatch.innerHTML = '<i class="fas fa-check-circle" style="color: #10b981;"></i> Passwords match';
                    passwordMatch.style.color = '#10b981';
                } else {
                    passwordMatch.innerHTML = '<i class="fas fa-exclamation-circle" style="color: #dc2626;"></i> Passwords do not match';
                    passwordMatch.style.color = '#dc2626';
                }
            } else {
                passwordMatch.innerHTML = '';
            }
        }
        
        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmPassword.addEventListener('input', checkPasswordMatch);
        
        // Form validation
        const registerForm = document.getElementById('registerForm');
        const termsCheckbox = document.getElementById('terms');
        
        registerForm.addEventListener('submit', function(e) {
            if (!termsCheckbox.checked) {
                e.preventDefault();
                alert('Please agree to the Terms of Service and Privacy Policy');
            }
        });
        
        // Auto-hide alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert-custom.show');
            alerts.forEach(alert => {
                setTimeout(() => alert.classList.remove('show'), 5000);
            });
        }, 1000);
        
        // Floating animation for card
        const registerCard = document.querySelector('.register-card');
        let mouseX = 0, mouseY = 0;
        
        document.addEventListener('mousemove', (e) => {
            mouseX = e.clientX / window.innerWidth - 0.5;
            mouseY = e.clientY / window.innerHeight - 0.5;
            const cardX = mouseX * 8;
            const cardY = mouseY * 8;
            registerCard.style.transform = `perspective(1000px) rotateY(${cardX}deg) rotateX(${-cardY}deg) translateY(-10px)`;
        });
        
        document.addEventListener('mouseleave', () => {
            registerCard.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg) translateY(0px)';
        });
    </script>
</body>
</html>