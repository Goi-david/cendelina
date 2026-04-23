<?php
session_start();
require_once 'config/database.php';

// Redirect if already logged in as admin
if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('Location: admin-dashboard.php');
    exit();
}

$error = '';
$success = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) ? true : false;
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        // Check admin credentials
        $sql = "SELECT id, full_name, email, phone, password_hash, role, status FROM users WHERE email = ? AND role = 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            
            if ($admin['status'] !== 'active') {
                $error = 'Your account is inactive. Please contact the system administrator.';
            } elseif (password_verify($password, $admin['password_hash'])) {
                // Set session variables
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['full_name'] = $admin['full_name'];
                $_SESSION['email'] = $admin['email'];
                $_SESSION['role'] = $admin['role'];
                $_SESSION['is_admin'] = true;
                
                // Remember me cookie (30 days)
                if ($remember) {
                    setcookie('admin_email', $email, time() + (86400 * 30), "/");
                    setcookie('admin_remember', 'true', time() + (86400 * 30), "/");
                } else {
                    setcookie('admin_email', '', time() - 3600, "/");
                    setcookie('admin_remember', '', time() - 3600, "/");
                }
                
                // Log the login attempt
                $log_sql = "INSERT INTO audit_logs (user_id, action, table_name, ip_address, user_agent) VALUES (?, 'LOGIN', 'users', ?, ?)";
                $log_stmt = $conn->prepare($log_sql);
                $log_stmt->bind_param("iss", $admin['id'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
                $log_stmt->execute();
                $log_stmt->close();
                
                // Update last login
                $update_sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("i", $admin['id']);
                $update_stmt->execute();
                $update_stmt->close();
                
                header('Location: admin-dashboard.php');
                exit();
            } else {
                $error = 'Invalid password. Please try again.';
                
                // Log failed attempt
                $log_sql = "INSERT INTO audit_logs (user_id, action, table_name, ip_address, user_agent, old_value) VALUES (NULL, 'LOGIN_FAILED', 'users', ?, ?, ?)";
                $log_stmt = $conn->prepare($log_sql);
                $log_stmt->bind_param("sss", $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], $email);
                $log_stmt->execute();
                $log_stmt->close();
            }
        } else {
            $error = 'No admin account found with this email address.';
        }
        $stmt->close();
    }
}

