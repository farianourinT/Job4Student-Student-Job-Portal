<?php require __DIR__ . '/../layouts/header.php'; ?>
<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>Apply to Job</h1>
        </div>

        <div class="application-container">
            <div class="application-form-card">
                <h2><?php echo htmlspecialchars($job['title']); ?></h2>
                <p class="job-location"><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($job['company_name']); ?></p>
                <p class="job-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job['location']); ?></p>
                <p class="job-price"><strong>৳<?php echo number_format((float)$job['salary'], 2); ?></strong></p>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form id="applyForm" id="applyForm" method="POST" action="" class="application-form" enctype="multipart/form-data">
                    <div id="applyMsg" class="form-message" style="display:none;"></div>
<div class="form-group">
                        <label for="expected_start_date">Expected Start Date *</label>
                        <input type="date" name="expected_start_date" id="expected_start_date" required min="<?php echo date('Y-m-d'); ?>"
                               value="<?php echo isset($_POST['expected_start_date']) ? htmlspecialchars($_POST['expected_start_date']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="cover_letter">Cover Letter / Message</label>
                        <textarea name="cover_letter" id="cover_letter" rows="6"
                                  placeholder="Write a short cover letter (optional)..."><?php echo isset($_POST['cover_letter']) ? htmlspecialchars($_POST['cover_letter']) : ''; ?></textarea>
                    </div>

                <div class="form-group">
                    <label for="application_cv">CV / Resume (Optional)</label>
                    <input type="file" id="application_cv" name="application_cv" accept=".pdf,.doc,.docx">
                    <small>You can upload a specific CV for this job. If you don't upload, your profile CV will be used (if available).</small>
                </div>

                    <div class="form-actions">
                        <a href="job_details.php?id=<?php echo $job_id; ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Submit Application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>


<?php require __DIR__ . '/../layouts/footer.php'; ?>
