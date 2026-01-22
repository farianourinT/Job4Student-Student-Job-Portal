<?php
require_once __DIR__ . '/config/config.php';
requireRole('recruiter');

$page_title = "Application Requests";
$conn = getDBConnection();

$user_id = (int)($_SESSION['user_id'] ?? 0);
$error = '';
$success = '';

// Handle approve/reject (same pattern as original booking approvals)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['application_id'])) {
    $app_id = (int)$_POST['application_id'];
    $action = sanitizeInput($_POST['action']);
    if (!in_array($action, ['approve','reject'], true)) {
        $error = 'Invalid action.';
    } else {
        $new_status = $action === 'approve' ? 'approved' : 'rejected';
        $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ? AND recruiter_id = ? AND status = 'pending'");
        $stmt->bind_param("sii", $new_status, $app_id, $user_id);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $success = 'Application updated successfully.';
        } else {
            $error = 'Unable to update application.';
        }
        $stmt->close();
    }
}

$stmt = $conn->prepare(
    "SELECT a.*, j.title as job_title, j.company_name, j.location, j.salary, j.job_type, j.industry,
            u.full_name as student_name, u.email as student_email
     FROM applications a
     JOIN jobs j ON a.job_id = j.id
     JOIN users u ON a.student_id = u.id
     WHERE a.recruiter_id = ?
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
            <h1>Application Requests</h1>
            <p>Review and manage student applications</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

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
                            <p><strong>Student:</strong> <?php echo htmlspecialchars($app['student_name']); ?> (<?php echo htmlspecialchars($app['student_email']); ?>)</p>
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
                            <?php if ($app['status'] === 'pending'): ?>
                                <form method="POST" action="" style="display:inline-flex; gap:10px;">
                                    <input type="hidden" name="application_id" value="<?php echo (int)$app['id']; ?>">
                                    <button type="submit" name="action" value="approve" class="btn btn-primary">Approve</button>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-bookings">
                    <p>No applications received yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
