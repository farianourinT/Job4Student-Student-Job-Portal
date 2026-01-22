<?php
require_once __DIR__ . '/config/config.php';
requireRole('recruiter');

$page_title = "My Jobs";
$conn = getDBConnection();

$user_id = (int)($_SESSION['user_id'] ?? 0);

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM jobs WHERE id = ? AND recruiter_id = ?");
    $stmt->bind_param("ii", $delete_id, $user_id);
    $stmt->execute();
    $stmt->close();
    redirect('my_jobs.php');
}

$stmt = $conn->prepare(
    "SELECT j.*, (SELECT image_path FROM job_images WHERE job_id = j.id AND is_primary = 1 LIMIT 1) as primary_image
     FROM jobs j WHERE j.recruiter_id = ? ORDER BY j.created_at DESC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$jobs = $stmt->get_result();
$stmt->close();

closeDBConnection($conn);
include 'includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="page-header">
            <div>
                <h1>My Job Posts</h1>
                <p>Manage your postings and track applications</p>
            </div>
            <a href="post_job.php" class="btn btn-primary"><i class="fas fa-plus"></i> Post Job</a>
        </div>

        <div class="jobs-grid">
            <?php if ($jobs->num_rows > 0): ?>
                <?php while ($job = $jobs->fetch_assoc()): ?>
                    <div class="job-card">
                        <div class="job-image">
                            <?php if (!empty($job['primary_image'])): ?>
                                <img src="<?php echo htmlspecialchars($job['primary_image']); ?>" alt="Job image">
                            <?php else: ?>
                                <div class="no-image"><i class="fas fa-briefcase"></i></div>
                            <?php endif; ?>
                        </div>

                        <div class="job-content">
                            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                            <p class="job-location"><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($job['company_name']); ?></p>
                            <p class="job-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job['location']); ?></p>
                            <p class="job-price"><i class="fas fa-money-bill-wave"></i> ৳<?php echo number_format((float)$job['salary'], 2); ?></p>
                            <p class="job-type"><i class="fas fa-tags"></i> <?php echo ucfirst(str_replace('-', ' ', $job['job_type'])); ?> • <?php echo htmlspecialchars($job['industry']); ?></p>
                            <p class="job-type"><i class="fas fa-circle"></i> Status: <?php echo htmlspecialchars(ucfirst($job['status'])); ?></p>

                            <div class="job-actions">
                                <a href="job_details.php?id=<?php echo $job['id']; ?>" class="btn btn-secondary btn-block">View</a>
                                <a href="edit_job.php?id=<?php echo $job['id']; ?>" class="btn btn-primary btn-block">Edit</a>
                                <a href="my_jobs.php?delete=<?php echo $job['id']; ?>" class="btn btn-danger btn-block" onclick="return confirm('Delete this job post?');">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-jobs">
                    <p>You haven't posted any jobs yet.</p>
                    <a href="post_job.php" class="btn btn-primary">Post your first job</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
