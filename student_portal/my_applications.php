<?php
require_once __DIR__ . '/config/config.php';
requireRole('student');

$page_title = "My Applications";
$conn = getDBConnection();

$user_id = (int)($_SESSION['user_id'] ?? 0);

// Allow withdraw (same pattern as original cancel)
if (isset($_GET['withdraw']) && is_numeric($_GET['withdraw'])) {
    $app_id = (int)$_GET['withdraw'];
    $stmt = $conn->prepare("UPDATE applications SET status = 'cancelled' WHERE id = ? AND student_id = ? AND status IN ('pending', 'approved')");
    $stmt->bind_param("ii", $app_id, $user_id);
    $stmt->execute();
    $stmt->close();
    redirect('my_applications.php');
}

$stmt = $conn->prepare(
    "SELECT a.*, j.title as job_title, j.company_name, j.location, j.salary, j.job_type, j.industry, u.full_name as recruiter_name
     FROM applications a
     JOIN jobs j ON a.job_id = j.id
     JOIN users u ON a.recruiter_id = u.id
     WHERE a.student_id = ?
     ORDER BY a.created_at DESC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$applications = $stmt->get_result();
$stmt->close();

closeDBConnection($conn);
include 'includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>My Applications</h1>
            <p>Track your submitted applications</p>
        </div>

        <div class="bookings-list">
            <?php if ($applications->num_rows > 0): ?>
                <?php while ($app = $applications->fetch_assoc()): ?>
                    <div class="booking-card">
                        <div class="booking-header">
                            <h3><?php echo htmlspecialchars($app['job_title']); ?></h3>
                            <span class="status status-<?php echo htmlspecialchars($app['status']); ?>"><?php echo htmlspecialchars(ucfirst($app['status'])); ?></span>
                        </div>

                        <div class="booking-details">
                            <p><strong>Company:</strong> <?php echo htmlspecialchars($app['company_name']); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($app['location']); ?></p>
                            <p><strong>Salary:</strong> ৳<?php echo number_format((float)$app['salary'], 2); ?></p>
                            <p><strong>Type:</strong> <?php echo ucfirst(str_replace('-', ' ', $app['job_type'])); ?> • <?php echo htmlspecialchars($app['industry']); ?></p>
                            <p><strong>Recruiter:</strong> <?php echo htmlspecialchars($app['recruiter_name']); ?></p>
                            <p><strong>Applied On:</strong> <?php echo htmlspecialchars($app['application_date']); ?></p>
                            <?php if (!empty($app['expected_start_date'])): ?>
                                <p><strong>Expected Start:</strong> <?php echo htmlspecialchars($app['expected_start_date']); ?></p>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($app['cover_letter'])): ?>
                            <div class="booking-message">
                                <strong>Cover Letter:</strong>
                                <p><?php echo nl2br(htmlspecialchars($app['cover_letter'])); ?></p>
                            <?php if (!empty($application['cv_file'])): ?>
                                <p><strong>CV:</strong> <a href="uploads/application_cv/<?php echo htmlspecialchars($application['cv_file']); ?>" target="_blank">View</a></p>
                            <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="booking-actions">
                            <a href="job_details.php?id=<?php echo (int)$app['job_id']; ?>" class="btn btn-secondary">View Job</a>
                            <?php if (in_array($app['status'], ['pending','approved'], true)): ?>
                                <a href="my_applications.php?withdraw=<?php echo (int)$app['id']; ?>" class="btn btn-danger" onclick="return confirm('Withdraw this application?');">Withdraw</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-bookings">
                    <p>You haven't applied to any jobs yet.</p>
                    <a href="jobs.php" class="btn btn-primary">Browse Jobs</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
