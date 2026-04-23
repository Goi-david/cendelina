<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin-login.php');
    exit();
}

// Get admin info
$admin_name = $_SESSION['full_name'];
$admin_email = $_SESSION['email'];

// Get statistics
$stats = [];

// Total users
$result = $conn->query("SELECT COUNT(*) as count FROM users");
$stats['total_users'] = $result->fetch_assoc()['count'];

// Total products
$result = $conn->query("SELECT COUNT(*) as count FROM products");
$stats['total_products'] = $result->fetch_assoc()['count'];

// Total orders
$result = $conn->query("SELECT COUNT(*) as count FROM orders");
$stats['total_orders'] = $result->fetch_assoc()['count'];

// Total vendors
$result = $conn->query("SELECT COUNT(*) as count FROM vendors");
$stats['total_vendors'] = $result->fetch_assoc()['count'];

// Low stock products
$result = $conn->query("SELECT COUNT(*) as count FROM products WHERE current_stock <= min_stock_level AND status = 'active'");
$stats['low_stock'] = $result->fetch_assoc()['count'];

// Pending orders
$result = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
$stats['pending_orders'] = $result->fetch_assoc()['count'];

// Recent orders
$recent_orders = $conn->query("SELECT o.*, u.full_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");

// Recent audit logs
$recent_logs = $conn->query("SELECT l.*, u.full_name FROM audit_logs l LEFT JOIN users u ON l.user_id = u.id ORDER BY l.created_at DESC LIMIT 10");

// Recent users
$recent_users = $conn->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");

