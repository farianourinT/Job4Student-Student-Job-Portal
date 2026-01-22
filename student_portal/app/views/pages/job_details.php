<?php require __DIR__ . '/../layouts/header.php'; ?>
<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1><?php echo htmlspecialchars($job['title']); ?></h1>
            <p><?php echo htmlspecialchars($job['company_name']); ?> • <?php echo htmlspecialchars($job['location']); ?></p>
        </div>

        <div class="details-layout">
            <div class="details-main">
                <div class="details-card">
                    <?php if (!empty($job['primary_image'])): ?>
                        <img class="details-hero" src="<?php echo htmlspecialchars($job['primary_image']); ?>" alt="Job image">
                    <?php endif; ?>

                    <div class="details-meta">
                        <div><i class="fas fa-tags"></i> <?php echo ucfirst(str_replace('-', ' ', $job['job_type'])); ?> • <?php echo htmlspecialchars($job['industry']); ?></div>
                        <div><i class="fas fa-money-bill-wave"></i> ৳<?php echo number_format((float)$job['salary'], 2); ?></div>
                        <?php if (!empty($job['application_deadline'])): ?>
                            <div><i class="fas fa-calendar"></i> Deadline: <?php echo htmlspecialchars($job['application_deadline']); ?></div>
                        <?php endif; ?>
                    </div>

                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>

                    <?php if (!empty($job['requirements'])): ?>
                        <h3>Requirements</h3>
                        <p><?php echo nl2br(htmlspecialchars($job['requirements'])); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <aside class="details-sidebar">
                <div class="details-card">
                    <h3>Recruiter</h3>
                    <p><i class="fas fa-user-tie"></i> <?php echo htmlspecialchars($job['recruiter_name']); ?></p>
                    <p><i class="fas fa-circle"></i> Status: <?php echo htmlspecialchars(ucfirst($job['status'])); ?></p>

                    <?php if (hasRole('student')): ?>
                        <?php if ($already_applied): ?>
                            <a href="my_applications.php" class="btn btn-primary btn-block">View My Applications</a>
                        <?php else: ?>
                            <?php if ($job['status'] === 'open'): ?>
                                <a href="apply_job.php?id=<?php echo $job_id; ?>" class="btn btn-primary btn-block">Apply Now</a>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-block" disabled>Applications Closed</button>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="muted">Log in as a Student to apply.</p>
                        <a href="login.php" class="btn btn-primary btn-block">Login</a>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>
</main>


<?php require __DIR__ . '/../layouts/footer.php'; ?>
