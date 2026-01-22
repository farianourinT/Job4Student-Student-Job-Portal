<?php
require_once __DIR__ . '/config/config.php';
requireRole('admin');

$page_title = "All Jobs";
$conn = getDBConnection();

// Admin can close/open a job
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'], $_POST['status'])) {
    $job_id = (int)$_POST['job_id'];
    $status = sanitizeInput($_POST['status']);
    if (in_array($status, ['open','closed'], true)) {
        $stmt = $conn->prepare("UPDATE jobs SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $job_id);
        $stmt->execute();
        $stmt->close();
    }
    redirect('admin_jobs.php');
}

$jobs = $conn->query(
    "SELECT j.*, u.full_name as recruiter_name
     FROM jobs j
     JOIN users u ON j.recruiter_id = u.id
     ORDER BY j.created_at DESC"
);

closeDBConnection($conn);
include 'includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>All Jobs</h1>
            <p>Admin view of every job post</p>
        </div>

        <div class="bookings-list">
            <?php if ($jobs && $jobs->num_rows > 0): ?>
                <?php while ($job = $jobs->fetch_assoc()): ?>
                    <div class="booking-card">
                        <div class="booking-header">
                            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                            <span class="status status-<?php echo htmlspecialchars($job['status']); ?>"><?php echo htmlspecialchars(ucfirst($job['status'])); ?></span>
                        </div>

                        <div class="booking-details">
                            <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company_name']); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                            <p><strong>Salary:</strong> ৳<?php echo number_format((float)$job['salary'], 2); ?></p>
                            <p><strong>Type:</strong> <?php echo ucfirst(str_replace('-', ' ', $job['job_type'])); ?> • <?php echo htmlspecialchars($job['industry']); ?></p>
                            <p><strong>Recruiter:</strong> <?php echo htmlspecialchars($job['recruiter_name']); ?></p>
                        </div>

                        <div class="booking-actions">
                            <a href="job_details.php?id=<?php echo (int)$job['id']; ?>" class="btn btn-secondary">View</a>
                            <form method="POST" action="" style="display:inline-flex; gap:10px;">
                                <input type="hidden" name="job_id" value="<?php echo (int)$job['id']; ?>">
                                <?php if ($job['status'] === 'open'): ?>
                                    <button type="submit" name="status" value="closed" class="btn btn-danger">Close</button>
                                <?php else: ?>
                                    <button type="submit" name="status" value="open" class="btn btn-primary">Open</button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-bookings"><p>No jobs found.</p></div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
