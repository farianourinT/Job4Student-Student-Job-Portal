<?php
require_once __DIR__ . '/config/config.php';
requireLogin();

$page_title = "Dashboard";
$user_role = getCurrentUserRole();
$conn = getDBConnection();

$stats = [];

if ($user_role == 'student') {
    $user_id = (int)$_SESSION['user_id'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM applications WHERE student_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['total_applications'] = $result->fetch_assoc()['total'];
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM applications WHERE student_id = ? AND status = 'pending'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['pending_applications'] = $result->fetch_assoc()['total'];
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM applications WHERE student_id = ? AND status = 'approved'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['approved_applications'] = $result->fetch_assoc()['total'];
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM applications WHERE student_id = ? AND status = 'confirmed'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['confirmed_applications'] = $result->fetch_assoc()['total'];
    $stmt->close();
    
} elseif ($user_role == 'recruiter') {
    $user_id = (int)$_SESSION['user_id'];
    
    // Total jobs
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM jobs WHERE recruiter_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['total_jobs'] = $result->fetch_assoc()['total'];
    $stmt->close();
    
    // Open jobs
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM jobs WHERE recruiter_id = ? AND status = 'open'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['open_jobs'] = $result->fetch_assoc()['total'];
    $stmt->close();
    
    // Total applications
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM applications WHERE recruiter_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['total_applications'] = $result->fetch_assoc()['total'];
    $stmt->close();
    
    // Pending requests
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM applications WHERE recruiter_id = ? AND status = 'pending'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['pending_requests'] = $result->fetch_assoc()['total'];
    $stmt->close();
    
} elseif ($user_role == 'admin') {
    // Total users
    $result = $conn->query("SELECT COUNT(*) as total FROM users");
    $stats['total_users'] = $result->fetch_assoc()['total'];
    
    // Total jobs
    $result = $conn->query("SELECT COUNT(*) as total FROM jobs");
    $stats['total_jobs'] = $result->fetch_assoc()['total'];
    
    // Total applications
    $result = $conn->query("SELECT COUNT(*) as total FROM applications");
    $stats['total_applications'] = $result->fetch_assoc()['total'];
    
    // Pending applications
    $result = $conn->query("SELECT COUNT(*) as total FROM applications WHERE status = 'pending'");
    $stats['pending_applications'] = $result->fetch_assoc()['total'];
}

closeDBConnection($conn);

include 'includes/header.php';
?>
<main class="main-content">
    <div class="container">
        <div class="dashboard-header">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h1>
            <p class="user-role-badge"><?php echo ucfirst($user_role); ?></p>
        </div>
        
        <div class="stats-grid">
            <?php if ($user_role == 'student'): ?>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total_applications']; ?></h3>
                        <p>Total Applications</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['pending_applications']; ?></h3>
                        <p>Pending Applications</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['approved_applications']; ?></h3>
                        <p>Approved Applications</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['confirmed_applications']; ?></h3>
                        <p>Confirmed Applications</p>
                    </div>
                </div>
                
            <?php elseif ($user_role == 'recruiter'): ?>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total_jobs']; ?></h3>
                        <p>Total Jobs</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['open_jobs']; ?></h3>
                        <p>Open Jobs</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-inbox"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total_applications']; ?></h3>
                        <p>Total Applications</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-bell"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['pending_requests']; ?></h3>
                        <p>Pending Requests</p>
                    </div>
                </div>
                
            <?php elseif ($user_role == 'admin'): ?>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total_users']; ?></h3>
                        <p>Total Users</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total_jobs']; ?></h3>
                        <p>Total Jobs</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total_applications']; ?></h3>
                        <p>Total Applications</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $stats['pending_applications']; ?></h3>
                        <p>Pending Applications</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="quick-actions">
            <h2>Quick Actions</h2>
            <div class="actions-grid">
                <?php if ($user_role == 'student'): ?>
                    <a href="jobs.php" class="action-card">
                        <i class="fas fa-search"></i>
                        <h3>Browse Jobs</h3>
                        <p>Find your job</p>
                    </a>
                    <a href="my_applications.php" class="action-card">
                        <i class="fas fa-calendar"></i>
                        <h3>My Applications</h3>
                        <p>View application status</p>
                    </a>
                    <a href="profile.php" class="action-card">
                        <i class="fas fa-user"></i>
                        <h3>My Profile</h3>
                        <p>Update your information</p>
                    </a>
                    
                <?php elseif ($user_role == 'recruiter'): ?>
                    <a href="post_job.php" class="action-card">
                        <i class="fas fa-plus-circle"></i>
                        <h3>Add Job</h3>
                        <p>List a new job</p>
                    </a>
                    <a href="my_jobs.php" class="action-card">
                        <i class="fas fa-briefcase"></i>
                        <h3>My Jobs</h3>
                        <p>Manage your listings</p>
                    </a>
                    <a href="application_requests.php" class="action-card">
                        <i class="fas fa-inbox"></i>
                        <h3>Application Requests</h3>
                        <p>Review student requests</p>
                    </a>
                    <a href="profile.php" class="action-card">
                        <i class="fas fa-user"></i>
                        <h3>My Profile</h3>
                        <p>Update your information</p>
                    </a>
                    
                <?php elseif ($user_role == 'admin'): ?>
                    <a href="admin_users.php" class="action-card">
                        <i class="fas fa-users"></i>
                        <h3>Manage Users</h3>
                        <p>View and manage all users</p>
                    </a>
                    <a href="admin_jobs.php" class="action-card">
                        <i class="fas fa-briefcase"></i>
                        <h3>All Jobs</h3>
                        <p>Oversee all listings</p>
                    </a>
                    <a href="admin_applications.php" class="action-card">
                        <i class="fas fa-list"></i>
                        <h3>All Applications</h3>
                        <p>Monitor all applications</p>
                    </a>
                    <a href="profile.php" class="action-card">
                        <i class="fas fa-user"></i>
                        <h3>My Profile</h3>
                        <p>Update your information</p>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>

