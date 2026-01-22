<?php
require_once __DIR__ . '/config/config.php';
requireRole('admin');

$page_title = "All Applications";
$conn = getDBConnection();

$apps = $conn->query(
    "SELECT a.*, j.title as job_title, j.company_name, j.location, j.salary,
            su.full_name as student_name, su.email as student_email,
            ru.full_name as recruiter_name, ru.email as recruiter_email
     FROM applications a
     JOIN jobs j ON a.job_id = j.id
     JOIN users su ON a.student_id = su.id
     JOIN users ru ON a.recruiter_id = ru.id
     ORDER BY a.created_at DESC"
);

closeDBConnection($conn);
include 'includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>All Applications</h1>
            <p>Admin view of every student application</p>
        </div>

        <div class="bookings-list">
            <?php if ($apps && $apps->num_rows > 0): ?>
                <?php while ($app = $apps->fetch_assoc()): ?>
                    <div class="booking-card">
                        <div class="booking-header">
                            <h3><?php echo htmlspecialchars($app['job_title']); ?></h3>
                            <span class="status status-<?php echo htmlspecialchars($app['status']); ?>"><?php echo htmlspecialchars(ucfirst($app['status'])); ?></span>
                        </div>

                        <div class="booking-details">
                            <p><strong>Company:</strong> <?php echo htmlspecialchars($app['company_name']); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($app['location']); ?></p>
                            <p><strong>Salary:</strong> ৳<?php echo number_format((float)$app['salary'], 2); ?></p>
                            <p><strong>Student:</strong> <?php echo htmlspecialchars($app['student_name']); ?> (<?php echo htmlspecialchars($app['student_email']); ?>)</p>
                            <p><strong>Recruiter:</strong> <?php echo htmlspecialchars($app['recruiter_name']); ?> (<?php echo htmlspecialchars($app['recruiter_email']); ?>)</p>
                            <p><strong>Applied On:</strong> <?php echo htmlspecialchars($app['application_date']); ?></p>
                            <?php if (!empty($app['expected_start_date'])): ?>
                                <p><strong>Expected Start:</strong> <?php echo htmlspecialchars($app['expected_start_date']); ?></p>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($app['cover_letter'])): ?>
                            <div class="booking-message">
                                <strong>Cover Letter:</strong>
                                <p><?php echo nl2br(htmlspecialchars($app['cover_letter'])); ?></p>
                            </div>
                        <?php endif; ?>

                        <div class="booking-actions">
                            <a href="job_details.php?id=<?php echo (int)$app['job_id']; ?>" class="btn btn-secondary">View Job</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-bookings"><p>No applications found.</p></div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
