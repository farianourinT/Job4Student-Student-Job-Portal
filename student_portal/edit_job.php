<?php
require_once __DIR__ . '/config/config.php';
requireRole('recruiter');

$page_title = "Edit Job";
$error = '';
$success = '';

$conn = getDBConnection();
$user_id = (int)($_SESSION['user_id'] ?? 0);
$job_id = (int)($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ? AND recruiter_id = ?");
$stmt->bind_param("ii", $job_id, $user_id);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$job) {
    closeDBConnection($conn);
    redirect('my_jobs.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $job_type = sanitizeInput($_POST['job_type'] ?? '');
    $industry = sanitizeInput($_POST['industry'] ?? '');
    $location = sanitizeInput($_POST['location'] ?? '');
    $company_name = sanitizeInput($_POST['company_name'] ?? '');
    $salary = $_POST['salary'] ?? 0;
    $requirements = sanitizeInput($_POST['requirements'] ?? '');
    $application_deadline = $_POST['application_deadline'] ?? null;
    $status = sanitizeInput($_POST['status'] ?? 'open');

    if (empty($title) || empty($description) || empty($job_type) || empty($industry) || empty($location) || empty($company_name) || $salary <= 0) {
        $error = 'Please fill in all required fields.';
    } elseif (!in_array($job_type, ['internship', 'part-time', 'full-time'], true)) {
        $error = 'Invalid job type selected.';
    } elseif (!in_array($status, ['open', 'closed'], true)) {
        $error = 'Invalid status.';
    } else {
        $stmt = $conn->prepare(
            "UPDATE jobs SET title = ?, description = ?, job_type = ?, industry = ?, location = ?, company_name = ?, salary = ?, requirements = ?, application_deadline = ?, status = ?
             WHERE id = ? AND recruiter_id = ?"
        );
        $stmt->bind_param(
            "ssssssdsssii",
            $title,
            $description,
            $job_type,
            $industry,
            $location,
            $company_name,
            $salary,
            $requirements,
            $application_deadline,
            $status,
            $job_id,
            $user_id
        );

        if ($stmt->execute()) {
            $success = 'Job updated successfully!';
            // refresh job values for display
            $job = array_merge($job, [
                'title' => $title,
                'description' => $description,
                'job_type' => $job_type,
                'industry' => $industry,
                'location' => $location,
                'company_name' => $company_name,
                'salary' => $salary,
                'requirements' => $requirements,
                'application_deadline' => $application_deadline,
                'status' => $status,
            ]);
        } else {
            $error = 'Failed to update job. Please try again.';
        }
        $stmt->close();
    }
}

closeDBConnection($conn);
include 'includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>Edit Job</h1>
        </div>

        <div class="form-container">
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="" class="form-card">
                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Job Title *</label>
                        <input type="text" name="title" id="title" required value="<?php echo htmlspecialchars($job['title']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="job_type">Job Type *</label>
                        <select name="job_type" id="job_type" required>
                            <option value="internship" <?php echo ($job['job_type'] === 'internship') ? 'selected' : ''; ?>>Internship</option>
                            <option value="part-time" <?php echo ($job['job_type'] === 'part-time') ? 'selected' : ''; ?>>Part-time</option>
                            <option value="full-time" <?php echo ($job['job_type'] === 'full-time') ? 'selected' : ''; ?>>Full-time</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea name="description" id="description" rows="5" required><?php echo htmlspecialchars($job['description']); ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="company_name">Company Name *</label>
                        <input type="text" name="company_name" id="company_name" required value="<?php echo htmlspecialchars($job['company_name']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="industry">Industry *</label>
                        <input type="text" name="industry" id="industry" required value="<?php echo htmlspecialchars($job['industry']); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="location">Location *</label>
                        <input type="text" name="location" id="location" required value="<?php echo htmlspecialchars($job['location']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="salary">Salary (৳) *</label>
                        <input type="number" name="salary" id="salary" required min="0" step="0.01" value="<?php echo htmlspecialchars($job['salary']); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="requirements">Requirements</label>
                    <textarea name="requirements" id="requirements" rows="4"><?php echo htmlspecialchars($job['requirements'] ?? ''); ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="application_deadline">Application Deadline</label>
                        <input type="date" name="application_deadline" id="application_deadline" value="<?php echo htmlspecialchars($job['application_deadline'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="open" <?php echo ($job['status'] === 'open') ? 'selected' : ''; ?>>Open</option>
                            <option value="closed" <?php echo ($job['status'] === 'closed') ? 'selected' : ''; ?>>Closed</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="my_jobs.php" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