// Check for remembered email
$remembered_email = $_COOKIE['admin_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Cendelina Trading Ltd | System Administrator Portal</title>
    
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
            --success: #10B981;
            --danger: #EF4444;
            --warning: #F59E0B;
            --info: #06B6D4;
            --light: #F9FAFB;
            --dark: #111827;
            --white: #ffffff;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
            
            --gradient-primary: linear-gradient(135deg, var(--primary), var(--primary-dark));
            --gradient-secondary: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            --gradient-success: linear-gradient(135deg, var(--success), #059669);
            --gradient-danger: linear-gradient(135deg, var(--danger), #DC2626);
            
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.07);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
            --shadow-xl: 0 20px 25px rgba(0,0,0,0.1);
            --shadow-2xl: 0 25px 50px rgba(0,0,0,0.25);
            
            --border-radius-sm: 0.5rem;
            --border-radius-md: 0.75rem;
            --border-radius-lg: 1rem;
            --border-radius-xl: 1.25rem;
            --border-radius-2xl: 1.5rem;
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
            position: relative;
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
        .shape6 { width: 100px; height: 100px; bottom: 40%; right: 25%; animation-delay: 12s; }
        
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
            pointer-events: none;
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

        /* Login Container */
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 10;
            padding: 20px;
        }
        
        .login-card {
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius-2xl);
            box-shadow: var(--shadow-2xl);
            overflow: hidden;
            max-width: 480px;
            width: 100%;
            transition: var(--transition-bounce);
        }
        
        .login-card:hover {
            transform: translateY(-10px);
        }
        
        /* Login Header */
        .login-header {
            background: var(--gradient-primary);
            padding: 40px 30px;
            text-align: center;
            color: var(--white);
            position: relative;
            overflow: hidden;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1), transparent 70%);
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .login-logo {
            width: 80px;
            height: 80px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            position: relative;
            z-index: 1;
            box-shadow: var(--shadow-lg);
        }
        
        .login-logo i {
            font-size: 2.5rem;
            color: var(--primary);
        }
        
        .login-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        
        .login-header p {
            opacity: 0.8;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }
        
        .security-badge-header {
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--gradient-secondary);
            padding: 5px 20px;
            border-radius: var(--border-radius-full);
            font-size: 0.7rem;
            white-space: nowrap;
            z-index: 2;
        }
        
        /* Login Body */
        .login-body {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--gray-700);
            font-size: 0.9rem;
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
            font-size: 1rem;
            transition: var(--transition-base);
        }
        
        .form-control-custom {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius-lg);
            font-size: 1rem;
            transition: var(--transition-base);
            background: var(--white);
        }
        
        .form-control-custom:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(234,88,12,0.1);
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }
        
        .checkbox-group label {
            margin: 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .forgot-link {
            color: var(--secondary);
            text-decoration: none;
            transition: var(--transition-base);
        }
        
        .forgot-link:hover {
            text-decoration: underline;
        }
        
        /* Login Button */
        .btn-login {
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
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-login:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(234,88,12,0.4);
        }
        
        /* Security Badges */
        .security-badge {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--gray-200);
        }
        
        .security-badge i {
            color: var(--secondary);
            margin-right: 5px;
        }
        
        .security-badge span {
            font-size: 0.8rem;
            color: var(--gray-500);
        }
        
        .back-to-site {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-to-site a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: var(--transition-base);
            font-size: 0.9rem;
        }
        
        .back-to-site a:hover {
            color: var(--secondary);
        }
        
        /* Alert Messages */
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
            border-left: 4px solid var(--success);
        }
        
        .alert-error-custom {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }
        
        .alert-info-custom {
            background: #dbeafe;
            color: #1e40af;
            border-left: 4px solid var(--info);
        }
        
        /* Responsive */
        @media (max-width: 576px) {
            .login-body { padding: 30px 25px; }
            .login-header { padding: 30px 25px; }
            .login-header h2 { font-size: 1.4rem; }
            .security-badge-header { font-size: 0.6rem; white-space: normal; text-align: center; width: 90%; }
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
        <div class="shape shape6"></div>
    </div>

    <!-- Particles -->
    <div class="particles" id="particles"></div>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card" data-aos="zoom-in" data-aos-duration="800">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h2>Admin Portal</h2>
                <p>System Administrator Access Only</p>
                <div class="security-badge-header">
                    <i class="fas fa-lock"></i> Secure Login
                </div>
            </div>
            <div class="login-body">
                <!-- Alert Messages -->
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
                
                <!-- Login Form -->
                <form method="POST" action="" id="loginForm">
                    <div class="form-group">
                        <label><i class="fas fa-envelope me-1"></i> Email Address</label>
                        <div class="input-group-custom">
                            <i class="fas fa-user-shield"></i>
                            <input type="email" class="form-control-custom" name="email" placeholder="admin@cendelina.com" value="<?php echo htmlspecialchars($remembered_email); ?>" required autofocus>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-key me-1"></i> Password</label>
                        <div class="input-group-custom">
                            <i class="fas fa-lock"></i>
                            <input type="password" class="form-control-custom" name="password" id="password" placeholder="••••••••" required>
                            <button type="button" class="position-absolute end-0 top-50 translate-middle-y me-3 bg-transparent border-0" id="togglePassword" style="cursor: pointer; z-index: 5;">
                                <i class="fas fa-eye-slash text-muted"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" name="remember" <?php echo $remembered_email ? 'checked' : ''; ?>>
                            <span>Remember me</span>
                        </label>
                        <a href="forgot-password.php" class="forgot-link">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" class="btn-login" id="loginBtn">
                        <i class="fas fa-sign-in-alt me-2"></i> Login to Dashboard
                    </button>
                </form>
                
                <!-- Security Information -->
                <div class="security-badge">
                    <div class="row g-2">
                        <div class="col-6">
                            <i class="fas fa-lock"></i> <span>256-bit SSL</span>
                        </div>
                        <div class="col-6">
                            <i class="fas fa-shield-alt"></i> <span>Secure Connection</span>
                        </div>
                        <div class="col-6">
                            <i class="fas fa-history"></i> <span>All activities logged</span>
                        </div>
                        <div class="col-6">
                            <i class="fas fa-fingerprint"></i> <span>2FA Ready</span>
                        </div>
                    </div>
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
            for (let i = 0; i < 60; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                const size = Math.random() * 6 + 2;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 15 + 's';
                particle.style.animationDuration = Math.random() * 12 + 8 + 's';
                particlesContainer.appendChild(particle);
            }
        }
        createParticles();
        
        // Toggle Password Visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
        
        // Floating animation for login card
        const loginCard = document.querySelector('.login-card');
        let mouseX = 0, mouseY = 0;
        let cardX = 0, cardY = 0;
        
        document.addEventListener('mousemove', (e) => {
            mouseX = e.clientX / window.innerWidth - 0.5;
            mouseY = e.clientY / window.innerHeight - 0.5;
            cardX = mouseX * 10;
            cardY = mouseY * 10;
            loginCard.style.transform = `perspective(1000px) rotateY(${cardX}deg) rotateX(${-cardY}deg) translateY(-10px)`;
        });
        
        document.addEventListener('mouseleave', () => {
            loginCard.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg) translateY(0px)';
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert-custom.show');
            alerts.forEach(alert => {
                setTimeout(() => alert.classList.remove('show'), 5000);
            });
        }, 1000);
        
        // Form validation
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        
        loginForm.addEventListener('submit', function(e) {
            const email = document.querySelector('input[name="email"]').value;
            const password = document.querySelector('input[name="password"]').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in both email and password.');
            }
        });
        
        // Add loading state to button
        loginForm.addEventListener('submit', function() {
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Logging in...';
            loginBtn.disabled = true;
        });
    </script>
</body>
</html>