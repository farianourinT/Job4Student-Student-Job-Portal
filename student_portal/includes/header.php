<?php
require_once __DIR__ . '/../config/config.php';
$current_page = basename($_SERVER['PHP_SELF'] ?? 'index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Job4Student</title>
    <link rel="stylesheet" href="assets/css/style.css?v=login-ui2">
    <script src="assets/js/main.js?v=login-ui2" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="index.php">
                    <i class="fas fa-briefcase"></i> Job4Student
                </a>
            </div>
            <ul class="nav-menu">
                <?php if (isLoggedIn()): ?>
                    <li><a href="dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a></li>
                    
                    <?php if (hasRole('student')): ?>
                        <li><a href="jobs.php" class="<?php echo $current_page == 'jobs.php' ? 'active' : ''; ?>">
                            <i class="fas fa-briefcase"></i> Browse Jobs
                        </a></li>
                        <li><a href="my_applications.php" class="<?php echo $current_page == 'my_applications.php' ? 'active' : ''; ?>">
                            <i class="fas fa-calendar-check"></i> My Applications
                        </a></li>
                    <?php endif; ?>
                    
                    <?php if (hasRole('recruiter')): ?>
                        <li><a href="my_jobs.php" class="<?php echo $current_page == 'my_jobs.php' ? 'active' : ''; ?>">
                            <i class="fas fa-briefcase"></i> My Jobs
                        </a></li>
                        <li><a href="application_requests.php" class="<?php echo $current_page == 'application_requests.php' ? 'active' : ''; ?>">
                            <i class="fas fa-inbox"></i> Application Requests
                        </a></li>
                    <?php endif; ?>
                    
                    <?php if (hasRole('admin')): ?>
                        <li><a href="admin_users.php" class="<?php echo $current_page == 'admin_users.php' ? 'active' : ''; ?>">
                            <i class="fas fa-users"></i> Users
                        </a></li>
                        <li><a href="admin_jobs.php" class="<?php echo $current_page == 'admin_jobs.php' ? 'active' : ''; ?>">
                            <i class="fas fa-briefcase"></i> All Jobs
                        </a></li>
                        <li><a href="admin_applications.php" class="<?php echo $current_page == 'admin_applications.php' ? 'active' : ''; ?>">
                            <i class="fas fa-list"></i> All Applications
                        </a></li>
                    <?php endif; ?>
                    
                    <li><a href="profile.php" class="<?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">
                        <i class="fas fa-user"></i> Profile
                    </a></li>
                    <li><a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a></li>
                <?php else: ?>
                    <li><a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                        <i class="fas fa-briefcase"></i> Home
                    </a></li>
                    <li><a href="jobs.php" class="<?php echo $current_page == 'jobs.php' ? 'active' : ''; ?>">
                        <i class="fas fa-briefcase"></i> Jobs
                    </a></li>
                    <li><a href="login.php" class="<?php echo $current_page == 'login.php' ? 'active' : ''; ?>">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a></li>
                <?php endif; ?>
            </ul>
            <div class="nav-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

