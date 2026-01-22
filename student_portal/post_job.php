<?php
require_once __DIR__ . '/config/config.php';
requireRole('recruiter');

$page_title = "Post Job";
$error = '';
$success = '';

$conn = getDBConnection();
$user_id = (int)($_SESSION['user_id'] ?? 0);

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

    if (empty($title) || empty($description) || empty($job_type) || empty($industry) || empty($location) || empty($company_name) || $salary <= 0) {
        $error = 'Please fill in all required fields.';
    } elseif (!in_array($job_type, ['internship', 'part-time', 'full-time'], true)) {
        $error = 'Invalid job type selected.';
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO jobs (recruiter_id, title, description, job_type, industry, location, company_name, salary, requirements, application_deadline)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "issssssdss",
            $user_id,
            $title,
            $description,
            $job_type,
            $industry,
            $location,
            $company_name,
            $salary,
            $requirements,
            $application_deadline
        );

        if ($stmt->execute()) {
            $job_id = $conn->insert_id;

            // Optional: upload job images (kept to match original upload pattern)
            if (!empty($_FILES['images']['name'][0])) {
                $upload_dir = JOB_IMAGES_DIR;
                $is_primary = true;

                foreach ($_FILES['images']['name'] as $key => $filename) {
                    if (($_FILES['images']['error'][$key] ?? 1) !== 0) {
                        continue;
                    }

                    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
                    if (!in_array($file_ext, $allowed_exts, true)) {
                        continue;
                    }

                    $new_filename = 'job_' . $job_id . '_' . time() . '_' . $key . '.' . $file_ext;
                    $upload_path = $upload_dir . $new_filename;

                    if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $upload_path)) {
                        $image_path = 'uploads/jobs/' . $new_filename;
                        $stmt_img = $conn->prepare("INSERT INTO job_images (job_id, image_path, is_primary) VALUES (?, ?, ?)");
                        $stmt_img->bind_param("isi", $job_id, $image_path, $is_primary);
                        $stmt_img->execute();
                        $stmt_img->close();
                        $is_primary = false;
                    }
                }
            }

            $success = 'Job posted successfully!';
            header('refresh:2;url=my_jobs.php');
        } else {
            $error = 'Failed to post job. Please try again.';
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
            <h1>Post New Job</h1>
        </div>

        <div class="form-container">
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data" class="form-card">
                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Job Title *</label>
                        <input type="text" name="title" id="title" required
                               value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="job_type">Job Type *</label>
                        <select name="job_type" id="job_type" required>
                            <option value="">Select Type</option>
                            <option value="internship" <?php echo (isset($_POST['job_type']) && $_POST['job_type'] === 'internship') ? 'selected' : ''; ?>>Internship</option>
                            <option value="part-time" <?php echo (isset($_POST['job_type']) && $_POST['job_type'] === 'part-time') ? 'selected' : ''; ?>>Part-time</option>
                            <option value="full-time" <?php echo (isset($_POST['job_type']) && $_POST['job_type'] === 'full-time') ? 'selected' : ''; ?>>Full-time</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea name="description" id="description" rows="5" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="company_name">Company Name *</label>
                        <input type="text" name="company_name" id="company_name" required
                               value="<?php echo isset($_POST['company_name']) ? htmlspecialchars($_POST['company_name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="industry">Industry *</label>
                        <input type="text" name="industry" id="industry" required
                               value="<?php echo isset($_POST['industry']) ? htmlspecialchars($_POST['industry']) : ''; ?>"
                               placeholder="e.g., Software, Finance, Marketing">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="location">Location *</label>
                        <input type="text" name="location" id="location" required
                               value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>"
                               placeholder="e.g., Dhaka / Remote">
                    </div>

                    <div class="form-group">
                        <label for="salary">Salary (৳) *</label>
                        <input type="number" name="salary" id="salary" required min="0" step="0.01"
                               value="<?php echo isset($_POST['salary']) ? htmlspecialchars($_POST['salary']) : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="requirements">Requirements</label>
                    <textarea name="requirements" id="requirements" rows="4"><?php echo isset($_POST['requirements']) ? htmlspecialchars($_POST['requirements']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="application_deadline">Application Deadline</label>
                    <input type="date" name="application_deadline" id="application_deadline"
                           value="<?php echo isset($_POST['application_deadline']) ? htmlspecialchars($_POST['application_deadline']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="images">Job Images (optional)</label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*">
                    <small>You can select multiple images. First image will be used as primary.</small>
                </div>

                <div class="form-actions">
                    <a href="my_jobs.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Post Job</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