// Get current month sales
$result = $conn->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE()) AND status = 'delivered'");
$stats['monthly_sales'] = $result->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cendelina Trading Ltd</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
            --gradient-warning: linear-gradient(135deg, var(--warning), #D97706);
            --gradient-info: linear-gradient(135deg, var(--info), #0891B2);
            
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.07);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
            --shadow-xl: 0 20px 25px rgba(0,0,0,0.1);
            
            --border-radius-sm: 0.5rem;
            --border-radius-md: 0.75rem;
            --border-radius-lg: 1rem;
            --border-radius-xl: 1.25rem;
            --border-radius-2xl: 1.5rem;
            
            --transition-base: all 0.3s ease;
            --transition-bounce: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Space Grotesk', sans-serif;
            background: var(--gray-100);
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100%;
            background: var(--gradient-primary);
            color: var(--white);
            transition: var(--transition-base);
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: var(--secondary);
            border-radius: 10px;
        }
        
        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar-logo {
            width: 60px;
            height: 60px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }
        
        .sidebar-logo i {
            font-size: 2rem;
            color: var(--primary);
        }
        
        .sidebar-header h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .sidebar-header p {
            font-size: 0.75rem;
            opacity: 0.7;
        }
        
        .sidebar-nav {
            padding: 20px 0;
        }
        
        .nav-item {
            list-style: none;
            margin-bottom: 5px;
        }
        
        .nav-link-dashboard {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: var(--transition-base);
            gap: 12px;
        }
        
        .nav-link-dashboard i {
            width: 22px;
            font-size: 1.1rem;
        }
        
        .nav-link-dashboard:hover,
        .nav-link-dashboard.active {
            background: rgba(255,255,255,0.1);
            color: var(--white);
            border-left: 3px solid var(--secondary);
        }
        
        .nav-link-dashboard:hover i,
        .nav-link-dashboard.active i {
            color: var(--secondary);
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 20px 30px;
        }
        
        /* Top Bar */
        .top-bar {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 15px 25px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-sm);
        }
        
        .page-title h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }
        
        .page-title p {
            font-size: 0.85rem;
            color: var(--gray-500);
            margin: 0;
        }
        
        .admin-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .admin-avatar {
            width: 45px;
            height: 45px;
            background: var(--gradient-secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .admin-details {
            text-align: right;
        }
        
        .admin-details h4 {
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0;
        }
        
        .admin-details p {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin: 0;
        }
        
        .logout-btn {
            background: var(--danger);
            color: var(--white);
            border: none;
            padding: 8px 15px;
            border-radius: var(--border-radius-md);
            font-size: 0.85rem;
            transition: var(--transition-base);
        }
        
        .logout-btn:hover {
            background: #DC2626;
            transform: translateY(-2px);
        }
        
        /* Stats Cards */
        .stat-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 20px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition-bounce);
            border: 1px solid var(--gray-200);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: var(--border-radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        
        .stat-icon.primary { background: rgba(30,58,138,0.1); color: var(--primary); }
        .stat-icon.success { background: rgba(16,185,129,0.1); color: var(--success); }
        .stat-icon.warning { background: rgba(245,158,11,0.1); color: var(--warning); }
        .stat-icon.danger { background: rgba(239,68,68,0.1); color: var(--danger); }
        .stat-icon.info { background: rgba(6,182,212,0.1); color: var(--info); }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.85rem;
            color: var(--gray-500);
        }
        
        /* Section Cards */
        .section-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 20px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 25px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--gray-200);
        }
        
        .section-header h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }
        
        /* Tables */
        .table-custom {
            width: 100%;
        }
        
        .table-custom th {
            background: var(--gray-100);
            padding: 12px 15px;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--gray-600);
        }
        
        .table-custom td {
            padding: 12px 15px;
            font-size: 0.85rem;
            color: var(--gray-700);
            border-bottom: 1px solid var(--gray-200);
        }
        
        .badge-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-pending { background: #fed7aa; color: #9a3412; }
        .badge-shipped { background: #dbeafe; color: #1e40af; }
        .badge-delivered { background: #d1fae5; color: #065f46; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
        
        /* Chart Container */
        .chart-container {
            height: 300px;
            position: relative;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                left: -280px;
            }
            .sidebar.active {
                left: 0;
            }
            .main-content {
                margin-left: 0;
            }
            .menu-toggle {
                display: block;
            }
        }
        
        .menu-toggle {
            display: none;
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 10px 15px;
            border-radius: var(--border-radius-md);
            margin-right: 15px;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            padding: 20px 0;
            color: var(--gray-500);
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-ship"></i>
            </div>
            <h3>Cendelina Trading Ltd</h3>
            <p>Admin Control Panel</p>
        </div>
        
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="#" class="nav-link-dashboard active" data-page="dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link-dashboard" data-page="users">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link-dashboard" data-page="products">
                    <i class="fas fa-boxes"></i>
                    <span>Products</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link-dashboard" data-page="orders">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link-dashboard" data-page="vendors">
                    <i class="fas fa-truck"></i>
                    <span>Vendors</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link-dashboard" data-page="inventory">
                    <i class="fas fa-warehouse"></i>
                    <span>Inventory</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link-dashboard" data-page="audit">
                    <i class="fas fa-history"></i>
                    <span>Audit Logs</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link-dashboard" data-page="reports">
                    <i class="fas fa-chart-line"></i>
                    <span>Reports</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link-dashboard" data-page="settings">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div style="display: flex; align-items: center;">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="page-title">
                    <h1>Dashboard</h1>
                    <p>Welcome back, <?php echo htmlspecialchars($admin_name); ?></p>
                </div>
            </div>
            <div class="admin-info">
                <div class="admin-details">
                    <h4><?php echo htmlspecialchars($admin_name); ?></h4>
                    <p><?php echo htmlspecialchars($admin_email); ?></p>
                </div>
                <div class="admin-avatar">
                    <?php echo substr($admin_name, 0, 1); ?>
                </div>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div id="dashboardContent">
            <!-- Stats Row -->
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card">
                        <div class="stat-icon primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-value"><?php echo number_format($stats['total_users']); ?></div>
                        <div class="stat-label">Total Users</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card">
                        <div class="stat-icon success">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="stat-value"><?php echo number_format($stats['total_products']); ?></div>
                        <div class="stat-label">Total Products</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card">
                        <div class="stat-icon warning">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-value"><?php echo number_format($stats['total_orders']); ?></div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-card">
                        <div class="stat-icon info">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-value">$<?php echo number_format($stats['monthly_sales'], 2); ?></div>
                        <div class="stat-label">Monthly Sales</div>
                    </div>
                </div>
            </div>

            <!-- Second Stats Row -->
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card">
                        <div class="stat-icon primary">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="stat-value"><?php echo number_format($stats['total_vendors']); ?></div>
                        <div class="stat-label">Total Vendors</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card">
                        <div class="stat-icon danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-value"><?php echo number_format($stats['low_stock']); ?></div>
                        <div class="stat-label">Low Stock Alerts</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card">
                        <div class="stat-icon warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-value"><?php echo number_format($stats['pending_orders']); ?></div>
                        <div class="stat-label">Pending Orders</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-card">
                        <div class="stat-icon success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-value">98%</div>
                        <div class="stat-label">Satisfaction Rate</div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-4 mb-4">
                <div class="col-lg-8" data-aos="fade-right">
                    <div class="section-card">
                        <div class="section-header">
                            <h3><i class="fas fa-chart-line me-2"></i> Sales Overview</h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-left">
                    <div class="section-card">
                        <div class="section-header">
                            <h3><i class="fas fa-chart-pie me-2"></i> Category Distribution</h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="row g-4 mb-4">
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="section-card">
                        <div class="section-header">
                            <h3><i class="fas fa-shopping-cart me-2"></i> Recent Orders</h3>
                            <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <table class="table-custom">
                            <thead>
                                <tr><th>Order #</th><th>Customer</th><th>Amount</th><th>Status</th><th>Date</th></tr>
                            </thead>
                            <tbody>
                                <?php while ($order = $recent_orders->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $order['order_number']; ?></td>
                                    <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td><span class="badge-status badge-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-5" data-aos="fade-left">
                    <div class="section-card">
                        <div class="section-header">
                            <h3><i class="fas fa-users me-2"></i> Recent Users</h3>
                            <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <table class="table-custom">
                            <thead>
                                <tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                <?php while ($user = $recent_users->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><span class="badge-status badge-<?php echo $user['role']; ?>"><?php echo ucfirst($user['role']); ?></span></td>
                                    <td><span class="badge-status badge-<?php echo $user['status']; ?>"><?php echo ucfirst($user['status']); ?></span></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Audit Logs -->
            <div class="row g-4" data-aos="fade-up">
                <div class="col-12">
                    <div class="section-card">
                        <div class="section-header">
                            <h3><i class="fas fa-history me-2"></i> Recent Activity Logs</h3>
                            <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <table class="table-custom">
                            <thead>
                                <tr><th>User</th><th>Action</th><th>Table</th><th>IP Address</th><th>Time</th></tr>
                            </thead>
                            <tbody>
                                <?php while ($log = $recent_logs->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($log['full_name'] ?? 'System'); ?></td>
                                    <td><span class="badge-status" style="background: #dbeafe; color: #1e40af;"><?php echo $log['action']; ?></span></td>
                                    <td><?php echo $log['table_name'] ?? '-'; ?></td>
                                    <td><?php echo $log['ip_address'] ?? '-'; ?></td>
                                    <td><?php echo date('M d, H:i', strtotime($log['created_at'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2026 Cendelina Trading Ltd. All rights reserved. | Admin Dashboard v1.0</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
        
        // Sidebar Toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
        
        // Close sidebar on click outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992) {
                if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
        
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Sales (USD)',
                    data: [12500, 15000, 18200, 21000, 24500, 28000, 31200, 34500, 37800, 41200, 44500, 48000],
                    borderColor: '#EA580C',
                    backgroundColor: 'rgba(234,88,12,0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#EA580C',
                    pointBorderColor: '#fff',
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { callbacks: { label: (ctx) => `$${ctx.raw.toLocaleString()}` } }
                },
                scales: { y: { beginAtZero: true, ticks: { callback: (val) => `$${val.toLocaleString()}` } } }
            }
        });
        
        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Technology', 'Building Materials', 'Cars & Spares', 'Office Essentials', 'Others'],
                datasets: [{
                    data: [35, 28, 22, 10, 5],
                    backgroundColor: ['#1E3A8A', '#EA580C', '#10B981', '#F59E0B', '#6B7280'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
        
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const topBar = document.querySelector('.top-bar');
            if (window.scrollY > 10) {
                topBar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
            } else {
                topBar.style.boxShadow = '0 1px 2px rgba(0,0,0,0.05)';
            }
        });
        
        // Page navigation (simulated - in production would load content via AJAX)
        document.querySelectorAll('.nav-link-dashboard').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = link.dataset.page;
                
                // Update active state
                document.querySelectorAll('.nav-link-dashboard').forEach(l => l.classList.remove('active'));
                link.classList.add('active');
                
                // Update page title
                const pageTitle = document.querySelector('.page-title h1');
                const pageSubtitle = document.querySelector('.page-title p');
                
                const pages = {
                    dashboard: { title: 'Dashboard', subtitle: 'Welcome back, <?php echo $admin_name; ?>' },
                    users: { title: 'User Management', subtitle: 'Manage all user accounts' },
                    products: { title: 'Product Management', subtitle: 'Add, edit, and manage products' },
                    orders: { title: 'Order Management', subtitle: 'Track and manage customer orders' },
                    vendors: { title: 'Vendor Management', subtitle: 'Manage supplier information' },
                    inventory: { title: 'Inventory Control', subtitle: 'Track stock levels and movements' },
                    audit: { title: 'Audit Logs', subtitle: 'View all system activities' },
                    reports: { title: 'Reports', subtitle: 'Generate business reports' },
                    settings: { title: 'System Settings', subtitle: 'Configure system preferences' }
                };
                
                if (pages[page]) {
                    pageTitle.textContent = pages[page].title;
                    pageSubtitle.textContent = pages[page].subtitle;
                }
                
                // Show message for other pages (in production, load content)
                const dashboardContent = document.getElementById('dashboardContent');
                if (page !== 'dashboard') {
                    dashboardContent.innerHTML = `
                        <div class="section-card text-center py-5">
                            <i class="fas fa-${page === 'users' ? 'users' : page === 'products' ? 'boxes' : page === 'orders' ? 'shopping-cart' : 'cog'} fa-4x" style="color: var(--primary); margin-bottom: 20px;"></i>
                            <h3>${pages[page].title}</h3>
                            <p class="text-muted">This section is under development. Full functionality will be available soon.</p>
                            <div class="mt-4">
                                <div class="alert alert-info" style="background: #dbeafe; border: none;">
                                    <i class="fas fa-info-circle me-2"></i> 
                                    In production, this would load real data from the database with full CRUD operations.
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    location.reload();
                }
            });
        });
    </script>
</body>
</html>